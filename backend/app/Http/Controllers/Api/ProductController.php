<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
            'stock'       => 'nullable|integer|min:0',
            // Mendukung file upload (foto) atau string URL opsional
            'image_url'   => 'nullable',
        ]);

        // Cek jika request membawa file gambar
        if ($request->hasFile('image_url')) {
            $request->validate([
                'image_url' => 'image|mimes:jpeg,jpg,png,webp|max:2048',
            ]);
            $path = $request->file('image_url')->store('products', 'public');
            $validated['image_url'] = url('storage/' . $path);
        }

        $product = Product::create($validated);

        return response()->json([
            'status'  => true,
            'message' => 'Produk berhasil dibuat',
            'data'    => $product
        ], 201);
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
            'stock'       => 'sometimes|required|integer|min:0',
            'image_url'   => 'nullable',
        ]);

        // Cek jika ada file gambar baru yang diunggah
        if ($request->hasFile('image_url')) {
            $request->validate([
                'image_url' => 'image|mimes:jpeg,jpg,png,webp|max:2048',
            ]);

            // Hapus gambar lama jika file fisik tersimpan di storage
            if ($product->image_url && str_contains($product->image_url, 'storage/products/')) {
                $oldPath = str_replace(url('storage/'), '', $product->image_url);
                Storage::disk('public')->delete($oldPath);
            }

            // Simpan gambar baru
            $path = $request->file('image_url')->store('products', 'public');
            $validated['image_url'] = url('storage/' . $path);
        }

        $product->update($validated);

        return response()->json([
            'status'  => true,
            'message' => 'Produk berhasil diubah',
            'data'    => $product
        ], 200);
    }

    public function destroy($id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json(['status' => false, 'message' => 'Produk tidak ditemukan'], 404);
        }

        // Hapus file gambar fisik dari storage jika ada
        if ($product->image_url && str_contains($product->image_url, 'storage/products/')) {
            $oldPath = str_replace(url('storage/'), '', $product->image_url);
            Storage::disk('public')->delete($oldPath);
        }

        $product->delete();
        return response()->json(['status' => true, 'message' => 'Produk berhasil dihapus'], 200);
    }
}
