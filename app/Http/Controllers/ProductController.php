<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Models\Product;
use Inertia\Inertia;

class ProductController extends Controller
{
    /**
     * Admin product listing
     */
    public function adminIndex()
    {
        $sortBy = request('sort_by', 'id');
        $sortDirection = request('sort_direction', 'desc');
        $perPage = request('per_page', 10);

        // Validate sort parameters
        $allowedSortFields = ['id', 'name', 'price', 'quantity_available', 'created_at'];
        if (! in_array($sortBy, $allowedSortFields)) {
            $sortBy = 'id';
        }
        if (! in_array($sortDirection, ['asc', 'desc'])) {
            $sortDirection = 'desc';
        }

        $products = Product::orderBy($sortBy, $sortDirection)
            ->paginate($perPage)
            ->withQueryString();

        return Inertia::render('Products', [
            'products' => $products,
            'filters' => [
                'sort_by' => $sortBy,
                'sort_direction' => $sortDirection,
                'per_page' => $perPage,
            ],
        ]);
    }

    /**
     * User-facing product listing
     */
    public function index()
    {
        $products = Product::orderBy('name', 'asc')
            ->paginate(12)
            ->withQueryString();

        return Inertia::render('Products/Index', [
            'products' => $products,
        ]);
    }

    /**
     * Show product details for users
     */
    public function show(Product $product)
    {
        return Inertia::render('Products/Show', [
            'product' => $product,
        ]);
    }

    public function store(ProductRequest $request)
    {
        Product::create($request->validated());

        return redirect()->route('products.index')->with('success', 'Product created successfully.');
    }

    public function update(ProductRequest $request, Product $product)
    {
        $product->update($request->validated());

        return redirect()->route('products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
    }
}
