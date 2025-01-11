<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
use MongoDB\BSON\ObjectId;
use Illuminate\Support\Facades\Log;

class Cart extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'carts';
    
    protected $fillable = [
        'user_id',
        'product_id',
        'quantity',
    ];

    protected $with = ['product']; // Always eager load product

    protected $casts = [
        'quantity' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($cart) {
            try {
                if (!$cart->user_id instanceof ObjectId) {
                    $cart->user_id = new ObjectId($cart->user_id);
                }
                if (!$cart->product_id instanceof ObjectId) {
                    $cart->product_id = new ObjectId($cart->product_id);
                }

                // Verify the product exists
                $product = Product::find($cart->product_id);
                if (!$product) {
                    throw new \Exception('Product not found: ' . $cart->product_id);
                }

                Log::info('Creating cart item', [
                    'user_id' => (string)$cart->user_id,
                    'product_id' => (string)$cart->product_id,
                    'product_exists' => $product !== null
                ]);
            } catch (\Exception $e) {
                Log::error('Error converting IDs to ObjectId', [
                    'error' => $e->getMessage(),
                    'user_id' => $cart->user_id,
                    'product_id' => $cart->product_id
                ]);
                throw $e;
            }
        });

        static::retrieved(function ($cart) {
            Log::info('Retrieved cart item', [
                'cart_id' => (string)$cart->_id,
                'product_id' => (string)$cart->product_id,
                'product_loaded' => $cart->relationLoaded('product'),
                'product_exists' => $cart->product !== null
            ]);
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', '_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', '_id');
    }

    // Calculate total price of cart items
    public function getTotalPriceAttribute()
    {
        if (!$this->product) {
            Log::warning('Product not found for cart item', [
                'cart_id' => (string)$this->_id,
                'product_id' => (string)$this->product_id
            ]);
            return 0;
        }
        return $this->product->price * $this->quantity;
    }
}
