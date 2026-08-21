<?php

namespace App\Http\Requests\PurchaseOrder;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ScanPurchaseOrderRequest extends FormRequest
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
            'document' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:2048'],
        ];
    }

    public function messages()
    {
        return [
            'document.required' => 'File dokumen harus diunggah',
            'document.file' => 'File dokumen harus berupa file',
            'document.mimes' => 'File dokumen harus berupa PDF, JPG, JPEG, atau PNG',
            'document.max' => 'Ukuran file dokumen maksimal 2MB',
        ];
    }
}
