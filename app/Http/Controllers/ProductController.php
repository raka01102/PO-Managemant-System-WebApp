<?php

namespace App\Http\Controllers;

use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Services\ProductService;

class ProductController extends Controller
{
    protected ProductService $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function index()
    {
        $products = Product::latest()->paginate(10);
        return view('products.index', compact('products'));
    }

    public function create()
    {
        return view('products.create');
    }

    public function store(StoreProductRequest $request)
    {
        $this->productService->store($request->validated());

        return redirect()->route('products.index')->with('success', 'Product berhasil ditambahkan.');
    }

    public function show(string $id)
    {
        $product = Product::findOrFail($id);
        return view('products.show', compact('product'));
    }

    public function edit(string $id)
    {
        $product = Product::findOrFail($id);
        return view('products.edit', compact('product'));
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        $this->productService->update($product, $request->validated());

        return redirect()->route('products.index')->with('success', 'Product berhasil diperbarui.');
    }

    public function destroy(Product $product)
    {
        $this->productService->delete($product);

        return redirect()->route('products.index')->with('success', 'Product berhasil dihapus.');
    }
}
