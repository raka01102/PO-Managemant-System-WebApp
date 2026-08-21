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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\UploadedFile;

class PurchaseOrderService
{
    public function __construct(private OCRService $ocr) {}

    public function get(string $id)
    {
        $purchaseOrder = PurchaseOrder::with([
            'customer',
            'items.product',
            'attachments',
            'logs',
            'payments',
        ])->findOrFail($id);
        return $purchaseOrder;
    }

    public function prepareScanData(UploadedFile $document)
    {
        $tempPath = $document->store('temp_scans', 'public');

        $extractedData = $this->emptyExtractedData();

        if (config('ocr.enabled')) {
            try {
                $extractedData = $this->ocr->scan($document);
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
        $purchaseOrder->update($data);
    }

    public function delete(PurchaseOrder $purchaseOrder)
    {
        $purchaseOrder->delete();
    }

    private function emptyExtractedData()
    {
        return [
            'po_number' => '',
            'customer_name' => '',
            'order_date' => '',
            'items' => [],
            'raw_text' => '',
        ];
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
        $lastCustomer = Customer::latest()->first();
        $newCustomerCode = 'CUS-' . str_pad(($lastCustomer ? (int) substr($lastCustomer->code, 4) + 1 : 1), 4, '0', STR_PAD_LEFT);

        return Customer::firstorcreate(
            [
                'name' => $data['customer_name'],
            ],
            [
                'code' => $newCustomerCode,
            ]
        );
    }

    private function createProduct(array $data)
    {
        $lastProduct = Product::latest()->first();
        $newProductCode = 'PRD-' . str_pad(($lastProduct ? (int) substr($lastProduct->code, 4) + 1 : 1), 4, '0', STR_PAD_LEFT);

        return Product::firstOrCreate(
            [
                'name' => $data['name'],
                'unit' => strtolower($data['unit']),
            ],
            [
                'code' => $newProductCode,
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
}
