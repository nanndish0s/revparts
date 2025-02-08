<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
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
            $userObjectId = new ObjectId($userId);
            
            $cartItems = Cart::where('user_id', $userObjectId)
                ->with(['product'])
                ->get();

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
        $quantity = $validatedData['quantity'] ?? 1;

        try {
            $userObjectId = new ObjectId($userId);
            $productObjectId = new ObjectId($validatedData['product_id']);

            $product = Product::where('_id', $productObjectId)->first();
            
            if (!$product) {
                return back()->withErrors(['error' => 'Product not found']);
            }

            if (!$product->inStock($quantity)) {
                return back()->withErrors(['error' => 'Product is out of stock or insufficient quantity']);
            }

            $existingCartItem = Cart::where('user_id', $userObjectId)
                ->where('product_id', $productObjectId)
                ->first();

            if ($existingCartItem) {
                $existingCartItem->quantity += $quantity;
                $existingCartItem->save();
            } else {
                $cartItem = new Cart();
                $cartItem->user_id = $userObjectId;
                $cartItem->product_id = $productObjectId;
                $cartItem->quantity = $quantity;
                $cartItem->save();
            }

            return redirect()->route('cart.index')->with('success', 'Product added to cart');
        } catch (\Exception $e) {
            Log::error('Error adding product to cart', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => $userId,
                'product_id' => $validatedData['product_id']
            ]);
            return back()->withErrors(['error' => 'Unable to add product to cart. Please try again.']);
        }
    }

    public function updateQuantity(Request $request, $cartItemId)
    {
        $validatedData = $request->validate([
            'quantity' => 'required|integer|min:1|max:10'
        ]);

        $userId = Auth::id();

        try {
            $cartItem = Cart::where('_id', new ObjectId($cartItemId))
                ->where('user_id', new ObjectId($userId))
                ->first();
            
            if (!$cartItem) {
                return back()->withErrors(['error' => 'Cart item not found']);
            }

            $product = Product::where('_id', $cartItem->product_id)->first();
            if (!$product->inStock($validatedData['quantity'])) {
                return back()->withErrors(['error' => 'Insufficient product quantity']);
            }

            $cartItem->quantity = $validatedData['quantity'];
            $cartItem->save();

            return back()->with('success', 'Cart updated successfully');
        } catch (\Exception $e) {
            Log::error('Error updating cart quantity', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'cart_item_id' => $cartItemId
            ]);
            return back()->withErrors(['error' => 'Unable to update cart. Please try again.']);
        }
    }

    public function remove($cartItemId)
    {
        $userId = Auth::id();

        try {
            $cartItem = Cart::where('_id', new ObjectId($cartItemId))
                ->where('user_id', new ObjectId($userId))
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
                'cart_item_id' => $cartItemId
            ]);
            return back()->withErrors(['error' => 'Unable to remove item from cart. Please try again.']);
        }
    }
}
