<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class ProductService
{
    public function getProducts()
    {
        return Product::all();
    }

    public function store(array $data)
    {
        Product::create($data);
    }

    public function update(Product $product, array $data)
    {
        $product->update($data);
    }

    public function delete(Product $product)
    {
        $product->delete();
    }
}
