<?php

namespace App\Services;

use App\Models\PolishType;
use App\Models\ProductType;
use App\Models\StockPosition;
use App\Models\StoneType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class PositionStatisticsService
{
    public function normalizeFilters(array $filters): array
    {
        return [
            'filter_product_type_id' => $this->toInt($filters['filter_product_type_id'] ?? null),
            'filter_polish_type_id' => $this->toInt($filters['filter_polish_type_id'] ?? null),
            'filter_stone_type_id' => $this->toInt($filters['filter_stone_type_id'] ?? null),
            'filter_min_length' => $this->toFloat($filters['filter_min_length'] ?? null),
            'filter_max_length' => $this->toFloat($filters['filter_max_length'] ?? null),
            'filter_min_width' => $this->toFloat($filters['filter_min_width'] ?? null),
            'filter_max_width' => $this->toFloat($filters['filter_max_width'] ?? null),
            'filter_min_thickness' => $this->toFloat($filters['filter_min_thickness'] ?? null),
            'filter_max_thickness' => $this->toFloat($filters['filter_max_thickness'] ?? null),
        ];
    }

    public function buildGroupedQuery(array $filters): Builder
    {
        $query = StockPosition::query()
            ->leftJoin('product_types', 'stock_positions.product_type_id', '=', 'product_types.id')
            ->leftJoin('polish_types', 'stock_positions.polish_type_id', '=', 'polish_types.id')
            ->leftJoin('stone_types', 'stock_positions.stone_type_id', '=', 'stone_types.id')
            ->selectRaw('stock_positions.product_type_id')
            ->selectRaw('stock_positions.polish_type_id')
            ->selectRaw('stock_positions.stone_type_id')
            ->selectRaw('stock_positions.length')
            ->selectRaw('stock_positions.width')
            ->selectRaw('stock_positions.thickness')
            ->selectRaw("COALESCE(product_types.name, 'Не указано') as product_type_name")
            ->selectRaw("COALESCE(polish_types.name, 'Не указано') as polish_type_name")
            ->selectRaw("COALESCE(stone_types.name, 'Не указано') as stone_type_name")
            ->selectRaw('SUM(stock_positions.quantity) as total_quantity')
            ->selectRaw('SUM(COALESCE(stock_positions.weight, 0)) as total_weight')
            ->selectRaw('COUNT(stock_positions.id) as positions_count');

        $this->applyFilters($query, $filters);

        return $query->groupBy([
            'stock_positions.product_type_id',
            'stock_positions.polish_type_id',
            'stock_positions.stone_type_id',
            'stock_positions.length',
            'stock_positions.width',
            'stock_positions.thickness',
            'product_types.name',
            'polish_types.name',
            'stone_types.name',
        ]);
    }

    public function getTotals(array $filters): array
    {
        $baseQuery = StockPosition::query();
        $this->applyFilters($baseQuery, $filters);

        $totalQuantity = (float) $baseQuery->sum('quantity');
        $totalWeight = (float) $baseQuery->sum('weight');
        $groupsCount = DB::query()
            ->fromSub($this->buildGroupedQuery($filters)->toBase(), 'position_statistics')
            ->count();

        return [
            'total_quantity' => $totalQuantity,
            'total_weight' => $totalWeight,
            'groups_count' => $groupsCount,
        ];
    }

    public function appendSharePercent(Collection $rows, float $totalQuantity): Collection
    {
        return $rows->map(function ($row) use ($totalQuantity) {
            $row->share_percent = $totalQuantity > 0
                ? round(((float) $row->total_quantity / $totalQuantity) * 100, 2)
                : 0.0;

            return $row;
        });
    }

    public function getFilterData(): array
    {
        return [
            'productTypes' => ProductType::getActive(),
            'polishTypes' => PolishType::getActive(),
            'stoneTypes' => StoneType::getActive(),
        ];
    }

    private function applyFilters(Builder $query, array $filters): void
    {
        if (!is_null($filters['filter_product_type_id'])) {
            $query->where('stock_positions.product_type_id', $filters['filter_product_type_id']);
        }

        if (!is_null($filters['filter_polish_type_id'])) {
            $query->where('stock_positions.polish_type_id', $filters['filter_polish_type_id']);
        }

        if (!is_null($filters['filter_stone_type_id'])) {
            $query->where('stock_positions.stone_type_id', $filters['filter_stone_type_id']);
        }

        if (!is_null($filters['filter_min_length'])) {
            $query->where('stock_positions.length', '>=', $filters['filter_min_length']);
        }

        if (!is_null($filters['filter_max_length'])) {
            $query->where('stock_positions.length', '<=', $filters['filter_max_length']);
        }

        if (!is_null($filters['filter_min_width'])) {
            $query->where('stock_positions.width', '>=', $filters['filter_min_width']);
        }

        if (!is_null($filters['filter_max_width'])) {
            $query->where('stock_positions.width', '<=', $filters['filter_max_width']);
        }

        if (!is_null($filters['filter_min_thickness'])) {
            $query->where('stock_positions.thickness', '>=', $filters['filter_min_thickness']);
        }

        if (!is_null($filters['filter_max_thickness'])) {
            $query->where('stock_positions.thickness', '<=', $filters['filter_max_thickness']);
        }
    }

    private function toInt(mixed $value): ?int
    {
        if ($value === null || $value === '') {
            return null;
        }

        return (int) $value;
    }

    private function toFloat(mixed $value): ?float
    {
        if ($value === null || $value === '') {
            return null;
        }

        return (float) $value;
    }
}

