<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\GenerateCode;

class Customer extends Model
{
    use SoftDeletes;
    use GenerateCode;
    protected $table = 'customers';

    protected $fillable = [
        'code',
        'name',
        'phone',
        'address',
    ];

    public static function generateCodeCustomer()
    {
        return static::generateCode('CUS', 'code');
    }

    public function purchaseOrders()
    {
        return $this->hasMany(PurchaseOrder::class);
    }
}
