<?php

namespace App\Http\Controllers;

use App\Exports\PositionStatisticsExport;
use App\Services\PositionStatisticsService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class StatisticsController extends Controller
{
    public function __construct(
        private PositionStatisticsService $statisticsService
    ) {
    }

    public function index(Request $request): View
    {
        $filters = $this->statisticsService->normalizeFilters($request->all());

        $statistics = $this->statisticsService
            ->buildGroupedQuery($filters)
            ->orderByDesc('total_quantity')
            ->paginate(20)
            ->withQueryString();

        $totals = $this->statisticsService->getTotals($filters);

        $statistics->setCollection(
            $this->statisticsService->appendSharePercent(
                $statistics->getCollection(),
                (float) $totals['total_quantity']
            )
        );

        $filterData = $this->statisticsService->getFilterData();

        return view('statistics.index', [
            'statistics' => $statistics,
            'totals' => $totals,
            'filters' => $filters,
            'productTypes' => $filterData['productTypes'],
            'polishTypes' => $filterData['polishTypes'],
            'stoneTypes' => $filterData['stoneTypes'],
        ]);
    }

    public function export(Request $request): BinaryFileResponse
    {
        $filters = $this->statisticsService->normalizeFilters($request->all());
        $totals = $this->statisticsService->getTotals($filters);

        $rows = $this->statisticsService
            ->buildGroupedQuery($filters)
            ->orderByDesc('total_quantity')
            ->get();

        $rows = $this->statisticsService->appendSharePercent($rows, (float) $totals['total_quantity']);

        return Excel::download(new PositionStatisticsExport($rows), 'position_statistics.xlsx');
    }
}

