<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 *
 *
 * @property int $id
 * @property int|null $pallet_id
 * @property string $type
 * @property string $length
 * @property string $width
 * @property string $thickness
 * @property int $quantity
 * @property int|null $polish_type_id
 * @property int|null $product_type_id
 * @property string|null $qr_code_path
 * @property string|null $image_path
 * @property string|null $weight
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Pallet|null $pallet
 * @property-read \App\Models\PolishType|null $polishType
 * @property-read \App\Models\ProductType|null $productType
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockPosition newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockPosition newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockPosition query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockPosition whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockPosition whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockPosition whereLength($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockPosition wherePalletId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockPosition wherePolishTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockPosition whereProductTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockPosition whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockPosition whereThickness($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockPosition whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockPosition whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockPosition whereWidth($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockPosition whereQrCodePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockPosition whereImagePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockPosition whereWeight($value)
 * @mixin \Eloquent
 */
class StockPosition extends Model
{
    protected $table = 'stock_positions';
    protected $fillable = [
        'pallet_id',
        'length',
        'width',
        'thickness',
        'quantity',
        'polish_type_id',
        'product_type_id',
        'qr_code_path',
        'image_path',
        'weight',
    ];

    /**
     * Получить поддон, связанный с позицией на складе.
     */
    public function pallet(): BelongsTo
    {
        return $this->belongsTo(Pallet::class);
    }

    /**
     * Получить тип полировки, связанный с позицией на складе.
     */
    public function polishType(): BelongsTo
    {
        return $this->belongsTo(PolishType::class);
    }

    /**
     * Получить вид продукции, связанный с позицией на складе.
     */
    public function productType(): BelongsTo
    {
        return $this->belongsTo(ProductType::class);
    }

    public function getType(): string
    {
        return $this->productType ? $this->productType->name : '';
    }

    public function getLength(): float
    {
        return $this->length;
    }

    public function getWidth(): float
    {
        return $this->width;
    }

    public function getThickness(): float
    {
        return $this->thickness;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    /**
     * Получить название типа полировки.
     */
    public function getPolishType(): ?string
    {
        return $this->polishType?->name;
    }

    public function getProductType(): ?string
    {
        return $this->productType?->name;
    }

    public function getQrCodePath(): ?string
    {
        return $this->qr_code_path;
    }

    public function getImagePath(): ?string
    {
        return $this->image_path;
    }

    /**
     * Получить номер поддона.
     */
    public function getPalletNumber(): ?string
    {
        return $this->pallet?->number;
    }

    /**
     * Получить ID поддона.
     */
    public function getPalletId(): ?int
    {
        return $this->pallet_id;
    }

    public function getWeight(): ?float
    {
        return $this->weight;
    }

    /**
     * Установить поддон по номеру (создать новый если не существует).
     */
    public function setPalletByNumber(string $palletNumber): void
    {
        $pallet = Pallet::findOrCreateByNumber($palletNumber);
        $this->pallet_id = $pallet->id;
    }
}
