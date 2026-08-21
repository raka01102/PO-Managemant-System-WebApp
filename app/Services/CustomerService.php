<?php

namespace App\Services;

use App\Models\Customer;
use Illuminate\Support\Facades\Http;

class CustomerService
{
    public function getCustomers()
    {
        return Customer::all();
    }

    public function store(array $data)
    {
        Customer::create($data);
    }

    public function update(Customer $customer, array $data)
    {
        $customer->update($data);
    }

    public function delete(Customer $customer)
    {
        $customer->delete();
    }
}
