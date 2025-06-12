<?php

namespace App\Http\Requests\PolishType;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePolishTypeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|unique:polish_types,name,' . $this->polishType->id,
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ];
    }

    public function getName(): string
    {
        return $this->validated('name');
    }

    public function getSortOrder(): int
    {
        return $this->validated('sort_order') ?? 0;
    }

    public function isActive(): bool
    {
        return $this->has('is_active');
    }
} 