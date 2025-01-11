<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use MongoDB\BSON\ObjectId;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    public function index()
    {
        try {
            $userId = Auth::id();
            $orders = Order::where('user_id', new ObjectId($userId))
                ->orderBy('created_at', 'desc')
                ->paginate(10);

            return view('orders.index', compact('orders'));
        } catch (\Exception $e) {
            Log::error('Error fetching orders', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => $userId ?? null
            ]);

            return redirect()->route('homepage')
                ->withErrors(['error' => 'Unable to fetch orders. Please try again.']);
        }
    }

    public function checkout()
    {
        try {
            $userId = Auth::id();
            $cartItems = Cart::where('user_id', new ObjectId($userId))
                ->with('product')
                ->get();

            if ($cartItems->isEmpty()) {
                return redirect()->route('cart.index')
                    ->withErrors(['error' => 'Your cart is empty']);
            }

            // Calculate totals
            $subtotal = $cartItems->sum(function ($item) {
                return $item->product ? $item->product->price * $item->quantity : 0;
            });
            $taxRate = 0.10; // 10% tax
            $taxAmount = $subtotal * $taxRate;
            $total = $subtotal + $taxAmount;

            // Create order items array
            $orderItems = $cartItems->map(function ($item) {
                return [
                    'product_id' => (string)$item->product_id,
                    'product_name' => $item->product->name,
                    'quantity' => $item->quantity,
                    'unit_price' => $item->product->price,
                    'subtotal' => $item->product->price * $item->quantity
                ];
            })->toArray();

            // Create order
            $order = Order::create([
                'user_id' => new ObjectId($userId),
                'total_amount' => $total,
                'tax_amount' => $taxAmount,
                'items' => $orderItems,
                'status' => 'confirmed',
                'order_number' => Order::generateOrderNumber()
            ]);

            // Clear cart after successful order
            Cart::where('user_id', new ObjectId($userId))->delete();

            return view('orders.confirmation', [
                'order' => $order,
                'cartItems' => $cartItems,
                'subtotal' => $subtotal,
                'taxAmount' => $taxAmount,
                'total' => $total
            ]);

        } catch (\Exception $e) {
            Log::error('Error processing checkout', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => $userId ?? null
            ]);

            return redirect()->route('cart.index')
                ->withErrors(['error' => 'Unable to process your order. Please try again.']);
        }
    }

    public function show(string $orderNumber)
    {
        try {
            $userId = Auth::id();
            $order = Order::where('order_number', $orderNumber)
                ->where('user_id', new ObjectId($userId))
                ->firstOrFail();

            return view('orders.show', compact('order'));
        } catch (\Exception $e) {
            return redirect()->route('homepage')
                ->withErrors(['error' => 'Order not found.']);
        }
    }

    /**
     * Display a listing of all orders for admin
     */
    public function adminIndex()
    {
        try {
            $orders = Order::with(['user'])
                ->orderBy('created_at', 'desc')
                ->paginate(15);

            return view('admin.orders.index', compact('orders'));
        } catch (\Exception $e) {
            Log::error('Error fetching admin orders', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->route('admin.dashboard')
                ->withErrors(['error' => 'Unable to fetch orders. Please try again.']);
        }
    }

    /**
     * Display the specified order for admin
     */
    public function adminShow(Order $order)
    {
        return view('admin.orders.show', compact('order'));
    }

    /**
     * Update order status
     */
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled'
        ]);

        try {
            $order->status = $request->status;
            $order->save();

            return redirect()->back()
                ->with('success', 'Order status updated successfully');
        } catch (\Exception $e) {
            Log::error('Error updating order status', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'order_id' => $order->_id
            ]);

            return redirect()->back()
                ->withErrors(['error' => 'Unable to update order status. Please try again.']);
        }
    }
}
