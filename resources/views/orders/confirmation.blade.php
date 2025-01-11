@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-3xl mx-auto">
        <!-- Success Message -->
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6" role="alert">
            <strong class="font-bold">Order Confirmed!</strong>
            <p class="block sm:inline">Your order number is: {{ $order->order_number }}</p>
        </div>

        <!-- Receipt Card -->
        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            <!-- Receipt Header -->
            <div class="bg-gray-800 text-white p-4">
                <h2 class="text-2xl font-bold text-center">Order Receipt</h2>
            </div>

            <!-- Order Info -->
            <div class="p-6">
                <div class="mb-6">
                    <div class="flex justify-between mb-2">
                        <span class="text-gray-600">Order Number:</span>
                        <span class="font-semibold">{{ $order->order_number }}</span>
                    </div>
                    <div class="flex justify-between mb-2">
                        <span class="text-gray-600">Date:</span>
                        <span>{{ $order->created_at->format('F j, Y h:i A') }}</span>
                    </div>
                </div>

                <!-- Order Items -->
                <div class="border-t border-b border-gray-200 py-4 mb-6">
                    <h3 class="font-semibold mb-3">Order Items</h3>
                    @foreach($order->items as $item)
                        <div class="flex justify-between items-center mb-2">
                            <div>
                                <span class="font-medium">{{ $item['product_name'] }}</span>
                                <span class="text-gray-600 text-sm block">
                                    {{ $item['quantity'] }} x LKR {{ number_format($item['unit_price'], 2) }}
                                </span>
                            </div>
                            <span class="font-medium">
                                LKR {{ number_format($item['subtotal'], 2) }}
                            </span>
                        </div>
                    @endforeach
                </div>

                <!-- Order Summary -->
                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Subtotal</span>
                        <span>LKR {{ number_format($subtotal, 2) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Tax (10%)</span>
                        <span>LKR {{ number_format($taxAmount, 2) }}</span>
                    </div>
                    <div class="flex justify-between font-bold text-lg pt-2 border-t">
                        <span>Total</span>
                        <span>LKR {{ number_format($total, 2) }}</span>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="bg-gray-50 px-6 py-4">
                <div class="text-center text-gray-600">
                    <p class="mb-2">Thank you for your order!</p>
                    <p class="text-sm">A confirmation email will be sent to your registered email address.</p>
                </div>
                <div class="mt-4 text-center">
                    <a href="{{ route('products.index') }}" 
                       class="inline-block bg-blue-500 text-white px-6 py-2 rounded hover:bg-blue-600">
                        Continue Shopping
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
