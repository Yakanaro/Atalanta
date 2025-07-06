<?php

namespace App\Exports;

use App\Models\StockPosition;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class StockPositionsExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return StockPosition::with(['polishType', 'productType'])->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID',
            'Тип',
            'Длина (см)',
            'Ширина (см)',
            'Толщина (см)',
            'Вес (кг)',
            'Количество',
            'Вид полировки',
            'Номер поддона',
            'Дата создания',
            'Дата обновления'
        ];
    }

    /**
     * @param StockPosition $position
     * @return array
     */
    public function map($position): array
    {
        return [
            $position->id,
            $position->productType ? $position->productType->name : '-',
            $this->formatNumber($position->length),
            $this->formatNumber($position->width),
            $this->formatNumber($position->thickness),
            $this->formatNumber($position->weight),
            $position->quantity,
            $position->polishType ? $position->polishType->name : '-',
            $position->pallet_number ?? '-',
            $position->created_at->format('d.m.Y H:i'),
            $position->updated_at->format('d.m.Y H:i')
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

        return floor($number) == $number ? number_format($number, 0) : (string)$number;
    }
}
