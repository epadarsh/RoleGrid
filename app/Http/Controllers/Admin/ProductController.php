<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // GET /api/admin/products
    public function index(Request $request)
    {
        $query = Product::query();

        if ($request->filled('search')) {
            $searchTerm = $request->input('search');
            $query->where('title', 'like', '%' . $searchTerm . '%')
                ->orWhere('description', 'like', '%' . $searchTerm . '%');
        }

        $sortBy = $request->input('sort_by', 'id');
        $sortDir = $request->input('sort_dir', 'asc');

        $allowedSorts = ['id', 'title', 'price', 'stock', 'created_at'];
        if (!in_array($sortBy, $allowedSorts)) {
            $sortBy = 'id';
        }
        if (!in_array(strtolower($sortDir), ['asc', 'desc'])) {
            $sortDir = 'asc';
        }

        $query->orderBy($sortBy, $sortDir);

        $perPage = $request->input('per_page', 10);

        return $query->paginate($perPage);
    }


    // POST /api/admin/products
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0.01',
            'stock' => 'required|integer|min:0',
            'image_url' => 'nullable|url|max:255',
        ]);

        $product = Product::create($request->all());

        return response()->json($product, 201);
    }

    // GET /api/admin/products/{product}
    public function show(Product $product)
    {
        return response()->json($product);
    }

    // PUT/PATCH /api/admin/products/{product}
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0.01',
            'stock' => 'required|integer|min:0',
            'image_url' => 'nullable|url|max:255',
        ]);

        $product->update($request->all());

        return response()->json($product);
    }

    // DELETE /api/admin/products/{product}
    public function destroy(Product $product)
    {
        $product->delete();

        return response()->json(['message' => 'Product deleted successfully.'], 204);
    }
}
