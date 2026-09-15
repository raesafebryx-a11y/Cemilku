<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Categorie;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategorieController extends Controller
{
    public function index()
    {
        $categories = Categorie::withCount('products')->latest()->get();
        return response()->json(['status' => true, 'data' => $categories], 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $category = Categorie::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
        ]);

        return response()->json(['status' => true, 'message' => 'Kategori berhasil dibuat', 'data' => $category], 201);
    }

    public function show($id)
    {
        $category = Categorie::with('products')->find($id);
        if (!$category) {
            return response()->json(['status' => false, 'message' => 'Kategori tidak ditemukan'], 404);
        }
        return response()->json(['status' => true, 'data' => $category], 200);
    }

    public function update(Request $request, $id)
    {
        $category = Categorie::find($id);
        if (!$category) {
            return response()->json(['status' => false, 'message' => 'Kategori tidak ditemukan'], 404);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $category->update([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
        ]);

        return response()->json(['status' => true, 'message' => 'Kategori berhasil diubah', 'data' => $category], 200);
    }

    public function destroy($id)
    {
        $category = Categorie::find($id);
        if (!$category) {
            return response()->json(['status' => false, 'message' => 'Kategori tidak ditemukan'], 404);
        }

        $category->delete();
        return response()->json(['status' => true, 'message' => 'Kategori berhasil dihapus'], 200);
    }
}
