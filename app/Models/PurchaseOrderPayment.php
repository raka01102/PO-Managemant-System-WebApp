<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseOrderPayment extends Model
{
    protected $table = 'purchase_order_payments';

    protected $fillable = [
        'purchase_order_id',
        'payment_date',
        'payment_method',
        'amount',
        'note',
    ];

    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class);
    }

    public function attachment()
    {
        return $this->hasmany(PurchaseOrderAttachment::class);
    }
}
