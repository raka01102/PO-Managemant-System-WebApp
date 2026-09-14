<?php

namespace App\Http\Controllers;

use App\Http\Requests\PurchaseOrder\ScanPurchaseOrderRequest;
use Illuminate\Http\Request;
use App\Services\PurchaseOrderService;
use App\Http\Requests\PurchaseOrder\StorePurchaseOrderRequest;
use App\Http\Requests\PurchaseOrder\UpdatePurchaseOrderRequest;
use App\Http\Requests\PurchaseOrder\UpdatePurchaseOrderPaymentRequest;
use App\Http\Requests\PurchaseOrder\UpdatePurchaseOrderShippingRequest;
use App\Models\PurchaseOrder;
use App\Models\Customer;
use App\Services\OCRService;
use Illuminate\Support\Facades\Validator;

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
            'payments',
        );
        return view('purchase-orders.show', compact('purchaseOrder'));
    }

    public function edit(purchaseOrder $purchaseOrder)
    {
        $purchaseOrder->load('customer', 'items.product', 'attachments', 'logs');
        $items = $purchaseOrder->items->map(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->product->name ?? 'Produk telah dihapus',
                'quantity' => $item->quantity,
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

    public function updateShipping(UpdatePurchaseOrderShippingRequest $request, PurchaseOrder $purchaseOrder)
    {
        try {
            $this->purchaseOrderService->advanceDeliveryStatus(
                $purchaseOrder,
                $request->validated(),
                $request->file('attachment')
            );

            return redirect()
                ->route('purchase-orders.index')
                ->with('success', 'Status Pengiriman PO berhasil diperbarui.');
        } catch (\RuntimeException $e) {

            return redirect()
                ->back()
                ->with('error', $e->getMessage());
        } catch (\Throwable $e) {

            report($e);

            return redirect()
                ->back()
                ->with('error', 'Gagal update status pengiriman.');
        }
    }

    public function updatePayment(UpdatePurchaseOrderPaymentRequest $request, PurchaseOrder $purchaseOrder)
    {
        try {
            $this->purchaseOrderService->recordPayment(
                $purchaseOrder,
                $request->validated(),
                $request->file('attachment')
            );

            return redirect()
                ->route('purchase-orders.index')
                ->with('success', 'Pembayaran berhasil diperbarui.');
        } catch (\Throwable $e) {

            report($e);

            return redirect()
                ->back()
                ->with('error', 'Gagal update pembayaran.');
        }
    }

    public function update(UpdatePurchaseOrderRequest $request, PurchaseOrder $purchaseOrder)
    {
        try {
            $this->purchaseOrderService->update($purchaseOrder, $request->validated());

            return redirect()
                ->route('purchase-orders.index')
                ->with('success', 'PO berhasil diperbarui.');
        } catch (\Throwable $e) {

            report($e);

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
