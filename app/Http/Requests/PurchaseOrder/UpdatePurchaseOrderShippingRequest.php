<?php

namespace App\Http\Requests\PurchaseOrder;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePurchaseOrderShippingRequest extends FormRequest
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
            'note'       => ['nullable', 'string', 'max:1000'],
            'attachment' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'note.max'             => 'Catatan maksimal 1000 karakter',

            'attachment.required'  => 'Bukti pengiriman harus diunggah',
            'attachment.file'      => 'Bukti pengiriman harus berupa file',
            'attachment.mimes'     => 'Bukti pengiriman harus berupa JPG, JPEG, PNG, atau PDF',
            'attachment.max'       => 'Ukuran bukti pengiriman maksimal 2MB',
        ];
    }
}
