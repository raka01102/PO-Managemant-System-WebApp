<?php

namespace App\Http\Requests\PurchaseOrder;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePurchaseOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $purchaseOrderId = $this->route('purchase_order')?->id ?? $this->route('purchase_order');

        return [
            'po_number'             => ['required', 'string', 'max:50', 'unique:purchase_orders,po_number,' . $purchaseOrderId],
            'customer_name'         => ['required', 'string', 'max:100'],
            'order_date'            => ['required', 'date'],
            'items'                 => ['required', 'array', 'min:1'],
            'items.*.id'            => ['nullable'],
            'items.*.name'          => ['required', 'string', 'max:100'],
            'items.*.quantity'      => ['required', 'integer', 'min:1'],
            'items.*.unit'          => ['required', 'string', 'max:50'],
            'items.*.price_at_time' => ['required', 'numeric', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'po_number.required'             => 'Nomor PO harus diisi',
            'po_number.max'                  => 'Nomor PO maksimal 50 karakter',
            'po_number.unique'                => 'Nomor PO sudah digunakan',

            'customer_name.required'         => 'Nama Customer harus diisi',
            'customer_name.max'              => 'Nama Customer maksimal 100 karakter',

            'order_date.required'            => 'Tanggal PO harus diisi',

            'items.required'                 => 'Item harus diisi',
            'items.*.name.required'          => 'Nama Produk harus diisi',
            'items.*.name.max'               => 'Nama Produk maksimal 100 karakter',
            'items.*.unit.required'          => 'Satuan Produk harus diisi',
            'items.*.unit.max'               => 'Satuan Produk maksimal 100 karakter',
            'items.*.quantity.required'      => 'Jumlah Produk harus diisi',
            'items.*.quantity.min'           => 'Jumlah Produk minimal 1',
            'items.*.price_at_time.required' => 'Harga Produk harus diisi',
            'items.*.price_at_time.min'      => 'Harga Produk minimal 0',
        ];
    }

    protected function prepareForValidation(): void
    {
        $items = $this->items;

        if (is_array($items)) {
            $items = array_map(function ($item) {
                return [
                    'id'            => $item['id'] ?? null,
                    'name'          => isset($item['name']) ? strtoupper($item['name']) : null,
                    'quantity'      => $item['quantity'] ?? null,
                    'unit'          => isset($item['unit']) ? strtolower($item['unit']) : null,
                    'price_at_time' => $item['price_at_time'] ?? null,
                ];
            }, $items);
        }

        $this->merge([
            'po_number'     => $this->po_number ? strtoupper($this->po_number) : $this->po_number,
            'customer_name' => $this->customer_name ? strtoupper($this->customer_name) : $this->customer_name,
            'items'         => $items,
        ]);
    }
}
