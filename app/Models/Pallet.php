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
 * @property string $status
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
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pallet whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pallet whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Pallet extends Model
{
    // Константы статусов
    const STATUS_IN_WAREHOUSE = 'На складе';
    const STATUS_SHIPPING = 'Отгрузка';
    const STATUS_SHIPPED = 'Отправлен';

    protected $table = 'pallets';

    protected $fillable = [
        'number',
        'order_number',
        'status',
        'qr_code_path',
        'image_path',
    ];

    /**
     * Получить все доступные статусы.
     */
    public static function getAvailableStatuses(): array
    {
        return [
            self::STATUS_IN_WAREHOUSE => 'На складе',
            self::STATUS_SHIPPING => 'Отгрузка',
            self::STATUS_SHIPPED => 'Отправлен',
        ];
    }

    /**
     * Получить статус с CSS классом для отображения.
     */
    public function getStatusWithClass(): array
    {
        $statusClasses = [
            self::STATUS_IN_WAREHOUSE => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
            self::STATUS_SHIPPING => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
            self::STATUS_SHIPPED => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300',
        ];

        return [
            'status' => $this->status,
            'class' => $statusClasses[$this->status] ?? 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300'
        ];
    }

    /**
     * Проверить, находится ли поддон на складе.
     */
    public function isInWarehouse(): bool
    {
        return $this->status === self::STATUS_IN_WAREHOUSE;
    }

    /**
     * Проверить, находится ли поддон в процессе отгрузки.
     */
    public function isShipping(): bool
    {
        return $this->status === self::STATUS_SHIPPING;
    }

    /**
     * Проверить, отправлен ли поддон.
     */
    public function isShipped(): bool
    {
        return $this->status === self::STATUS_SHIPPED;
    }

    /**
     * Изменить статус поддона.
     */
    public function changeStatus(string $status): bool
    {
        if (!in_array($status, array_keys(self::getAvailableStatuses()))) {
            return false;
        }

        $this->status = $status;
        return $this->save();
    }

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
     * Получить статус поддона.
     */
    public function getStatus(): string
    {
        return $this->status;
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
     * Скоуп для фильтрации по статусу.
     */
    public function scopeByStatus(Builder $query, string $status): Builder
    {
        return $query->where('status', $status);
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
        return self::firstOrCreate(
            ['number' => $number],
            ['status' => self::STATUS_IN_WAREHOUSE]
        );
    }

    /**
     * Проверить, существует ли поддон с таким номером.
     */
    public static function existsByNumber(string $number): bool
    {
        return self::where('number', $number)->exists();
    }

    /**
     * Сгенерировать следующий номер поддона.
     */
    public static function generateNextNumber(): string
    {
        $lastPallet = self::orderBy('id', 'desc')->first();

        if (!$lastPallet) {
            return 'P-001';
        }

        // Извлекаем номер из последнего поддона
        $lastNumber = $lastPallet->number;

        // Проверяем, соответствует ли номер формату P-XXX
        if (preg_match('/^P-(\d+)$/', $lastNumber, $matches)) {
            $nextNumber = intval($matches[1]) + 1;
            return 'P-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
        }

        // Если формат не соответствует, ищем максимальный номер в базе
        $maxNumber = self::where('number', 'LIKE', 'P-%')
            ->get()
            ->map(function ($pallet) {
                if (preg_match('/^P-(\d+)$/', $pallet->number, $matches)) {
                    return intval($matches[1]);
                }
                return 0;
            })
            ->max();

        $nextNumber = $maxNumber ? $maxNumber + 1 : 1;
        return 'P-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Получить путь к QR-коду поддона.
     */
    public function getQrCodePath(): ?string
    {
        return $this->qr_code_path;
    }

    /**
     * Получить URL QR-кода поддона.
     */
    public function getQrCodeUrl(): ?string
    {
        return $this->qr_code_path ? asset('storage/' . $this->qr_code_path) : null;
    }

    /**
     * Получить путь к изображению поддона.
     */
    public function getImagePath(): ?string
    {
        return $this->image_path;
    }

    /**
     * Получить URL изображения поддона.
     */
    public function getImageUrl(): ?string
    {
        return $this->image_path ? asset($this->image_path) : null;
    }
}
