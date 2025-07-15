<?php

namespace App\Http\Requests\StockPosition;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Pallet;

class StoreStockPositionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'product_type_id' => 'required|exists:product_types,id',
            'length' => 'required',
            'width' => 'required',
            'thickness' => 'required',
            'quantity' => 'required',
            'polish_type_id' => 'nullable|exists:polish_types,id',
            'stone_type_id' => 'nullable|exists:stone_types,id',
            'pallet_id' => 'nullable|exists:pallets,id',
            'pallet_number' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];
    }

    public function getProductTypeId(): int
    {
        return (int)$this->validated('product_type_id');
    }

    public function getLength(): float
    {
        return (float)$this->validated('length');
    }

    public function getWidth(): float
    {
        return (float)$this->validated('width');
    }

    public function getThickness(): float
    {
        return (float)$this->validated('thickness');
    }

    public function getQuantity(): int
    {
        return (int)$this->validated('quantity');
    }

    public function getPolishTypeId(): ?int
    {
        return $this->validated('polish_type_id') ? (int)$this->validated('polish_type_id') : null;
    }

    public function getStoneTypeId(): ?int
    {
        return $this->validated('stone_type_id') ? (int)$this->validated('stone_type_id') : null;
    }

    /**
     * Получить ID поддона или создать новый поддон по номеру.
     */
    public function getPalletId(): ?int
    {
        // Если передан pallet_id, используем его
        if ($this->validated('pallet_id')) {
            return (int)$this->validated('pallet_id');
        }

        // Если передан pallet_number, находим или создаем поддон
        if ($this->validated('pallet_number')) {
            $pallet = Pallet::findOrCreateByNumber($this->validated('pallet_number'));
            return $pallet->id;
        }

        return null;
    }

    /**
     * Получить номер поддона для отображения.
     */
    public function getPalletNumber(): ?string
    {
        return $this->validated('pallet_number');
    }

    public function hasImage(): bool
    {
        return $this->hasFile('image');
    }

    public function getImage()
    {
        return $this->file('image');
    }

    public function toArray(): array
    {
        $data = [
            'product_type_id' => $this->getProductTypeId(),
            'length' => $this->getLength(),
            'width' => $this->getWidth(),
            'thickness' => $this->getThickness(),
            'quantity' => $this->getQuantity(),
            'polish_type_id' => $this->getPolishTypeId(),
            'stone_type_id' => $this->getStoneTypeId(),
            'pallet_id' => $this->getPalletId(),
            'weight' => $this->calculateWeight(),
        ];

        return $data;
    }

    private function calculateWeight(): float
    {
        return round($this->getLength() * $this->getWidth() * $this->getThickness() * $this->getQuantity() * 0.0032, 2);
    }
}
