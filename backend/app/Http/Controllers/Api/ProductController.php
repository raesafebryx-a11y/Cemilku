<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->latest()->get();
        return response()->json(['status' => true, 'data' => $products], 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name'        => 'required|string|max:255',
            'price'       => 'required|numeric|min:0',
            'spicy_level' => 'nullable|string',
            'tag'         => 'nullable|string',
            'description' => 'nullable|string',
            'image_url'   => 'nullable|string',
            'stock'       => 'required|integer|min:0',
        ]);

        $product = Product::create($validated);

        return response()->json(['status' => true, 'message' => 'Produk berhasil dibuat', 'data' => $product], 201);
    }

    public function show($id)
    {
        $product = Product::with('category')->find($id);
        if (!$product) {
            return response()->json(['status' => false, 'message' => 'Produk tidak ditemukan'], 404);
        }
        return response()->json(['status' => true, 'data' => $product], 200);
    }

    public function update(Request $request, $id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json(['status' => false, 'message' => 'Produk tidak ditemukan'], 404);
        }

        $validated = $request->validate([
            'category_id' => 'sometimes|required|exists:categories,id',
            'name'        => 'sometimes|required|string|max:255',
            'price'       => 'sometimes|required|numeric|min:0',
            'spicy_level' => 'nullable|string',
            'tag'         => 'nullable|string',
            'description' => 'nullable|string',
            'image_url'   => 'nullable|string',
            'stock'       => 'sometimes|required|integer|min:0',
        ]);

        $product->update($validated);

        return response()->json(['status' => true, 'message' => 'Produk berhasil diubah', 'data' => $product], 200);
    }

    public function destroy($id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json(['status' => false, 'message' => 'Produk tidak ditemukan'], 404);
        }

        $product->delete();
        return response()->json(['status' => true, 'message' => 'Produk berhasil dihapus'], 200);
    }
}
