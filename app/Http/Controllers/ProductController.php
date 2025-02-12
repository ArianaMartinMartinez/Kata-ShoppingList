<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();

        return response()->json($products, 200);
    }

    public function store(Request $request)
    {
        //
    }

    public function show(string $id)
    {
        $product = Product::findOrFail($id);

        return response()->json($product, 200);
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroyOneProduct(string $id)
    {
        //
    }

    public function destroyAllProducts() {
        //
    }
}
