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

    public function index(Request $request)
    {
        $search = $request->query('search');
        $unit = $request->query('unit');

        $products = Product::query()
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('code', 'like', "%{$search}%");
                });
            })
            ->when($unit, function ($query, $unit) {
                $query->where('unit', $unit);
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        $units = Product::query()
            ->select('unit')
            ->distinct()
            ->orderBy('unit')
            ->pluck('unit');

        return view('products.index', compact('products', 'units'));
    }

    public function create()
    {
        $newCode = Product::generateCodeProduct();
        return view('products.create', compact('newCode'));
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
