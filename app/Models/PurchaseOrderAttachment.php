<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseOrderAttachment extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'po_attachments';

    protected $fillable = [
        'purchase_order_id',
        'delivery_order_id',
        'purchase_order_payment_id',
        'file_path',
        'file_type',
    ];

    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class);
    }

    public function deliveries()
    {
        return $this->belongsTo(DeliveryOrder::class);
    }

    public function payments()
    {
        return $this->belongsTo(PurchaseOrderPayment::class);
    }
}
