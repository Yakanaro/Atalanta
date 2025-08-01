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

        if ($request->filled('filter_length')) {
            $query->where('length', $request->filter_length);
        }

        if ($request->filled('filter_width')) {
            $query->where('width', $request->filter_width);
        }

        if ($request->filled('filter_thickness')) {
            $query->where('thickness', $request->filter_thickness);
        }

        return $query->with(['polishType', 'productType'])->latest()->get();
    }
}
