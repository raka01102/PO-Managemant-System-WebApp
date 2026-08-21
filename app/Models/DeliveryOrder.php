<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeliveryOrder extends Model
{
    protected $table = 'delivery_orders';

    protected $fillable = [
        'purchase_order_id',
        'delivery_number',
        'delivery_date',
        'status',
        'note',
    ];

    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class);
    }

    public function attachments()
    {
        return $this->hasMany(PurchaseOrderAttachment::class);
    }
}
