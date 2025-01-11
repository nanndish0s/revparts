<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
use MongoDB\BSON\ObjectId;
use Illuminate\Support\Facades\Log;

class Product extends Model
{
    /**
     * The connection name for the model.
     *
     * @var string
     */
    protected $connection = 'mongodb';

    /**
     * The collection associated with the model.
     *
     * @var string
     */
    protected $collection = 'products';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 
        'description', 
        'price', 
        'stock_quantity', 
        'category', 
        'product_image'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'price' => 'float',
        'stock_quantity' => 'integer'
    ];

    /**
     * The "booting" method of the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::retrieved(function ($product) {
            Log::info('Retrieved product', [
                'product_id' => (string)$product->_id,
                'name' => $product->name,
                'price' => $product->price
            ]);
        });
    }

    /**
     * Check if product is in stock
     */
    public function inStock($quantity = 1)
    {
        return $this->stock_quantity >= $quantity;
    }

    /**
     * Get the image URL
     */
    public function getImageUrl()
    {
        return $this->product_image ?? '/images/default-product.jpg';
    }

    /**
     * Get the cart items for the product.
     */
    public function cartItems()
    {
        return $this->hasMany(Cart::class, 'product_id', '_id');
    }
}
