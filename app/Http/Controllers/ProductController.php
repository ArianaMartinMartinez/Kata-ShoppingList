<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();

        return response()->json($products, 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required | string',
            'quantity' => 'required | integer | min:1',
        ]);

        if($validator->fails()) {
            return response()->json([
                'message' => 'Introduced data is not correct',
                'errors' => $validator->errors(),
            ], 400);
        }

        $validated = $validator->validate();

        $productAlreadyExists = Product::where('name', $validated['name'])->first();
        if($productAlreadyExists) {
            return response()->json([
                'message' => 'Introduced product already exists in the list',
            ], 400);
        }

        $product = Product::create([
            'name' => $validated['name'],
            'quantity' => $validated['quantity'],
        ]);
        $product->save();

        return response()->json($product, 201);
    }

    public function show(string $id)
    {
        $product = Product::findOrFail($id);

        return response()->json($product, 200);
    }

    public function update(Request $request, string $id)
    {
        $product = Product::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required | string',
            'quantity' => 'required | integer | min:1',
        ]);

        if($validator->fails()) {
            return response()->json([
                'message' => 'Introduced data is not correct',
                'errors' => $validator->errors(),
            ], 400);
        }

        $validated = $validator->validate();

        $productAlreadyExists = Product::where('name', $validated['name'])->first();
        if($productAlreadyExists && $productAlreadyExists->id !== $product->id) {
            return response()->json([
                'message' => 'Introduced product already exists in the list',
            ], 400);
        }

        $product->update([
            'name' => $validated['name'],
            'quantity' => $validated['quantity'],
        ]);
        $product->save();

        return response()->json($product, 200);
    }

    public function destroyOneProduct(string $id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return response()->json([
            'message' => 'Product deleted',
            'product' => $product,
        ], 200);
    }

    public function destroyAllProducts() {
        //
    }
}
