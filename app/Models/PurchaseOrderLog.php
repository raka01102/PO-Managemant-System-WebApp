<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseOrderLog extends Model
{
    protected $table = 'po_logs';

    protected $fillable = [
        'purchase_order_id',
        'type',
        'old_value',
        'new_value',
        'note',
        'attachment',
        'updated_by',
    ];

    public function purchaseOrder() {
        return $this->belongsTo(PurchaseOrder::class);
    }

    public function user() {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
