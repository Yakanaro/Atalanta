<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PositionStatisticsExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
{
    public function __construct(
        private Collection $rows
    ) {
    }

    public function collection(): Collection
    {
        return $this->rows->map(function ($row) {
            return [
                'product_type' => $row->product_type_name,
                'size' => $this->formatSize($row),
                'polish_type' => $row->polish_type_name,
                'stone_type' => $row->stone_type_name,
                'total_quantity' => (int) $row->total_quantity,
                'total_weight' => $this->formatDecimal((float) $row->total_weight),
                'positions_count' => (int) $row->positions_count,
                'share_percent' => $this->formatDecimal((float) $row->share_percent),
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Тип продукции',
            'Размер (см)',
            'Вид полировки',
            'Тип камня',
            'Суммарное количество',
            'Суммарный вес (кг)',
            'Кол-во позиций',
            'Доля (%)',
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }

    private function formatSize(object $row): string
    {
        return $this->formatDecimal((float) $row->length)
            . ' × ' . $this->formatDecimal((float) $row->width)
            . ' × ' . $this->formatDecimal((float) $row->thickness);
    }

    private function formatDecimal(float $value): string
    {
        if ((float) ((int) $value) === $value) {
            return number_format($value, 0, '.', '');
        }

        return number_format($value, 2, '.', '');
    }
}

