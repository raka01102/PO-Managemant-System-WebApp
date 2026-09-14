<?php

namespace App\Http\Requests\Customer;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCustomerRequest extends FormRequest
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
            'code' => ['required', 'string', 'max:50', Rule::unique('customers', 'code')->ignore($this->route('customer'))],
            'name' => ['required', 'string', 'max:100'],
            'address' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
        ];
    }

    public function messages(): array
    {
        return [
            'code.required' => 'Kode Customer harus diisi',
            'code.unique' => 'Kode Customer sudah digunakan',
            'code.max' => 'Kode Customer maksimal 50 karakter',

            'name.required' => 'Nama Customer harus diisi',
            'name.max' => 'Nama Customer maksimal 100 karakter',

            'address.max' => 'Alamat maksimal 255 karakter',
            'phone.max' => 'Nomor Telepon maksimal 20 karakter',
            'phone.integer' => 'Nomor Telepon harus berupa angka',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'code' => strtoupper($this->code),
            'name' => strtoupper($this->name),
            'address' => strtolower($this->address),
        ]);
    }
}
