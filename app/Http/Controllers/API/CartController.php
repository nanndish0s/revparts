<?php

namespace App\Http\Controllers\Api;

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

            return response()->json([
                'cart_items' => $cartItems->map(function ($item) {
                    return [
                        'id' => (string)$item->_id,
                        'product_id' => (string)$item->product_id,
                        'quantity' => $item->quantity,
                        'product' => $item->product ? [
                            'id' => (string)$item->product->_id,
                            'name' => $item->product->name,
                            'price' => $item->product->price
                        ] : null
                    ];
                }),
                'total' => $total
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching cart items', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => $userId
            ]);
            return response()->json([
                'error' => 'Unable to fetch cart items',
                'message' => $e->getMessage()
            ], 500);
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
                return response()->json([
                    'error' => 'Product not found',
                    'product_id' => $validatedData['product_id']
                ], 404);
            }

            if (!$product->inStock($quantity)) {
                return response()->json([
                    'error' => 'Insufficient stock',
                    'product_id' => (string)$product->_id,
                    'available_stock' => $product->stock_quantity
                ], 400);
            }

            $existingCartItem = Cart::where('user_id', $userObjectId)
                ->where('product_id', $productObjectId)
                ->first();

            if ($existingCartItem) {
                $existingCartItem->quantity += $quantity;
                $existingCartItem->save();

                return response()->json([
                    'message' => 'Cart item updated',
                    'cart_item' => [
                        'id' => (string)$existingCartItem->_id,
                        'product_id' => (string)$existingCartItem->product_id,
                        'quantity' => $existingCartItem->quantity
                    ]
                ]);
            }

            $cartItem = new Cart();
            $cartItem->user_id = $userObjectId;
            $cartItem->product_id = $productObjectId;
            $cartItem->quantity = $quantity;
            $cartItem->save();

            return response()->json([
                'message' => 'Product added to cart',
                'cart_item' => [
                    'id' => (string)$cartItem->_id,
                    'product_id' => (string)$cartItem->product_id,
                    'quantity' => $cartItem->quantity
                ]
            ], 201);

        } catch (\Exception $e) {
            Log::error('Error adding product to cart', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => $userId,
                'product_id' => $validatedData['product_id']
            ]);
            return response()->json([
                'error' => 'Unable to add product to cart',
                'message' => $e->getMessage()
            ], 500);
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
                return response()->json([
                    'error' => 'Cart item not found',
                    'cart_item_id' => $cartItemId
                ], 404);
            }

            $product = Product::where('_id', $cartItem->product_id)->first();
            if (!$product->inStock($validatedData['quantity'])) {
                return response()->json([
                    'error' => 'Insufficient stock',
                    'product_id' => (string)$product->_id,
                    'available_stock' => $product->stock_quantity
                ], 400);
            }

            $cartItem->quantity = $validatedData['quantity'];
            $cartItem->save();

            return response()->json([
                'message' => 'Cart item quantity updated',
                'cart_item' => [
                    'id' => (string)$cartItem->_id,
                    'product_id' => (string)$cartItem->product_id,
                    'quantity' => $cartItem->quantity
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating cart item quantity', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'cart_item_id' => $cartItemId
            ]);
            return response()->json([
                'error' => 'Unable to update cart item quantity',
                'message' => $e->getMessage()
            ], 500);
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
                return response()->json([
                    'error' => 'Cart item not found',
                    'cart_item_id' => $cartItemId
                ], 404);
            }

            $cartItem->delete();
            return response()->json([
                'message' => 'Product removed from cart',
                'cart_item_id' => $cartItemId
            ]);
        } catch (\Exception $e) {
            Log::error('Error removing cart item', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'cart_item_id' => $cartItemId
            ]);
            return response()->json([
                'error' => 'Unable to remove item from cart',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
