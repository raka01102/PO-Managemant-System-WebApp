<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Models\Concerns\LogsActivity as ConcernsLogsActivity;
use Spatie\Activitylog\Support\LogOptions as SupportLogOptions;

class PurchaseOrder extends Model
{
    use HasFactory;
    use SoftDeletes;
    use ConcernsLogsActivity;

    protected $table = 'purchase_orders';

    protected $fillable = [
        'po_number',
        'customer_id',
        'order_date',
        'total_amount',
        'delivery_status',
        'payment_status',
    ];

    protected $casts = [
        'order_date' => 'date',
    ];

    public function getRemainingAmountAttribute()
    {
        return max(0, $this->total_amount - $this->payments()->sum('amount'));
    }

    public function getActivitylogOptions(): SupportLogOptions
    {
        return SupportLogOptions::defaults()
            ->logOnly([
                'po_number',
                'delivery_status',
                'payment_status',
                'customer_id',
            ])
            ->logOnlyDirty();
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function items()
    {
        return $this->hasMany(PurchaseOrderItem::class);
    }

    public function attachments()
    {
        return $this->hasMany(PurchaseOrderAttachment::class);
    }

    public function revisions()
    {
        return $this->hasMany(PurchaseOrderRevision::class);
    }

    public function deliveries()
    {
        return $this->hasmany(DeliveryOrder::class);
    }

    public function payments()
    {
        return $this->hasMany(PurchaseOrderPayment::class);
    }

    public function logs()
    {
        return $this->hasMany(PurchaseOrderLog::class);
    }
}
