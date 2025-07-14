<?php

namespace App\Http\Requests\Pallet;

use Illuminate\Foundation\Http\FormRequest;

class StorePalletRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'positions' => 'nullable|array',
            'positions.*.product_type_id' => 'required|exists:product_types,id',
            'positions.*.length' => 'required|numeric|min:0',
            'positions.*.width' => 'required|numeric|min:0',
            'positions.*.thickness' => 'required|numeric|min:0',
            'positions.*.quantity' => 'required|integer|min:1',
            'positions.*.polish_type_id' => 'nullable|exists:polish_types,id',
        ];
    }

    public function messages(): array
    {
        return [
            'positions.*.product_type_id.required' => 'Необходимо выбрать вид продукции.',
            'positions.*.product_type_id.exists' => 'Выбранный вид продукции не существует.',
            'positions.*.length.required' => 'Длина обязательна для заполнения.',
            'positions.*.length.numeric' => 'Длина должна быть числом.',
            'positions.*.length.min' => 'Длина не может быть отрицательной.',
            'positions.*.width.required' => 'Ширина обязательна для заполнения.',
            'positions.*.width.numeric' => 'Ширина должна быть числом.',
            'positions.*.width.min' => 'Ширина не может быть отрицательной.',
            'positions.*.thickness.required' => 'Толщина обязательна для заполнения.',
            'positions.*.thickness.numeric' => 'Толщина должна быть числом.',
            'positions.*.thickness.min' => 'Толщина не может быть отрицательной.',
            'positions.*.quantity.required' => 'Количество обязательно для заполнения.',
            'positions.*.quantity.integer' => 'Количество должно быть целым числом.',
            'positions.*.quantity.min' => 'Количество должно быть больше 0.',
            'positions.*.polish_type_id.exists' => 'Выбранный вид полировки не существует.',
        ];
    }

    public function getPositions(): array
    {
        $positions = $this->validated('positions') ?? [];

        // Добавляем расчет веса для каждой позиции
        foreach ($positions as &$position) {
            $position['weight'] = $this->calculateWeight(
                $position['length'],
                $position['width'],
                $position['thickness']
            );
        }

        return $positions;
    }

    private function calculateWeight(float $length, float $width, float $thickness): float
    {
        return round($length * $width * $thickness * 0.0032, 2);
    }
}
