<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use MongoDB\BSON\ObjectId;

class CartController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        Log::info('Fetching cart items for user', ['user_id' => $userId]);
        
        try {
            // Convert user ID to ObjectId
            $userObjectId = new ObjectId($userId);
            
            $cartItems = Cart::where('user_id', $userObjectId)
                ->with(['product'])
                ->get();

            // Debug log for each cart item
            foreach ($cartItems as $item) {
                Log::info('Cart item details', [
                    'cart_id' => (string)$item->_id,
                    'user_id' => (string)$item->user_id,
                    'product_id' => (string)$item->product_id,
                    'quantity' => $item->quantity,
                    'product_loaded' => $item->relationLoaded('product'),
                    'product_exists' => $item->product !== null,
                    'product_details' => $item->product ? [
                        'id' => (string)$item->product->_id,
                        'name' => $item->product->name,
                        'price' => $item->product->price
                    ] : null
                ]);
            }

            $total = $cartItems->sum(function ($item) {
                return $item->product ? $item->product->price * $item->quantity : 0;
            });

            return view('cart.index', compact('cartItems', 'total'));
        } catch (\Exception $e) {
            Log::error('Error fetching cart items', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => $userId
            ]);
            return view('cart.index', [
                'cartItems' => collect(),
                'total' => 0
            ])->withErrors(['error' => 'Unable to fetch cart items. Please try again.']);
        }
    }

    public function add(Request $request)
    {
        $validatedData = $request->validate([
            'product_id' => 'required|string',
            'quantity' => 'integer|min:1|max:10'
        ]);

        $userId = Auth::id();
        Log::info('Adding product to cart - Start', [
            'user_id' => $userId,
            'product_id' => $validatedData['product_id'],
            'quantity' => $validatedData['quantity'] ?? 1
        ]);

        try {
            // Convert IDs to ObjectId
            $userObjectId = new ObjectId($userId);
            $productObjectId = new ObjectId($validatedData['product_id']);

            // Find the product first
            $product = Product::where('_id', $productObjectId)->first();
            Log::info('Product lookup result', [
                'product_id' => $validatedData['product_id'],
                'product_found' => $product !== null,
                'product_details' => $product ? [
                    'name' => $product->name,
                    'price' => $product->price,
                    'stock' => $product->stock_quantity
                ] : null
            ]);
            
            if (!$product) {
                Log::error('Product not found', ['product_id' => $validatedData['product_id']]);
                return back()->withErrors(['error' => 'Product not found']);
            }

            // Check if product is in stock
            if (!$product->inStock($validatedData['quantity'] ?? 1)) {
                Log::warning('Product out of stock', [
                    'product_id' => (string)$product->_id,
                    'requested_quantity' => $validatedData['quantity'] ?? 1,
                    'available_quantity' => $product->stock_quantity
                ]);
                return back()->withErrors(['error' => 'Product is out of stock or insufficient quantity']);
            }

            // Find existing cart item
            $cartItem = Cart::where([
                'user_id' => $userObjectId,
                'product_id' => $productObjectId
            ])->first();

            Log::info('Existing cart item check', [
                'cart_item_found' => $cartItem !== null,
                'cart_item_details' => $cartItem ? [
                    'id' => (string)$cartItem->_id,
                    'quantity' => $cartItem->quantity
                ] : null
            ]);

            if ($cartItem) {
                $cartItem->quantity = $validatedData['quantity'] ?? ($cartItem->quantity + 1);
                $cartItem->save();
                Log::info('Updated existing cart item', [
                    'cart_item_id' => (string)$cartItem->_id,
                    'new_quantity' => $cartItem->quantity
                ]);
            } else {
                $cartItem = new Cart([
                    'user_id' => $userObjectId,
                    'product_id' => $productObjectId,
                    'quantity' => $validatedData['quantity'] ?? 1
                ]);
                $cartItem->save();
                Log::info('Created new cart item', [
                    'cart_item_id' => (string)$cartItem->_id,
                    'quantity' => $cartItem->quantity
                ]);
            }

            return redirect()->route('cart.index')->with('success', 'Product added to cart');
        } catch (\Exception $e) {
            Log::error('Error adding product to cart', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => $userId,
                'product_id' => $validatedData['product_id'] ?? null
            ]);
            return back()->withErrors(['error' => 'Unable to add product to cart. Please try again.']);
        }
    }

    public function updateQuantity(Request $request, $cartId)
    {
        $validatedData = $request->validate([
            'quantity' => 'required|integer|min:1|max:10'
        ]);

        try {
            $userId = Auth::id();
            $cartItem = Cart::where('user_id', new ObjectId($userId))
                ->where('_id', new ObjectId($cartId))
                ->first();
            
            if (!$cartItem) {
                return back()->withErrors(['error' => 'Cart item not found']);
            }

            $product = $cartItem->product;
            if (!$product) {
                Log::error('Product not found for cart item', [
                    'cart_id' => $cartId,
                    'product_id' => (string)$cartItem->product_id
                ]);
                return back()->withErrors(['error' => 'Product not found']);
            }

            // Check if product is in stock
            if (!$product->inStock($validatedData['quantity'])) {
                return back()->withErrors(['error' => 'Insufficient product quantity']);
            }

            $cartItem->update(['quantity' => $validatedData['quantity']]);
            return back()->with('success', 'Cart updated successfully');
        } catch (\Exception $e) {
            Log::error('Error updating cart quantity', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'cart_id' => $cartId
            ]);
            return back()->withErrors(['error' => 'Unable to update cart. Please try again.']);
        }
    }

    public function remove($cartId)
    {
        try {
            $userId = Auth::id();
            $cartItem = Cart::where('user_id', new ObjectId($userId))
                ->where('_id', new ObjectId($cartId))
                ->first();
            
            if (!$cartItem) {
                return back()->withErrors(['error' => 'Cart item not found']);
            }

            $cartItem->delete();
            return back()->with('success', 'Product removed from cart');
        } catch (\Exception $e) {
            Log::error('Error removing cart item', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'cart_id' => $cartId
            ]);
            return back()->withErrors(['error' => 'Unable to remove item from cart. Please try again.']);
        }
    }
}
