<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Http\Requests\Customer\StoreCustomerRequest;
use App\Http\Requests\Customer\UpdateCustomerRequest;
use App\Services\CustomerService;

class CustomerController extends Controller
{
    protected CustomerService $customerService;

    public function __construct(CustomerService $customerService)
    {
        $this->customerService = $customerService;
    }

    public function index()
    {
        $customers = Customer::latest()->paginate(12);
        return view('customers.index', compact('customers'));
    }

    public function create()
    {
        $newCode = Customer::generateCodeCustomer();
        return view('customers.create', compact('newCode'));
    }

    public function search(Request $request)
    {
        $customers = Customer::query()
            ->where(
                'name',
                'like',
                '%' . $request->search . '%'
            )
            ->limit(10)
            ->get([
                'id',
                'name'
            ]);

        return response()->json($customers);
    }

    public function store(StoreCustomerRequest $request)
    {
        $this->customerService->store($request->validated());

        return redirect()->route('customers.index')->with('success', 'Pelanggan berhasil ditambahkan');
    }

    public function show(string $id)
    {
        $customer = Customer::findOrFail($id);
        return view('customers.show', compact('customer'));
    }

    public function edit(string $id)
    {
        $customer = Customer::findOrFail($id);
        return view('customers.edit', compact('customer'));
    }

    public function update(UpdateCustomerRequest $request, Customer $customer)
    {
        $this->customerService->update($customer, $request->validated());

        return redirect()->route('customers.index')->with('success', 'Pelanggan berhasil diperbarui.');
    }

    public function destroy(Customer $customer)
    {
        $this->customerService->delete($customer);
        return redirect()->route('customers.index')->with('success', 'Pelanggan berhasil dihapus.');
    }
}
