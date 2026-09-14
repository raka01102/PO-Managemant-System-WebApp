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

    /**
     * Groups the exact `file_type` labels written by PurchaseOrderService
     * under the category keys used by the Blade views
     * (`purchase-orders/show.blade.php`'s <x-attachment-section> calls).
     *
     * Matching against this list is exact (case-insensitive), not a
     * substring match, so that e.g. 'po' never accidentally matches
     * 'Bukti PO Selesai'.
     */
    private const ATTACHMENT_TYPE_GROUPS = [
        'po' => [
            'PO Draft',
        ],
        'pengiriman' => [
            'Bukti Pengiriman',
            'Bukti Barang Sampai',
            'Bukti PO Selesai',
        ],
        'pembayaran' => [
            'Pembayaran',
        ],
    ];

    public function getRemainingAmountAttribute()
    {
        return max(0, $this->total_amount - $this->payments()->sum('amount'));
    }

    public static function generatePONumber()
    {
        $lastPurchaseOrder = self::latest('id')->first();

        $lastNumber = 0;

        if ($lastPurchaseOrder) {
            $parts = explode('-', $lastPurchaseOrder->po_number);

            $lastNumber = (int) end($parts);
        }

        $nextNumber = $lastNumber + 1;

        return 'PO-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }

    public function getAttachmentsByType(String $type)
    {
        $labels = self::ATTACHMENT_TYPE_GROUPS[$type] ?? [];

        if (empty($labels)) {
            return $this->attachments->filter(fn() => false)->values();
        }

        $normalizedLabels = array_map('strtolower', $labels);

        return $this->attachments
            ->filter(fn($attachment) => in_array(strtolower($attachment->file_type ?? ''), $normalizedLabels, true))
            ->values();
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
