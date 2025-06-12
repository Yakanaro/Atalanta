<?php

namespace App\UseCases\StockPosition;

use App\Models\StockPosition;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class FilterStockPositionsUseCase
{
    public function execute(Request $request): Collection
    {
        $query = StockPosition::query();
        
        if ($request->filled('filter_id')) {
            $query->where('id', $request->filter_id);
        }
        
        if ($request->filled('filter_type')) {
            $query->where('product_type_id', $request->filter_type);
        }
        
        if ($request->filled('filter_polish_type_id')) {
            $query->where('polish_type_id', $request->filter_polish_type_id);
        }
        
        if ($request->filled('filter_pallet_number')) {
            $query->where('pallet_number', 'like', '%' . $request->filter_pallet_number . '%');
        }
        
        return $query->with(['polishType', 'productType'])->latest()->get();
    }
} 