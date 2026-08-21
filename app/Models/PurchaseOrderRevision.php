<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseOrderRevision extends Model
{
    protected $table = 'purchase_order_revisions';

    protected $fillable = [
        'purchase_order_id',
        'revision_number',
        'revision_date',
        'note',
    ];

    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class);
    }
}
