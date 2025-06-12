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
        }
        
        return $stockPositions;
    }
    
    private function formatNumber($number): string
    {
        if (!is_numeric($number)) {
            return $number;
        }
        
        return floor($number) == $number ? number_format($number, 0) : $number;
    }
} 