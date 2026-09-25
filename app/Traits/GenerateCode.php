<?php

namespace App\Traits;

use Illuminate\Support\Facades\Cache;

/**
 * @mixin \Illuminate\Database\Eloquent\Model
 * @method static \Illuminate\Database\Eloquent\Builder|static query()
 * @method static \Illuminate\Database\Eloquent\Builder|static latest(?string $column = null)
 */
trait GenerateCode
{
    public static function generateCode(String $prefix, String $column = 'code', int $length = 4)
    {
        $lockKey = 'generate_code_lock_' . static::class;

        return Cache::lock($lockKey, 5)->block(3, function () use ($prefix, $column, $length) {
            $lastRecord = static::query()->latest('id')->first();

            $lastNumber = 0;

            if ($lastRecord && !empty($lastRecord->{$column})) {
                $parts = explode('-', $lastRecord->{$column});
                $lastNumber = (int) end($parts);
            }

            $nextNumber = $lastNumber + 1;

            return $prefix . '-' . str_pad($nextNumber, $length, '0', STR_PAD_LEFT);
        });
    }
}
