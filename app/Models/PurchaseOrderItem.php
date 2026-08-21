<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseOrderItem extends Model
{
    use softDeletes;
    protected $table = 'po_items';

    protected $fillable = [
        'purchase_order_id',
        'product_id',
        'quantity',
        'price_at_time',
        'subtotal',
    ];

    public function purchaseOrder() {
        return $this->belongsTo(PurchaseOrder::class);
    }

    public function product() {
        return $this->belongsTo(Product::class);
    }
}
