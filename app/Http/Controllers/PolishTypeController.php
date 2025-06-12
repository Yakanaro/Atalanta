<?php

namespace App\Http\Controllers;

use App\Http\Requests\PolishType\StorePolishTypeRequest;
use App\Http\Requests\PolishType\UpdatePolishTypeRequest;
use App\Models\PolishType;
use App\Repositories\PolishType\Dto\StorePolishTypeDto;
use App\Repositories\PolishType\PolishTypeRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PolishTypeController extends Controller
{
    private PolishTypeRepository $repository;

    public function __construct(PolishTypeRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index(): View
    {
        $polishTypes = $this->repository->getAll();
        
        return view('polish-types.index', compact('polishTypes'));
    }

    public function create(): View
    {
        return view('polish-types.create');
    }

    public function store(StorePolishTypeRequest $request): RedirectResponse
    {
        $this->repository->create(
            (new StorePolishTypeDto())
                ->setName($request->getName())
                ->setSortOrder($request->getSortOrder())
                ->setIsActive($request->isActive())
        );
        
        return redirect()->route('polish-types.index')
            ->with('success', 'Вид полировки успешно создан.');
    }

    public function edit(PolishType $polishType): View
    {
        return view('polish-types.edit', compact('polishType'));
    }

    public function update(UpdatePolishTypeRequest $request, PolishType $polishType): RedirectResponse
    {
        $this->repository->update(
            $polishType,
            (new StorePolishTypeDto())
                ->setName($request->getName())
                ->setSortOrder($request->getSortOrder())
                ->setIsActive($request->isActive())
        );
        
        return redirect()->route('polish-types.index')
            ->with('success', 'Вид полировки успешно обновлен.');
    }

    public function destroy(PolishType $polishType): RedirectResponse
    {
        $usageCount = $this->repository->checkUsage($polishType);
        
        if ($usageCount > 0) {
            return back()->with('error', "Невозможно удалить вид полировки, так как он используется в {$usageCount} позициях.");
        }
        
        $this->repository->delete($polishType);
        
        return redirect()->route('polish-types.index')
            ->with('success', 'Вид полировки успешно удален.');
    }
}
