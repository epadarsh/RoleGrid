<?php

namespace App\Http\Controllers;

use App\Models\Product;

class ProductController extends Controller
{
    /**
     * Display a list of products accessible to authenticated users.
     * Accessible via /api/products
     */
    public function index()
    {

        $products = Product::select('id', 'title', 'description', 'price', 'stock', 'image_url')
            ->orderBy('title')
            ->get();

        return response()->json($products);
    }
}
