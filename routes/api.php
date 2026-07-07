<?php

use App\Models\Product;
use App\Models\Borrowing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Fetch all products (inventaris)
Route::get('/products', function () {
    $products = Product::with('category')->orderBy('code', 'asc')->get();
    return response()->json([
        'status' => 'success',
        'data' => $products
    ]);
});

// Fetch single product details
Route::get('/products/{product}', function (Product $product) {
    return response()->json([
        'status' => 'success',
        'data' => $product->load('category')
    ]);
});

// Fetch borrowings history
Route::get('/borrowings', function () {
    $borrowings = Borrowing::with('products')->orderBy('created_at', 'desc')->get();
    return response()->json([
        'status' => 'success',
        'data' => $borrowings
    ]);
});
