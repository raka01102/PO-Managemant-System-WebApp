<?php

namespace App\Http\Controllers;

use App\Http\Requests\PurchaseOrder\ScanPurchaseOrderRequest;
use Illuminate\Http\Request;
use App\Services\PurchaseOrderService;
use App\Http\Requests\PurchaseOrder\StorePurchaseOrderRequest;
use App\Http\Requests\PurchaseOrder\UpdatePurchaseOrderRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\Customer;
use App\Models\Product;
use App\Models\PurchaseOrderAttachment;
use App\Models\PurchaseOrderLog;
use App\Services\OCRService;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class PurchaseOrderController extends Controller
{
    protected PurchaseOrderService $purchaseOrderService;

    public function __construct(PurchaseOrderService $purchaseOrderService)
    {
        $this->purchaseOrderService = $purchaseOrderService;
    }

    // GET METHODS
    public function index(Request $request, PurchaseOrder $purchaseOrder) // GET /purchase-orders.index
    {
        $purchaseOrders = $purchaseOrder->with([
            'customer',
            'items.product',
            'logs',
            'payments',
        ])->latest()->paginate(12);

        return view('purchase-orders.index', compact('purchaseOrders'));
    }

    public function create()
    {
        return view('purchase-orders.create');
    }

    public function shipping(purchaseOrder $purchaseOrder)
    {
        return view('purchase-orders.shipping', compact('purchaseOrder'));
    }

    public function payment(purchaseOrder $purchaseOrder)
    {
        return view('purchase-orders.payment', compact('purchaseOrder'));
    }

    public function show(purchaseOrder $purchaseOrder)
    {
        $purchaseOrder->load(
            'customer',
            'items.product',
            'attachments',
            'logs',
            'payments',
        )->latest()->paginate(12);
        return view('purchase-orders.show', compact('purchaseOrder'));
    }

    public function edit(purchaseOrder $purchaseOrder)
    {
        $purchaseOrder->load('customer', 'items.product', 'attachments', 'logs');
        $items = $purchaseOrder->items->map(function ($item) {
            return [
                'id' => $item->product->id,
                'name' => $item->product->name ?? 'Produk telah dihapus',
                'qty' => $item->quantity,
                'unit' => $item->product->unit,
                'price_at_time' => $item->price_at_time,
            ];
        });

        return view('purchase-orders.edit', compact('purchaseOrder', 'items'));
    }

    // POST METHODS
    public function scan(ScanPurchaseOrderRequest $request, PurchaseOrderService $purchaseOrderService, OCRService $ocr)
    {
        $result = $purchaseOrderService->prepareScanData($request->file('document'));

        $customers = Customer::orderBy('name', 'asc')->get();

        return view('purchase-orders.confirm', [
            'extracted' => $result['extracted'],
            'customers' => $customers,
            'filePath'  => $result['file_path'],
        ]);
    }

    public function store(StorePurchaseOrderRequest $request) // POST /purchase-orders.store
    {
        // dd($request->validated());
        $this->purchaseOrderService->store($request->validated());

        return redirect()->route('purchase-orders.index')->with('success', 'PO berhasil dibuat');
    }

    public function updateShipping(Request $request, purchaseOrder $purchaseOrder)
    {
        $validated = $request->validate([
            'status' => 'required|in:draft,sent,delivered,completed',
            'attachment' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'note' => 'nullable|string|max:1000',
        ]);

        $attachment = null;

        DB::beginTransaction();

        try {

            $oldStatus = $purchaseOrder->status;

            $newStatus = match ($oldStatus) {
                'draft' => 'sent',
                'sent' => 'delivered',
                'delivered' => 'completed',
                default => 'draft',
            };

            $purchaseOrder->update([
                'status' => $newStatus,
            ]);

            PurchaseOrderLog::create([
                'purchase_order_id' => $purchaseOrder->id,
                'type' => 'Status Pengiriman',
                'old_value' => $oldStatus,
                'new_value' => $newStatus,
                'note' => $validated['note'],
            ]);

            if ($request->hasFile('attachment')) {
                $attachment = $request->file('attachment')->store('po_attachments/shipping', 'public');

                $fileType = match ($validated['status']) {
                    'sent' => 'Bukti Pengiriman',
                    'delivered' => 'Bukti Barang Sampai',
                    'completed' => 'Bukti PO Selesai',
                    default => null,
                };

                if ($fileType) {
                    PurchaseOrderAttachment::create([
                        'purchase_order_id' => $purchaseOrder->id,
                        'file_path' => $attachment,
                        'file_type' => $fileType,
                    ]);
                }
            }

            DB::commit();

            return redirect()
                ->route('purchase-orders.index')
                ->with('success', 'Status Pengiriman PO berhasil diperbarui.');
        } catch (\Exception $e) {

            DB::rollBack();

            return redirect()
                ->back()
                ->with('error', 'Gagal update status.');
        }
    }

    public function updatePayment(Request $request, PurchaseOrder $purchaseOrder)
    {
        $validated = $request->validate([
            'is_paid' => 'required|in:0,1',
            'attachment' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'note' => 'nullable|string|max:1000',
        ]);

        $attachment = null;

        DB::beginTransaction();

        try {

            if ($request->hasFile('attachment')) {
                $attachment = $request
                    ->file('attachment')
                    ->store(
                        'purchase-orders/payment',
                        'public'
                    );
            }

            $oldPayment = $purchaseOrder->is_paid ? 'paid' : 'unpaid';

            $purchaseOrder->update([
                'is_paid' => true,
            ]);

            $newPayment = $request->is_paid ? 'paid' : 'unpaid';

            PurchaseOrderLog::create([
                'purchase_order_id' => $purchaseOrder->id,
                'type' => 'payment',
                'old_value' => $oldPayment,
                'new_value' => $newPayment,
                'note' => $request->note,
            ]);

            PurchaseOrderAttachment::create([
                'purchase_order_id' => $purchaseOrder->id,
                'file_path' => $attachment,
                'file_type' => 'Pembayaran',
            ]);

            DB::commit();

            return redirect()
                ->route('purchase-orders.index')
                ->with('success', 'Pembayaran berhasil diperbarui.');
        } catch (\Exception $e) {

            DB::rollBack();

            return redirect()
                ->back()
                ->with('error', 'Gagal update pembayaran.');
        }
    }

    public function update(Request $request, PurchaseOrder $purchaseOrder)
    {
        // dd($request->all());
        // dd($purchaseOrder->id);
        $validated = $request->validate([
            'po_number'             => 'required|string|unique:purchase_orders,po_number,' . $purchaseOrder->id,
            'customer_name'         => 'required|string',
            'order_date'            => 'required|date',
            'items'                 => 'required|array|min:1',
            'items.*.id'            => 'nullable|string',
            'items.*.name'          => 'required|string',
            'items.*.qty'           => 'required|integer|min:1',
            'items.*.unit'          => 'required|string',
            'items.*.price_at_time' => 'required|numeric|min:0',
        ]);

        // dd($validated);

        DB::beginTransaction();

        try {
            $customer = Customer::firstOrCreate(
                ['name' => $validated['customer_name']],
                ['code' => 'CUST-' . strtoupper(Str::random(6))],
            );

            $totalAmount = collect($validated['items'])
                ->sum(function ($item) {
                    return ($item['qty'] ?? 0)
                        * ($item['price_at_time'] ?? 0);
                });
            // dd($totalAmount);

            $purchaseOrder->update(
                [
                    'po_number' => $validated['po_number'],
                    'customer_id' => $customer->id,
                    'order_date' => $validated['order_date'],
                    'total_amount' => $totalAmount,
                ]
            );

            $existingItemsIds = [];

            foreach ($validated['items'] as $item) {
                $product = Product::firstOrCreate(
                    [
                        'name' => strtoupper($item['name']),
                        'unit' => strtolower($item['unit'])
                    ],
                    [
                        'code'  => 'PRD-' . strtoupper(Str::random(6)),
                        'price' => $item['price_at_time'],
                    ]
                );

                // dd($product);

                $subtotal = $item['qty'] * $item['price_at_time'];

                // dd($subtotal);

                if (!empty($item['id'])) {
                    $poItem = PurchaseOrderItem::where(
                        'purchase_order_id',
                        $purchaseOrder->id,
                    )
                        ->where(
                            'product_id',
                            $item['id'],
                        )
                        ->first();

                    // dd($poItem);

                    if ($poItem) {
                        $poItem->update([
                            'product_id' => $product->id,
                            'quantity' => $item['qty'],
                            'price_at_time' => $item['price_at_time'],
                            'subtotal' => $subtotal,
                        ]);

                        $existingItemsIds[] = $poItem->id;
                    }
                } else {
                    $newItem = purchaseOrderItem::create([
                        'purchase_order_id' => $purchaseOrder->id,
                        'product_id' => $product->id,
                        'quantity' => $item['qty'],
                        'price_at_time' => $item['price_at_time'],
                        'subtotal' => $subtotal,
                    ]);

                    $existingItemsIds[] = $newItem->id;
                }
            }

            PurchaseOrderItem::where(
                'purchase_order_id',
                $purchaseOrder->id,
            )
                ->whereNotIn(
                    'id',
                    $existingItemsIds,
                )
                ->delete();

            DB::commit();
            return redirect()
                ->route('purchase-orders.index')
                ->with('success', 'PO berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            // dd($e->getMessage());
            return redirect()
                ->back()
                ->with('error', 'Gagal memperbarui PO. Silakan coba lagi.');
        }
    }

    public function destroy(string $id)
    {
        $po = PurchaseOrder::findOrFail($id);
        $po->delete();

        return redirect()->route('purchase-orders.index')->with('success', 'PO berhasil dihapus.');
    }
}
