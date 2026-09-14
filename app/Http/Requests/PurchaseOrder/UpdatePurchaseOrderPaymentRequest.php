<?php

namespace App\Http\Requests\PurchaseOrder;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePurchaseOrderPaymentRequest extends FormRequest
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
        return [
            'amount'         => ['required', 'numeric', 'min:0.01'],
            'payment_method' => ['required', 'string', 'in:transfer,cash'],
            'payment_date'   => ['required', 'date'],
            'note'           => ['nullable', 'string', 'max:1000'],
            'attachment'     => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'amount.required'         => 'Jumlah pembayaran harus diisi',
            'amount.numeric'          => 'Jumlah pembayaran harus berupa angka',
            'amount.min'              => 'Jumlah pembayaran harus lebih dari 0',

            'payment_method.required' => 'Metode pembayaran harus dipilih',
            'payment_method.in'       => 'Metode pembayaran tidak valid',

            'payment_date.required'   => 'Tanggal pembayaran harus diisi',
            'payment_date.date'       => 'Tanggal pembayaran tidak valid',

            'note.max'                => 'Catatan maksimal 1000 karakter',

            'attachment.required'     => 'Bukti pembayaran harus diunggah',
            'attachment.file'         => 'Bukti pembayaran harus berupa file',
            'attachment.mimes'        => 'Bukti pembayaran harus berupa JPG, JPEG, PNG, atau PDF',
            'attachment.max'          => 'Ukuran bukti pembayaran maksimal 2MB',
        ];
    }
}
