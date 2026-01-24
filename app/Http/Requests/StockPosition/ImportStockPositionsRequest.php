<?php

namespace App\Http\Requests\StockPosition;

use Illuminate\Foundation\Http\FormRequest;

class ImportStockPositionsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'file' => 'required|file|mimes:xlsx,xls|max:10240',
        ];
    }

    public function messages(): array
    {
        return [
            'file.required' => 'Файл обязателен для загрузки.',
            'file.file' => 'Загруженный файл не является валидным файлом.',
            'file.mimes' => 'Файл должен быть в формате Excel (.xlsx или .xls).',
            'file.max' => 'Размер файла не должен превышать 10MB.',
        ];
    }
}
