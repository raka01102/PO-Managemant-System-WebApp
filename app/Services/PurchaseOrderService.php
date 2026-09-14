<?php

namespace App\Services;

use App\Models\PurchaseOrder;
use App\Models\User;
use App\Models\Customer;
use App\Models\Product;
use App\Models\PurchaseOrderItem;
use App\Models\PurchaseOrderAttachment;
use App\Models\PurchaseOrderLog;
use App\Models\PurchaseOrderPayment;
use App\Models\PurchaseOrderRevision;
use App\Models\PurchaseOrderRevisionItem;
use App\Models\PurchaseOrderRevisionAttachment;
use App\Models\PurchaseOrderRevisionPayment;
use App\Models\DeliveryOrder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\UploadedFile;

class PurchaseOrderService
{
    public function __construct(private OCRService $ocr)
    {
        // Untuk sekarang bisa generate PO Number saat create karena user hanya ada 1, jika ingin public PO Number lebih baik saat di store karena bisa didouble jika ada user yang membuka halaman yang sama secara bersamaan dan membuat PO Number yang sama

        // Jika nanti PO Number mau diubah atau user punya pilihan untuk mengubah PO Number maka nanti dibuat fungsi baru, ada yang generateSuggestNumber dan Number yang dibuat user sendiri
    }


    public function prepareScanData(UploadedFile $document)
    {
        $tempPath = $document->store('temp_scans', 'public');

        $extractedData = $this->emptyExtractedData();

        if (config('ocr.enabled')) {
            try {
                $extractedData = $this->ocr->scan($tempPath);
            } catch (\Throwable $e) {
                report($e);
                $extractedData = $this->emptyExtractedData();
            }
        }

        return [
            'file_path' => $tempPath,
            'extracted' => $extractedData,
        ];
    }

    public function store(array $data)
    {
        return DB::transaction(function () use ($data) {
            $customer = $this->createCustomer($data);

            $total = $this->calculateTotal($data['items']);

            $purchaseOrder = $this->createPurchaseOrder($data, $customer->id, $total);

            $this->storeItems($purchaseOrder, $data['items']);

            $newPath = $this->moveAttachmentToShipping($data['file_path']);

            $this->storeAttachments($purchaseOrder, $newPath);

            $this->storeLogs($purchaseOrder, $newPath);

            return $purchaseOrder;
        });
    }

    public function update(PurchaseOrder $purchaseOrder, array $data)
    {
        return DB::transaction(function () use ($purchaseOrder, $data) {
            $customer = $this->createCustomer($data);

            $total = $this->calculateTotal($data['items']);

            $purchaseOrder->update([
                'po_number'    => $data['po_number'],
                'customer_id'  => $customer->id,
                'order_date'   => $data['order_date'],
                'total_amount' => $total,
            ]);

            $this->syncItems($purchaseOrder, $data['items']);

            return $purchaseOrder->fresh();
        });
    }

    public function delete(PurchaseOrder $purchaseOrder)
    {
        $purchaseOrder->delete();
    }

    // UPDATE / SYNC METHODS
    private function syncItems(PurchaseOrder $purchaseOrder, array $items)
    {
        $keptItemIds = [];

        foreach ($items as $item) {
            $product = $this->createProduct($item);

            $subtotal = $item['quantity'] * $item['price_at_time'];

            $existingItem = null;

            if (!empty($item['id'])) {
                $existingItem = PurchaseOrderItem::where('purchase_order_id', $purchaseOrder->id)
                    ->where('id', $item['id'])
                    ->first();
            }

            if ($existingItem) {
                $existingItem->update([
                    'product_id'    => $product->id,
                    'quantity'      => $item['quantity'],
                    'price_at_time' => $item['price_at_time'],
                    'subtotal'      => $subtotal,
                ]);

                $keptItemIds[] = $existingItem->id;
            } else {
                $newItem = PurchaseOrderItem::create([
                    'purchase_order_id' => $purchaseOrder->id,
                    'product_id'        => $product->id,
                    'quantity'          => $item['quantity'],
                    'price_at_time'     => $item['price_at_time'],
                    'subtotal'          => $subtotal,
                ]);

                $keptItemIds[] = $newItem->id;
            }
        }

        PurchaseOrderItem::where('purchase_order_id', $purchaseOrder->id)
            ->whereNotIn('id', $keptItemIds)
            ->delete();
    }

    // PAYMENT METHODS
    public function recordPayment(PurchaseOrder $purchaseOrder, array $data, ?UploadedFile $attachment = null)
    {
        return DB::transaction(function () use ($purchaseOrder, $data, $attachment) {
            $oldPaymentStatus = $purchaseOrder->payment_status;

            $payment = PurchaseOrderPayment::create([
                'purchase_order_id' => $purchaseOrder->id,
                'payment_date'      => $data['payment_date'],
                'payment_method'    => $data['payment_method'],
                'amount'            => $data['amount'],
                'note'              => $data['note'] ?? null,
            ]);

            if ($attachment) {
                $this->storePaymentAttachment($purchaseOrder, $payment, $attachment);
            }

            $newPaymentStatus = $this->calculatePaymentStatus($purchaseOrder);

            $purchaseOrder->update([
                'payment_status' => $newPaymentStatus,
            ]);

            PurchaseOrderLog::create([
                'purchase_order_id' => $purchaseOrder->id,
                'type'              => 'payment',
                'old_value'         => $oldPaymentStatus,
                'new_value'         => $newPaymentStatus,
                'note'              => $data['note'] ?? null,
                'updated_by'        => Auth::id(),
            ]);

            return $payment;
        });
    }

    private function storePaymentAttachment(PurchaseOrder $purchaseOrder, PurchaseOrderPayment $payment, UploadedFile $attachment)
    {
        $path = $attachment->store('purchase-orders/payment', 'public');

        return PurchaseOrderAttachment::create([
            'purchase_order_id'         => $purchaseOrder->id,
            'purchase_order_payment_id' => $payment->id,
            'file_path'                 => $path,
            'file_type'                 => 'Pembayaran',
        ]);
    }

    private function calculatePaymentStatus(PurchaseOrder $purchaseOrder): string
    {
        $totalPaid = $purchaseOrder->payments()->sum('amount');

        if ($totalPaid <= 0) {
            return 'unpaid';
        }

        if ($totalPaid >= $purchaseOrder->total_amount) {
            return 'paid';
        }

        return 'partial';
    }

    // SHIPPING / DELIVERY METHODS
    public function advanceDeliveryStatus(PurchaseOrder $purchaseOrder, array $data, ?UploadedFile $attachment = null)
    {
        return DB::transaction(function () use ($purchaseOrder, $data, $attachment) {
            $oldStatus = $purchaseOrder->delivery_status;
            $newStatus = $this->nextDeliveryStatus($oldStatus);

            if (!$newStatus) {
                throw new \RuntimeException('PO sudah berada pada status pengiriman akhir.');
            }

            $purchaseOrder->update([
                'delivery_status' => $newStatus,
            ]);

            $deliveryOrderStatus = $this->deliveryOrderStatusFor($newStatus);
            $deliveryOrder = null;

            if ($deliveryOrderStatus) {
                $deliveryOrder = DeliveryOrder::create([
                    'purchase_order_id' => $purchaseOrder->id,
                    'delivery_number'   => $this->generateDeliveryNumber($purchaseOrder),
                    'delivery_date'     => now()->toDateString(),
                    'status'            => $deliveryOrderStatus,
                    'note'              => $data['note'] ?? null,
                ]);
            }

            if ($attachment) {
                $this->storeShippingAttachment($purchaseOrder, $deliveryOrder, $newStatus, $attachment);
            }

            PurchaseOrderLog::create([
                'purchase_order_id' => $purchaseOrder->id,
                'type'              => 'Status Pengiriman',
                'old_value'         => $oldStatus,
                'new_value'         => $newStatus,
                'note'              => $data['note'] ?? null,
                'updated_by'        => Auth::id(),
            ]);

            return $purchaseOrder;
        });
    }

    private function nextDeliveryStatus(string $current): ?string
    {
        return match ($current) {
            'draft'               => 'partially_delivered',
            'partially_delivered' => 'delivered',
            'delivered'           => 'completed',
            default               => null, // 'completed' and 'cancelled' have no further automatic transition
        };
    }

    private function deliveryOrderStatusFor(string $newDeliveryStatus): ?string
    {
        return match ($newDeliveryStatus) {
            'partially_delivered' => 'sent',
            'delivered'           => 'received',
            default               => null, // 'completed' closes the PO but isn't a new shipment event
        };
    }

    private function generateDeliveryNumber(PurchaseOrder $purchaseOrder): string
    {
        $sequence = $purchaseOrder->deliveries()->count() + 1;

        return 'DO-' . $purchaseOrder->po_number . '-' . str_pad($sequence, 2, '0', STR_PAD_LEFT);
    }

    private function storeShippingAttachment(PurchaseOrder $purchaseOrder, ?DeliveryOrder $deliveryOrder, string $newStatus, UploadedFile $attachment)
    {
        $path = $attachment->store('po_attachments/shipping', 'public');

        $fileType = match ($newStatus) {
            'partially_delivered' => 'Bukti Pengiriman',
            'delivered'           => 'Bukti Barang Sampai',
            'completed'           => 'Bukti PO Selesai',
            default               => 'Bukti Pengiriman',
        };

        return PurchaseOrderAttachment::create([
            'purchase_order_id' => $purchaseOrder->id,
            'delivery_order_id' => $deliveryOrder?->id,
            'file_path'         => $path,
            'file_type'         => $fileType,
        ]);
    }

    // CREATE METHODS
    private function createPurchaseOrder(array $data, int $customerId, float $total)
    {
        return PurchaseOrder::create([
            'po_number'         => $data['po_number'],
            'customer_id'       => $customerId,
            'order_date'        => $data['order_date'],
            'total_amount'      => $total,
            'delivery_status'   => 'draft',
            'payment_status'    => 'unpaid',
        ]);
    }

    private function createCustomer(array $data)
    {
        return Customer::firstorcreate(
            [
                'name' => $data['customer_name'],
            ],
            [
                'code' => Customer::generateCodeCustomer(),
            ]
        );
    }

    private function createProduct(array $data)
    {
        return Product::firstOrCreate(
            [
                'name' => $data['name'],
                'unit' => strtolower($data['unit']),
            ],
            [
                'code' => Product::generateCodeProduct(),
            ]
        );
    }

    // STORE METHODS
    private function storeAttachments(PurchaseOrder $purchaseOrder, string $newPath)
    {
        PurchaseOrderAttachment::create([
            'purchase_order_id' => $purchaseOrder->id,
            'file_path'         => $newPath,
            'file_type'         => 'PO Draft',
        ]);
    }

    private function storeItems(PurchaseOrder $purchaseOrder, array $data)
    {
        foreach ($data as $item) {
            $product = $this->createProduct($item);

            PurchaseOrderItem::create([
                'purchase_order_id' => $purchaseOrder->id,
                'product_id'        => $product->id,
                'quantity'          => $item['quantity'],
                'unit'              => $item['unit'],
                'price_at_time'     => $item['price_at_time'],
                'subtotal'          => $item['quantity'] * $item['price_at_time'],
            ]);
        }
    }

    private function storeLogs(PurchaseOrder $purchaseOrder, String $newPath)
    {
        PurchaseOrderLog::create([
            'purchase_order_id' => $purchaseOrder->id,
            'type'              => 'Status Pengiriman',
            'old_value'         => null,
            'new_value'         => 'draft',
            'note'              => 'PO dibuat',
            'attachment'        => $newPath,
            'updated_by'        => Auth::id(),
        ]);
    }

    // Another METHODS
    private function calculateTotal(array $data)
    {
        return collect($data)->sum(function ($data) {
            return $data['quantity'] * $data['price_at_time'];
        });
    }

    private function moveAttachmentToShipping(String $tempPath)
    {
        $filename = basename($tempPath);
        $newPath = 'po_attachments/shipping/' . $filename;
        Storage::disk('public')->move($tempPath, $newPath);

        return $newPath;
    }

    private function emptyExtractedData()
    {
        return [
            'po_number' => PurchaseOrder::generatePONumber(),
            'customer_name' => null,
            'items' => [],
            'raw_text' => '',
        ];
    }
}
