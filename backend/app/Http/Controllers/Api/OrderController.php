<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('items.product')->latest()->get();
        return response()->json(['status' => true, 'data' => $orders], 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id'          => 'nullable|exists:users,id',
            'customer_name'    => 'required|string|max:255',
            'customer_address' => 'required|string',
            'notes'            => 'nullable|string',
            'note'             => 'nullable|string', // Antisipasi input tanpa huruf 's'
            'items'            => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.qty'      => 'required|integer|min:1',
        ]);

        DB::beginTransaction();
        try {
            $totalPrice = 0;
            $orderItemsData = [];

            foreach ($validated['items'] as $item) {
                $product = Product::findOrFail($item['product_id']);

                // Cek stok produk
                if ($product->stock < $item['qty']) {
                    DB::rollBack();
                    return response()->json([
                        'status' => false,
                        'message' => "Stok untuk produk '{$product->name}' tidak mencukupi."
                    ], 400);
                }

                $subtotal = $product->price * $item['qty'];
                $totalPrice += $subtotal;

                // Kurangi stok
                $product->decrement('stock', $item['qty']);

                $orderItemsData[] = [
                    'product_id' => $product->id,
                    'qty'        => $item['qty'],
                    'price'      => $product->price,
                ];
            }

            // Prioritas user_id: 1. Dari token login Sanctum, 2. Dari request JSON input
            $userId = auth('sanctum')->id() ?? ($validated['user_id'] ?? null);

            // Prioritas notes: ambil dari 'notes' atau 'note'
            $notes = $validated['notes'] ?? ($validated['note'] ?? null);

            $order = Order::create([
                'user_id'          => $userId,
                'customer_name'    => $validated['customer_name'],
                'customer_address' => $validated['customer_address'],
                'notes'            => $notes,
                'total_price'      => $totalPrice,
                'status'           => 'pending',
            ]);

            foreach ($orderItemsData as $itemData) {
                $order->items()->create($itemData);
            }

            DB::commit();

            return response()->json([
                'status'  => true,
                'message' => 'Pesanan berhasil dibuat',
                'data'    => $order->load('items.product')
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        $order = Order::with('items.product')->find($id);
        if (!$order) {
            return response()->json(['status' => false, 'message' => 'Pesanan tidak ditemukan'], 404);
        }
        return response()->json(['status' => true, 'data' => $order], 200);
    }

    public function updateStatus(Request $request, $id)
    {
        $order = Order::find($id);
        if (!$order) {
            return response()->json(['status' => false, 'message' => 'Pesanan tidak ditemukan'], 404);
        }

        $validated = $request->validate([
            'status' => 'required|in:pending,processing,completed,cancelled',
        ]);

        $order->update(['status' => $validated['status']]);

        return response()->json(['status' => true, 'message' => 'Status pesanan berhasil diperbarui', 'data' => $order], 200);
    }
}
