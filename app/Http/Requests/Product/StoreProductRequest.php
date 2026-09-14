<?php

namespace App\Http\Requests\Product;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProductRequest extends FormRequest
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
            'code' => ['required', 'string', 'max:50', Rule::unique('products', 'code')],
            'name' => ['required', 'string', 'max:100'],
            'unit' => ['required', 'string', 'max:100'],
            'price' => ['required', 'numeric', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'code.required' => 'Kode Produk harus diisi',
            'code.unique' => 'Kode Produk sudah digunakan',
            'code.max' => 'Kode Produk maksimal 50 karakter',

            'name.required' => 'Nama Produk harus diisi',
            'name.max' => 'Nama Produk maksimal 100 karakter',

            'unit.required' => 'Satuan Produk harus diisi',
            'unit.max' => 'Satuan Produk maksimal 100 karakter',

            'price.required' => 'Harga Produk harus diisi',
            'price.numeric' => 'Harga Produk harus berupa angka',
            'price.min' => 'Harga Produk tidak boleh kurang dari 0',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'code' => strtoupper($this->code),
            'name' => strtoupper($this->name),
            'unit' => strtolower($this->unit),
        ]);
    }
}
