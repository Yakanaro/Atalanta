<?php

namespace App\Repositories\PolishType;

use App\Models\PolishType;
use App\Repositories\PolishType\Dto\StorePolishTypeDto;
use Illuminate\Database\Eloquent\Collection;

class PolishTypeRepository
{
    public function getAll(): Collection
    {
        return PolishType::orderBy('sort_order')->orderBy('name')->get();
    }

    public function findById(int $id): ?PolishType
    {
        return PolishType::find($id);
    }

    public function create(StorePolishTypeDto $dto): PolishType
    {
        return PolishType::create($dto->toArray());
    }

    public function update(PolishType $polishType, StorePolishTypeDto $dto): bool
    {
        return $polishType->update($dto->toArray());
    }

    public function delete(PolishType $polishType): bool
    {
        return $polishType->delete();
    }

    public function checkUsage(PolishType $polishType): int
    {
        return \App\Models\StockPosition::where('polish_type_id', $polishType->id)->count();
    }
} 