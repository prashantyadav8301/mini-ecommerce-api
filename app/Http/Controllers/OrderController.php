<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function store()
    {
        $cartItems = Cart::with('product')
            ->where('user_id', auth()->id())
            ->get();

        if ($cartItems->isEmpty()) {
            return response()->json([
                'message' => 'Cart is empty',
            ], 400);
        }

        foreach ($cartItems as $item) {
            if ($item->product->stock < $item->quantity) {
                return response()->json([
                    'message' => "Insufficient stock for {$item->product->name}. Available: {$item->product->stock}",
                ], 400);
            }
        }

        $total = $cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });

        $order = Order::create([
            'user_id' => auth()->id(),
            'total_amount' => $total,
            'status' => 'pending',
        ]);

        foreach ($cartItems as $item) {
            OrderItem::create([
                'order_id'   => $order->id,
                'product_id' => $item->product_id,
                'quantity'   => $item->quantity,
                'price'      => $item->product->price,  // snapshot!
            ]);

            $item->product->decrement('stock', $item->quantity);
        }

        Cart::where('user_id', auth()->id())->delete();

        return response()->json([
            'message' => 'Order placed successfully.',
            'order'   => $order->load('items.product'),
        ], 201);
    }

    public function index()
    {
        $orders = Order::with('items.product')
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        return response()->json([
            'message' => 'Orders successfully fetched.',
            'orders' => $orders,
        ]);
    }

    // public function show($id)
    // {
    //     $order = Order::with('items.product')
    //         ->where('id', $id)
    //         ->where('user_id', auth()->id())
    //         ->firstOrFail();

    //     return response()->json([
    //         'message' => 'Single order fetched.',
    //         'data' => $order,
    //     ]);
    // }

    // public function cancel($id)
    // {
    //     return "hit";
    // }

    public function cancel($id)
    {
        $order = Order::with('items.product')
            ->whereKey($id)
            ->first();

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        if ($order->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($order->status !== 'pending') {
            return response()->json([
                'message' => 'Cannot cancel it',
            ]);
        }

        foreach ($order->items as $item) {
            $item->product->increment('stock', $item->quantity);
        }

        $order->status = 'cancelled';
        $order->save();

        return response()->json([
            'message' => 'Order cancelled successfully.',
            'order'   => $order,
        ]);
    }
}
