<?php

namespace App\Http\Controllers;

use App\Http\Requests\Pallet\StorePalletRequest;
use App\Models\Pallet;
use App\Models\PolishType;
use App\Models\ProductType;
use App\Models\StockPosition;
use App\Models\StoneType;
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
            ->with('stockPositions.productType', 'stockPositions.polishType', 'stockPositions.stoneType');

        // Фильтрация по номеру поддона
        if ($request->filled('filter_pallet_number')) {
            $query->where('number', 'like', '%' . $request->input('filter_pallet_number') . '%');
        }

        // Фильтрация по статусу поддона
        if ($request->filled('filter_status')) {
            $query->where('status', $request->input('filter_status'));
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

        // Фильтрация по типу камня
        if ($request->filled('filter_stone_type_id')) {
            $query->whereHas('stockPositions', function ($q) use ($request) {
                $q->where('stone_type_id', $request->input('filter_stone_type_id'));
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
        $stoneTypes = StoneType::getActive();
        $palletStatuses = Pallet::getAvailableStatuses();

        return view('dashboard', compact('pallets', 'productTypes', 'polishTypes', 'stoneTypes', 'palletStatuses'));
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

            // Создаем поддон с автоматически сгенерированным номером
            $pallet = Pallet::create([
                'number' => Pallet::generateNextNumber(),
            ]);

            // Создаем позиции для поддона (если есть)
            $positions = $request->getPositions();
            $positionsCount = count($positions);

            foreach ($positions as $positionData) {
                StockPosition::create([
                    'pallet_id' => $pallet->id,
                    'product_type_id' => $positionData['product_type_id'],
                    'polish_type_id' => $positionData['polish_type_id'],
                    'length' => $positionData['length'],
                    'width' => $positionData['width'],
                    'thickness' => $positionData['thickness'],
                    'quantity' => $positionData['quantity'],
                    'weight' => $positionData['weight'],
                ]);
            }

            // Генерируем QR-код для поддона
            $this->generateQrCode($pallet);

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
        $pallet->load(['stockPositions.productType', 'stockPositions.polishType', 'stockPositions.stoneType']);

        return view('pallet.show', compact('pallet'));
    }

    /**
     * Отображение формы редактирования поддона.
     */
    public function edit(Pallet $pallet): View
    {
        $pallet->load(['stockPositions.productType', 'stockPositions.polishType', 'stockPositions.stoneType']);

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

            // Удаляем QR-код поддона и изображения связанных позиций
            $this->deletePalletFiles($pallet);

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
     * Изменение статуса поддона.
     */
    public function updateStatus(Request $request, Pallet $pallet): RedirectResponse
    {
        $request->validate([
            'status' => 'required|string|in:' . implode(',', array_keys(Pallet::getAvailableStatuses())),
        ]);

        try {
            $oldStatus = $pallet->status;
            $newStatus = $request->status;

            if ($pallet->changeStatus($newStatus)) {
                return back()->with('success', 'Статус поддона изменен с "' . $oldStatus . '" на "' . $newStatus . '".');
            } else {
                return back()->with('error', 'Не удалось изменить статус поддона.');
            }
        } catch (Exception $exception) {
            return back()->with('error', 'Ошибка при изменении статуса: ' . $exception->getMessage());
        }
    }

    /**
     * Генерация QR-кода для поддона.
     */
    private function generateQrCode(Pallet $pallet): void
    {
        $qrData = route('pallet.show', $pallet->id);
        $fileName = 'qr_code_pallet_' . $pallet->id . '.svg';
        $filePath = 'qr_codes/' . $fileName;

        if (!Storage::disk('public')->exists('qr_codes')) {
            Storage::disk('public')->makeDirectory('qr_codes');
        }

        // Генерируем QR-код в память
        $qrSvg = QrCode::format('svg')
            ->size(200)
            ->margin(1)
            ->encoding('UTF-8')
            ->generate($qrData);

        // Добавляем номер поддона в SVG
        $qrSvgWithText = $this->addTextToSvg($qrSvg, $pallet->number);

        // Сохраняем файл
        Storage::disk('public')->put($filePath, $qrSvgWithText);

        $pallet->update(['qr_code_path' => $filePath]);
    }

    /**
     * Добавление текста в SVG.
     */
    private function addTextToSvg(string $svg, string $text): string
    {
        // Парсим SVG и получаем размеры
        $dom = new \DOMDocument();
        $dom->loadXML($svg);
        $svgElement = $dom->getElementsByTagName('svg')->item(0);

        $width = (float) $svgElement->getAttribute('width');
        $height = (float) $svgElement->getAttribute('height');

        // Увеличиваем высоту для текста
        $newHeight = $height + 35;
        $svgElement->setAttribute('height', $newHeight);
        $svgElement->setAttribute('viewBox', '0 0 ' . $width . ' ' . $newHeight);

        // Создаем белый фон для текста
        $bgRect = $dom->createElement('rect');
        $bgRect->setAttribute('x', '0');
        $bgRect->setAttribute('y', $height);
        $bgRect->setAttribute('width', $width);
        $bgRect->setAttribute('height', '35');
        $bgRect->setAttribute('fill', 'white');
        $svgElement->appendChild($bgRect);

        // Создаем элемент текста
        $textElement = $dom->createElement('text');
        $textElement->setAttribute('x', $width / 2);
        $textElement->setAttribute('y', $height + 22);
        $textElement->setAttribute('text-anchor', 'middle');
        $textElement->setAttribute('font-family', 'Arial, sans-serif');
        $textElement->setAttribute('font-size', '28');
        $textElement->setAttribute('font-weight', 'bold');
        $textElement->setAttribute('fill', 'black');
        $textElement->nodeValue = $text;

        // Добавляем текст в SVG
        $svgElement->appendChild($textElement);

        return $dom->saveXML();
    }

    /**
     * Удаление файлов поддона.
     */
    private function deletePalletFiles(Pallet $pallet): void
    {
        // Удаляем QR-код поддона
        if ($pallet->getQrCodePath() && Storage::disk('public')->exists($pallet->getQrCodePath())) {
            Storage::disk('public')->delete($pallet->getQrCodePath());
        }

        // Удаляем файлы позиций
        foreach ($pallet->stockPositions as $position) {
            if ($position->getImagePath()) {
                $imagePath = str_replace('/storage/', '', parse_url($position->getImagePath(), PHP_URL_PATH));
                if (Storage::disk('public')->exists($imagePath)) {
                    Storage::disk('public')->delete($imagePath);
                }
            }
        }
    }

    /**
     * Скачивание QR-кода поддона.
     */
    public function downloadQr(Pallet $pallet)
    {
        if (!$pallet->getQrCodePath()) {
            return back()->with('error', 'QR-код не найден');
        }

        $path = storage_path('app/public/' . $pallet->getQrCodePath());

        if (!file_exists($path)) {
            return back()->with('error', 'Файл QR-кода не найден');
        }

        $fileName = 'qr_code_pallet_' . $pallet->number . '.svg';

        return response()->download($path, $fileName);
    }

    /**
     * Экспорт поддонов в Excel.
     */
    public function export()
    {
        return Excel::download(new PalletsExport, 'pallets.xlsx');
    }
}
