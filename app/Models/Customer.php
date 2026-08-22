<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use SoftDeletes;
    protected $table = 'customers';

    protected $fillable = [
        'code',
        'name',
        'phone',
        'address',
    ];

    public static function generateCodeCustomer()
    {
        $lastCustomer = self::latest('id')->first();

        $lastCode = 0;

        if ($lastCustomer) {
            $parts = explode('-', $lastCustomer->code);

            $lastCode = (int) end($parts);
        }

        $nextCode = $lastCode + 1;

        return 'CUS-' . str_pad($nextCode, 4, '0', STR_PAD_LEFT);
    }

    public function purchaseOrders()
    {
        return $this->hasMany(PurchaseOrder::class);
    }
}
