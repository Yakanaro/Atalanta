<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoneType extends Model
{
    use HasFactory;

    /**
     * Атрибуты, которые можно массово назначать.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'is_active',
    ];

    /**
     * Атрибуты, которые должны быть приведены к определенным типам.
     *
     * @var array
     */
    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Получить все активные виды камня
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getActive()
    {
        return self::where('is_active', true)
            ->orderBy('name')
            ->get();
    }

    /**
     * Получить все виды камня в виде массива для использования в селекте
     *
     * @return array
     */
    public static function getForSelect()
    {
        $types = self::getActive();
        $result = [];

        foreach ($types as $type) {
            $result[$type->id] = $type->name;
        }

        return $result;
    }
}
