<?php

namespace App\Http\Controllers;

use App\Http\Requests\Pallet\StorePalletRequest;
use App\Models\Pallet;
use App\Models\PolishType;
use App\Models\ProductType;
use App\Models\StockPosition;
use App\Exports\PalletsExport;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Maatwebsite\Excel\Facades\Excel;

class PalletController extends Controller
{
    /**
     * Отображение списка поддонов.
     */
    public function index(Request $request): RedirectResponse
    {
        return redirect()->route('dashboard');
    }

    /**
     * Отображение поддонов на главной странице.
     */
    public function dashboard(Request $request): View
    {
        $query = Pallet::query()
            ->withCount('stockPositions')
            ->with('stockPositions.productType', 'stockPositions.polishType');

        // Фильтрация по номеру поддона
        if ($request->filled('filter_pallet_number')) {
            $query->where('number', 'like', '%' . $request->input('filter_pallet_number') . '%');
        }

        // Фильтрация по типу продукции
        if ($request->filled('filter_product_type_id')) {
            $query->whereHas('stockPositions', function ($q) use ($request) {
                $q->where('product_type_id', $request->input('filter_product_type_id'));
            });
        }

        // Фильтрация по типу полировки
        if ($request->filled('filter_polish_type_id')) {
            $query->whereHas('stockPositions', function ($q) use ($request) {
                $q->where('polish_type_id', $request->input('filter_polish_type_id'));
            });
        }

        $pallets = $query->orderBy('created_at', 'desc')->get();

        // Добавляем статистику для каждого поддона
        $pallets->each(function ($pallet) {
            $pallet->total_quantity = $pallet->stockPositions->sum('quantity');
            $pallet->total_weight = $pallet->stockPositions->sum('weight');
        });

        $productTypes = ProductType::getActive();
        $polishTypes = PolishType::getActive();

        return view('dashboard', compact('pallets', 'productTypes', 'polishTypes'));
    }

    /**
     * Отображение формы создания поддона.
     */
    public function create(): View
    {
        $polishTypes = PolishType::getForSelect();
        $productTypes = ProductType::getForSelect();

        return view('pallet.create', compact('polishTypes', 'productTypes'));
    }

    /**
     * Создание поддона с позициями.
     */
    public function store(StorePalletRequest $request): RedirectResponse
    {
        try {
            DB::beginTransaction();

            // Создаем поддон
            $pallet = Pallet::create([
                'number' => $request->getPalletNumber(),
            ]);

            // Создаем позиции для поддона (если есть)
            $positions = $request->getPositions();
            $positionsCount = count($positions);
            
            foreach ($positions as $positionData) {
                $stockPosition = StockPosition::create([
                    'pallet_id' => $pallet->id,
                    'product_type_id' => $positionData['product_type_id'],
                    'polish_type_id' => $positionData['polish_type_id'],
                    'length' => $positionData['length'],
                    'width' => $positionData['width'],
                    'thickness' => $positionData['thickness'],
                    'quantity' => $positionData['quantity'],
                    'weight' => $positionData['weight'],
                ]);

                // Генерируем QR-код для позиции
                $this->generateQrCode($stockPosition);
            }

            DB::commit();

            $message = $positionsCount > 0 
                ? 'Поддон успешно создан с ' . $positionsCount . ' позициями.'
                : 'Поддон успешно создан. Позиции можно добавить позже.';

            return redirect()
                ->route('pallet.show', $pallet)
                ->with('success', $message);
        } catch (Exception $exception) {
            DB::rollBack();
            return back()->with('error', 'Ошибка при создании поддона: ' . $exception->getMessage())->withInput();
        }
    }

    /**
     * Отображение поддона с позициями.
     */
    public function show(Pallet $pallet): View
    {
        $pallet->load(['stockPositions.productType', 'stockPositions.polishType']);

        return view('pallet.show', compact('pallet'));
    }

    /**
     * Отображение формы редактирования поддона.
     */
    public function edit(Pallet $pallet): View
    {
        $pallet->load(['stockPositions.productType', 'stockPositions.polishType']);

        return view('pallet.edit', compact('pallet'));
    }

    /**
     * Обновление поддона.
     */
    public function update(Request $request, Pallet $pallet): RedirectResponse
    {
        $request->validate([
            'number' => 'required|string|max:255|unique:pallets,number,' . $pallet->id,
        ]);

        try {
            $pallet->update([
                'number' => $request->number,
            ]);

            return redirect()
                ->route('pallet.show', $pallet)
                ->with('success', 'Поддон успешно обновлен.');
        } catch (Exception $exception) {
            return back()->with('error', 'Ошибка при обновлении поддона: ' . $exception->getMessage())->withInput();
        }
    }

    /**
     * Удаление поддона.
     */
    public function destroy(Pallet $pallet): RedirectResponse
    {
        try {
            DB::beginTransaction();

            // Удаляем QR-коды и изображения связанных позиций
            foreach ($pallet->stockPositions as $position) {
                $this->deletePositionFiles($position);
            }

            // Удаляем все позиции поддона
            $pallet->stockPositions()->delete();

            // Удаляем поддон
            $pallet->delete();

            DB::commit();

            return redirect()
                ->route('dashboard')
                ->with('success', 'Поддон и все связанные позиции успешно удалены.');
        } catch (Exception $exception) {
            DB::rollBack();
            return back()->with('error', 'Ошибка при удалении поддона: ' . $exception->getMessage());
        }
    }

    /**
     * Генерация QR-кода для позиции.
     */
    private function generateQrCode(StockPosition $stockPosition): void
    {
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

        $stockPosition->update(['qr_code_path' => $filePath]);
    }

    /**
     * Удаление файлов позиции.
     */
    private function deletePositionFiles(StockPosition $position): void
    {
        // Удаляем QR-код
        if ($position->getQrCodePath() && Storage::disk('public')->exists($position->getQrCodePath())) {
            Storage::disk('public')->delete($position->getQrCodePath());
        }

        // Удаляем изображение
        if ($position->getImagePath()) {
            $imagePath = str_replace('/storage/', '', parse_url($position->getImagePath(), PHP_URL_PATH));
            if (Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }
        }
    }

    /**
     * Экспорт поддонов в Excel.
     */
    public function export()
    {
        return Excel::download(new PalletsExport, 'pallets.xlsx');
    }
}
