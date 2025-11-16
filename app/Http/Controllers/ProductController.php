<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a list of products accessible to authenticated users.
     * Accessible via /api/products
     */
    public function index()
    {
        // Fetch all products, but only select the fields needed for display
        $products = Product::select('id', 'title', 'description', 'price', 'stock', 'image_url')
            ->orderBy('title')
            ->get();

        return response()->json($products);
    }
}
