<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\GenerateCode;

class Product extends Model
{
    use SoftDeletes;
    use GenerateCode;

    protected $table = 'products';

    protected $fillable = [
        'code',
        'name',
        'price',
        'unit',
    ];

    public static function generateCodeProduct()
    {
        return static::generateCode('PRD', 'code');
    }

    public function purchaseOrderItems()
    {
        return $this->hasMany(PurchaseOrderItem::class);
    }
}
