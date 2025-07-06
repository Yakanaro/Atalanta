<?php

namespace App\UseCases\StockPosition;

use App\Models\StockPosition;
use Illuminate\Support\Collection;

class FormatStockPositionsUseCase
{
    public function execute(Collection $stockPositions): Collection
    {
        foreach ($stockPositions as $position) {
            $position->formatted_length = $this->formatNumber($position->getLength());
            $position->formatted_width = $this->formatNumber($position->getWidth());
            $position->formatted_thickness = $this->formatNumber($position->getThickness());
            if (method_exists($position, 'getWeight')) {
                $position->formatted_weight = $this->formatNumber($position->getWeight());
            }
        }

        return $stockPositions;
    }

    private function formatNumber($number): string
    {
        if ($number === null || !is_numeric($number)) {
            return '-';
        }

        return floor($number) == $number ? number_format($number, 0) : (string)$number;
    }
}
