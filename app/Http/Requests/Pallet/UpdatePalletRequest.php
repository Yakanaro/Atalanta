<?php

namespace App\Http\Requests\Pallet;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePalletRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'number' => 'required|string|max:255|unique:pallets,number,' . $this->route('pallet')->id,
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,heic,heif|max:10240',
        ];
    }

    public function messages(): array
    {
        return [
            'number.required' => 'Номер поддона обязателен для заполнения.',
            'number.unique' => 'Поддон с таким номером уже существует.',
            'image.image' => 'Файл должен быть изображением.',
            'image.mimes' => 'Изображение должно быть в одном из следующих форматов: jpeg, png, jpg, gif, svg, heic, heif.',
            'image.max' => 'Размер изображения не должен превышать 10MB.',
            'image.uploaded' => 'Ошибка загрузки изображения. Проверьте размер файла и попробуйте еще раз.',
        ];
    }

    /**
     * Получить номер поддона.
     */
    public function getNumber(): string
    {
        return $this->validated('number');
    }

    /**
     * Проверить, есть ли изображение.
     */
    public function hasImage(): bool
    {
        return $this->hasFile('image');
    }

    /**
     * Получить изображение.
     */
    public function getImage()
    {
        return $this->file('image');
    }
}
