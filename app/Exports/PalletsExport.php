<?php

namespace App\Exports;

use App\Models\Pallet;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PalletsExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Pallet::with(['stockPositions.productType', 'stockPositions.polishType'])->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID поддона',
            'Номер поддона',
            'Количество позиций',
            'Общий вес (кг)',
            'Общее количество',
            'Основные типы продукции',
            'Основные виды полировки',
            'Дата создания',
            'Дата обновления'
        ];
    }

    /**
     * @param Pallet $pallet
     * @return array
     */
    public function map($pallet): array
    {
        $positions = $pallet->stockPositions;

        // Подсчитываем статистику
        $totalWeight = $positions->sum('weight');
        $totalQuantity = $positions->sum('quantity');
        $positionsCount = $positions->count();

        // Получаем основные типы продукции
        $productTypes = $positions->whereNotNull('productType')
            ->groupBy('productType.name')
            ->map(function ($group) {
                return $group->count();
            })
            ->sortDesc()
            ->take(3)
            ->keys()
            ->join(', ');

        // Получаем основные виды полировки
        $polishTypes = $positions->whereNotNull('polishType')
            ->groupBy('polishType.name')
            ->map(function ($group) {
                return $group->count();
            })
            ->sortDesc()
            ->take(3)
            ->keys()
            ->join(', ');

        return [
            $pallet->id,
            $pallet->number,
            $positionsCount,
            $this->formatNumber($totalWeight),
            $totalQuantity,
            $productTypes ?: '-',
            $polishTypes ?: '-',
            $pallet->created_at->format('d.m.Y H:i'),
            $pallet->updated_at->format('d.m.Y H:i')
        ];
    }

    /**
     * @param Worksheet $sheet
     */
    public function styles(Worksheet $sheet)
    {
        return [
            // Стиль для заголовков
            1 => ['font' => ['bold' => true]],
        ];
    }

    /**
     * Форматирование числа
     * 
     * @param mixed $number
     * @return string
     */
    private function formatNumber($number): string
    {
        if (!is_numeric($number)) {
            return (string)$number;
        }

        return number_format($number, 2);
    }
}
