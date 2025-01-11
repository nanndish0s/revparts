<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
use MongoDB\BSON\ObjectId;

class Order extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'orders';

    protected $fillable = [
        'user_id',
        'total_amount',
        'tax_amount',
        'items',
        'status',
        'order_number'
    ];

    protected $casts = [
        'total_amount' => 'float',
        'tax_amount' => 'float',
        'items' => 'array'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', '_id');
    }

    public static function generateOrderNumber()
    {
        $latestOrder = self::orderBy('created_at', 'desc')->first();
        $lastNumber = $latestOrder ? intval(substr($latestOrder->order_number, 3)) : 0;
        $nextNumber = $lastNumber + 1;
        return 'ORD' . str_pad($nextNumber, 6, '0', STR_PAD_LEFT);
    }
}
