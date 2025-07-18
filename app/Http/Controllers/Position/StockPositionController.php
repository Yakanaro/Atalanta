<?php

namespace App\Http\Controllers\Position;

use App\Http\Controllers\Controller;
use App\Http\Requests\StockPosition\StoreStockPositionRequest;
use App\Http\Requests\StockPosition\UpdateStockPositionRequest;
use App\Models\Pallet;
use App\Models\PolishType;
use App\Models\ProductType;
use App\Models\StockPosition;
use App\Models\StoneType;
use App\UseCases\StockPosition\FilterStockPositionsUseCase;
use App\UseCases\StockPosition\FormatStockPositionsUseCase;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Exports\StockPositionsExport;
use Maatwebsite\Excel\Facades\Excel;

class StockPositionController extends Controller
{
    private FilterStockPositionsUseCase $filterUseCase;
    private FormatStockPositionsUseCase $formatUseCase;

    public function __construct(
        FilterStockPositionsUseCase $filterUseCase,
        FormatStockPositionsUseCase $formatUseCase
    ) {
        $this->filterUseCase = $filterUseCase;
        $this->formatUseCase = $formatUseCase;
    }

    public function index(Request $request): View
    {
        $stockPositions = $this->filterUseCase->execute($request);

        // Загружаем связанные поддоны
        $stockPositions->load('pallet');

        $stockPositions = $this->formatUseCase->execute($stockPositions);

        $productTypes = ProductType::getActive();
        $polishTypes = PolishType::getActive();

        return view('dashboard', compact('stockPositions', 'productTypes', 'polishTypes'));
    }

    public function create(Request $request): View
    {
        $polishTypes = PolishType::getForSelect();
        $productTypes = ProductType::getForSelect();
        $stoneTypes = StoneType::getForSelect();
        $pallets = Pallet::getForSelect();

        $selectedPalletId = $request->query('pallet_id');

        return view('stockPosition.create', compact('polishTypes', 'productTypes', 'stoneTypes', 'pallets', 'selectedPalletId'));
    }

    public function store(StoreStockPositionRequest $request): RedirectResponse
    {
        try {
            $stockPosition = StockPosition::create($request->toArray());

            if ($request->hasImage()) {
                $image = $request->getImage();

                if (!Storage::disk('public')->exists('position_images')) {
                    Storage::disk('public')->makeDirectory('position_images');
                }

                $path = Storage::disk('public')->put('position_images', $image);
                $url = Storage::url($path);

                $stockPosition->update([
                    'image_path' => $url
                ]);
            }

            // QR-код теперь генерируется для поддона, а не для позиций

            return redirect()
                ->route('dashboard')
                ->with('success', 'Позиция успешно создана.');
        } catch (Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }

    public function show(StockPosition $stockPosition): View
    {
        $stockPosition->load(['polishType', 'productType', 'pallet', 'stoneType']);

        $stockPosition = $this->formatUseCase->execute(collect([$stockPosition]))->first();

        return view('stockPosition.show', compact('stockPosition'));
    }

    public function edit(StockPosition $stockPosition): View
    {
        $polishTypes = PolishType::getForSelect();
        $productTypes = ProductType::getForSelect();
        $stoneTypes = StoneType::getForSelect();
        $pallets = Pallet::getForSelect();

        // Загружаем связанный поддон
        $stockPosition->load('pallet');

        return view('stockPosition.edit', compact('stockPosition', 'polishTypes', 'productTypes', 'stoneTypes', 'pallets'));
    }

    public function update(UpdateStockPositionRequest $request, StockPosition $stockPosition): RedirectResponse
    {
        try {
            $validated = $request->toArray();

            if ($request->hasImage()) {
                if ($stockPosition->getImagePath()) {
                    $oldPath = str_replace('/storage/', '', parse_url($stockPosition->getImagePath(), PHP_URL_PATH));
                    if (Storage::disk('public')->exists($oldPath)) {
                        Storage::disk('public')->delete($oldPath);
                    }
                }

                $image = $request->getImage();

                if (!Storage::disk('public')->exists('position_images')) {
                    Storage::disk('public')->makeDirectory('position_images');
                }

                $path = Storage::disk('public')->put('position_images', $image);
                $url = Storage::url($path);

                $validated['image_path'] = $url;
            }

            $stockPosition->update($validated);

            return redirect()
                ->route('stockPosition.show', $stockPosition->id)
                ->with('success', 'Позиция успешно обновлена.');
        } catch (Exception $exception) {
            return back()->with('error', $exception->getMessage())->withInput();
        }
    }

    public function destroy(StockPosition $stockPosition): RedirectResponse
    {
        try {
            if ($stockPosition->getImagePath()) {
                $imagePath = str_replace('/storage/', '', parse_url($stockPosition->getImagePath(), PHP_URL_PATH));
                if (Storage::disk('public')->exists($imagePath)) {
                    Storage::disk('public')->delete($imagePath);
                }
            }

            $stockPosition->delete();

            return redirect()
                ->route('dashboard')
                ->with('success', 'Позиция успешно удалена.');
        } catch (Exception $exception) {
            return back()->with('error', 'Ошибка при удалении позиции: ' . $exception->getMessage());
        }
    }



    public function export()
    {
        return Excel::download(new StockPositionsExport, 'stock_positions.xlsx');
    }
}
