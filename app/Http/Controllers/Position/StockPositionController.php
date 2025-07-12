<?php

namespace App\Http\Controllers\Position;

use App\Http\Controllers\Controller;
use App\Http\Requests\StockPosition\StoreStockPositionRequest;
use App\Http\Requests\StockPosition\UpdateStockPositionRequest;
use App\Models\Pallet;
use App\Models\PolishType;
use App\Models\ProductType;
use App\Models\StockPosition;
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
        $pallets = Pallet::getForSelect();

        $selectedPalletId = $request->query('pallet_id');

        return view('stockPosition.create', compact('polishTypes', 'productTypes', 'pallets', 'selectedPalletId'));
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

            $qrData = route('stockPosition.show', $stockPosition->id);

            $fileName = 'qr_code_' . $stockPosition->id . '.svg';
            $filePath = 'qr_codes/' . $fileName;

            if (!Storage::disk('public')->exists('qr_codes')) {
                Storage::disk('public')->makeDirectory('qr_codes');
            }

            QrCode::format('svg')
                ->size(200)
                ->margin(1)
                ->encoding('UTF-8')
                ->generate($qrData, storage_path('app/public/' . $filePath));

            $stockPosition->update([
                'qr_code_path' => $filePath
            ]);

            return redirect()
                ->route('dashboard')
                ->with('success', 'Позиция успешно создана. QR-код сгенерирован.');
        } catch (Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }

    public function show(StockPosition $stockPosition): View
    {
        $stockPosition->load(['polishType', 'productType', 'pallet']);

        $stockPosition = $this->formatUseCase->execute(collect([$stockPosition]))->first();

        return view('stockPosition.show', compact('stockPosition'));
    }

    public function edit(StockPosition $stockPosition): View
    {
        $polishTypes = PolishType::getForSelect();
        $productTypes = ProductType::getForSelect();
        $pallets = Pallet::getForSelect();

        // Загружаем связанный поддон
        $stockPosition->load('pallet');

        return view('stockPosition.edit', compact('stockPosition', 'polishTypes', 'productTypes', 'pallets'));
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
            if ($stockPosition->getQrCodePath() && Storage::disk('public')->exists($stockPosition->getQrCodePath())) {
                Storage::disk('public')->delete($stockPosition->getQrCodePath());
            }

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

    public function downloadQr(StockPosition $stockPosition)
    {
        if (!$stockPosition->getQrCodePath()) {
            return back()->with('error', 'QR-код не найден');
        }

        $path = storage_path('app/public/' . $stockPosition->getQrCodePath());

        if (!file_exists($path)) {
            return back()->with('error', 'Файл QR-кода не найден');
        }

        $fileName = 'qr_code_position_' . $stockPosition->id . '.svg';

        return response()->download($path, $fileName);
    }

    public function export()
    {
        return Excel::download(new StockPositionsExport, 'stock_positions.xlsx');
    }
}
