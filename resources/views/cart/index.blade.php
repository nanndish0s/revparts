@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-6">My Cart</h1>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        @if($cartItems->isEmpty())
            <div class="bg-white shadow-md rounded-lg p-6 text-center">
                <p class="text-gray-600">Your cart is empty</p>
                <a href="{{ route('products.index') }}" class="mt-4 inline-block bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                    Continue Shopping
                </a>
            </div>
        @else
            <div class="grid md:grid-cols-3 gap-6">
                <div class="md:col-span-2">
                    @foreach($cartItems as $item)
                        @if($item->product)
                            <livewire:cart-item :cartItem="$item" :key="$item->id" />
                        @endif
                    @endforeach
                </div>

                <div class="bg-white shadow-md rounded-lg p-6 h-fit">
                    <h2 class="text-2xl font-bold mb-4">Order Summary</h2>
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span>Subtotal</span>
                            <span>LKR {{ number_format($total, 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Tax (10%)</span>
                            <span>LKR {{ number_format($total * 0.1, 2) }}</span>
                        </div>
                        <hr class="my-2">
                        <div class="flex justify-between font-bold text-xl">
                            <span>Total</span>
                            <span>LKR {{ number_format($total * 1.1, 2) }}</span>
                        </div>
                    </div>
                    <form action="{{ route('checkout') }}" method="POST">
                        @csrf
                        <button type="submit" 
                                class="w-full bg-green-500 text-white py-3 rounded mt-6 hover:bg-green-600 transition-colors">
                            Proceed to Checkout
                        </button>
                    </form>
                </div>
            </div>
        @endif
    </div>
@endsection
