<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use MongoDB\BSON\ObjectId;

class OrderController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        Log::info('Fetching orders for user', ['user_id' => $userId]);
        
        try {
            $userObjectId = new ObjectId($userId);
            $orders = Order::where('user_id', $userObjectId)
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'orders' => $orders->map(function ($order) {
                    return [
                        'id' => (string)$order->_id,
                        'order_number' => $order->order_number,
                        'total_amount' => $order->total_amount,
                        'tax_amount' => $order->tax_amount,
                        'status' => $order->status,
                        'created_at' => $order->created_at,
                        'items' => $order->items
                    ];
                })
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching orders', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => $userId
            ]);
            return response()->json(['error' => 'Failed to fetch orders'], 500);
        }
    }

    public function store(Request $request)
    {
        $userId = Auth::id();
        Log::info('Creating new order for user', ['user_id' => $userId]);

        try {
            $userObjectId = new ObjectId($userId);
            
            // Get cart items
            $cartItems = Cart::where('user_id', $userObjectId)
                ->with(['product'])
                ->get();

            if ($cartItems->isEmpty()) {
                return response()->json(['error' => 'Cart is empty'], 400);
            }

            // Calculate totals
            $subtotal = $cartItems->sum(function ($item) {
                return $item->product ? $item->product->price * $item->quantity : 0;
            });
            
            // Calculate tax (assuming 10% tax rate)
            $taxRate = 0.10;
            $taxAmount = $subtotal * $taxRate;
            $totalAmount = $subtotal + $taxAmount;

            // Create order items from cart items
            $orderItems = $cartItems->map(function ($item) {
                return [
                    'product_id' => (string)$item->product_id,
                    'product_name' => $item->product->name,
                    'quantity' => $item->quantity,
                    'price' => $item->product->price,
                    'subtotal' => $item->product->price * $item->quantity
                ];
            })->toArray();

            // Create order
            $order = Order::create([
                'user_id' => $userObjectId,
                'order_number' => Order::generateOrderNumber(),
                'items' => $orderItems,
                'total_amount' => $totalAmount,
                'tax_amount' => $taxAmount,
                'status' => 'pending',
            ]);

            // Clear cart after order creation
            Cart::where('user_id', $userObjectId)->delete();

            return response()->json([
                'message' => 'Order created successfully',
                'order' => [
                    'id' => (string)$order->_id,
                    'order_number' => $order->order_number,
                    'total_amount' => $order->total_amount,
                    'tax_amount' => $order->tax_amount,
                    'status' => $order->status,
                    'created_at' => $order->created_at,
                    'items' => $order->items
                ]
            ], 201);

        } catch (\Exception $e) {
            Log::error('Error creating order', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => $userId
            ]);
            return response()->json(['error' => 'Failed to create order'], 500);
        }
    }

    public function show($id)
    {
        $userId = Auth::id();
        Log::info('Fetching order details', ['order_id' => $id, 'user_id' => $userId]);

        try {
            $order = Order::where('_id', new ObjectId($id))
                ->where('user_id', new ObjectId($userId))
                ->first();

            if (!$order) {
                return response()->json(['error' => 'Order not found'], 404);
            }

            return response()->json([
                'order' => [
                    'id' => (string)$order->_id,
                    'order_number' => $order->order_number,
                    'total_amount' => $order->total_amount,
                    'tax_amount' => $order->tax_amount,
                    'status' => $order->status,
                    'created_at' => $order->created_at,
                    'items' => $order->items
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching order details', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'order_id' => $id,
                'user_id' => $userId
            ]);
            return response()->json(['error' => 'Failed to fetch order details'], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $userId = Auth::id();
        Log::info('Updating order status', ['order_id' => $id, 'user_id' => $userId]);

        try {
            $order = Order::where('_id', new ObjectId($id))
                ->where('user_id', new ObjectId($userId))
                ->first();

            if (!$order) {
                return response()->json(['error' => 'Order not found'], 404);
            }

            // Only allow updating the status
            if ($request->has('status')) {
                $order->status = $request->status;
                $order->save();
            }

            return response()->json([
                'message' => 'Order updated successfully',
                'order' => [
                    'id' => (string)$order->_id,
                    'order_number' => $order->order_number,
                    'status' => $order->status
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating order', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'order_id' => $id,
                'user_id' => $userId
            ]);
            return response()->json(['error' => 'Failed to update order'], 500);
        }
    }
}
