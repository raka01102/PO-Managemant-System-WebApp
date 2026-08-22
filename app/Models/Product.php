<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;
    protected $table = 'products';

    protected $fillable = [
        'code',
        'name',
        'price',
        'unit',
    ];

    public static function generateCodeProduct()
    {
        $lastProduct = self::latest('id')->first();

        $lastCode = 0;

        if ($lastProduct) {
            $parts = explode('-', $lastProduct->code);

            $lastCode = (int) end($parts);
        }

        $nextCode = $lastCode + 1;

        return 'PRD-' . str_pad($nextCode, 4, '0', STR_PAD_LEFT);
    }

    public function purchaseOrderItems()
    {
        return $this->hasMany(PurchaseOrderItem::class);
    }
}
