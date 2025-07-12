<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

/**
 * 
 *
 * @property int $id
 * @property string $number
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\StockPosition[] $stockPositions
 * @property-read int|null $stock_positions_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pallet newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pallet newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pallet query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pallet whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pallet whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pallet whereNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pallet whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Pallet extends Model
{
    protected $table = 'pallets';

    protected $fillable = [
        'number',
    ];

    /**
     * Получить все позиции на складе, связанные с данным поддоном.
     */
    public function stockPositions(): HasMany
    {
        return $this->hasMany(StockPosition::class);
    }

    /**
     * Получить номер поддона.
     */
    public function getNumber(): string
    {
        return $this->number;
    }

    /**
     * Получить количество позиций в поддоне.
     */
    public function getPositionsCount(): int
    {
        return $this->stockPositions()->count();
    }

    /**
     * Получить общий вес поддона.
     */
    public function getTotalWeight(): float
    {
        return $this->stockPositions()->sum('weight') ?? 0;
    }

    /**
     * Получить общее количество штук в поддоне.
     */
    public function getTotalQuantity(): int
    {
        return $this->stockPositions()->sum('quantity') ?? 0;
    }

    /**
     * Скоуп для поиска поддона по номеру.
     */
    public function scopeByNumber(Builder $query, string $number): Builder
    {
        return $query->where('number', $number);
    }

    /**
     * Получить поддоны для выпадающего списка.
     */
    public static function getForSelect(): array
    {
        return self::orderBy('number')->pluck('number', 'id')->toArray();
    }

    /**
     * Найти или создать поддон по номеру.
     */
    public static function findOrCreateByNumber(string $number): self
    {
        return self::firstOrCreate(['number' => $number]);
    }

    /**
     * Проверить, существует ли поддон с таким номером.
     */
    public static function existsByNumber(string $number): bool
    {
        return self::where('number', $number)->exists();
    }
}
