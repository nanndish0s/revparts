@extends('layouts.app')

@section('content')
    @if($product)
        <div class="container mx-auto px-4 py-12">
            <div class="max-w-6xl mx-auto">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="flex flex-col md:flex-row">
                        <!-- Left Side - Image -->
                        <div class="md:w-1/2 p-8">
                            @if($product->product_image)
                                <img src="{{ asset('storage/' . $product->product_image) }}" 
                                     alt="{{ $product->name }}" 
                                     class="w-full h-[500px] object-cover rounded-lg shadow-md">
                            @else
                                <div class="w-full h-[500px] bg-gray-200 flex items-center justify-center text-gray-500 rounded-lg">
                                    No Image Available
                                </div>
                            @endif
                        </div>
                        
                        <!-- Right Side - Details -->
                        <div class="md:w-1/2 p-8 bg-gray-50">
                            <span class="inline-block px-3 py-1 bg-indigo-600 text-white text-sm rounded-full mb-4">
                                {{ ucfirst(str_replace('-', ' ', $product->category)) }}
                            </span>
                            
                            <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $product->name }}</h1>
                            
                            <p class="text-3xl font-bold text-indigo-600 mb-6">
                                LKR {{ number_format($product->price, 2) }}
                            </p>
                            
                            <div class="mb-6">
                                <p class="text-sm text-gray-600 mb-2">Availability:</p>
                                <p class="{{ $product->stock_quantity > 10 ? 'text-green-600' : 'text-red-600' }} font-bold">
                                    {{ $product->stock_quantity }} Units Available
                                </p>
                            </div>
                            
                            <div class="mb-8">
                                <h3 class="text-lg font-semibold text-gray-800 mb-2">Description</h3>
                                <p class="text-gray-600">{{ $product->description }}</p>
                            </div>
                            
                            <div class="space-y-4">
                                @auth
                                    @if($product->stock_quantity > 0)
                                    <form action="{{ route('cart.add') }}" method="POST" class="mb-4">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ (string)$product->_id }}">
                                        <div class="flex items-center">
                                            <label for="quantity" class="mr-2">Quantity:</label>
                                            <input type="number" name="quantity" id="quantity" 
                                                   value="1" min="1" max="{{ $product->stock_quantity }}"
                                                   class="w-20 form-input rounded border-gray-300 mr-4">
                                            
                                            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                                                Add to Cart
                                            </button>
                                        </div>
                                    </form>
                                    @endif
                                @else
                                    <p class="text-gray-600">
                                        <a href="{{ route('login') }}" class="text-blue-500 hover:underline">Login</a> 
                                        to add this item to your cart
                                    </p>
                                @endauth
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="py-12">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-8 text-center">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">Product Not Found</h2>
                    <p class="text-gray-600 mb-6">
                        The product you are looking for does not exist or has been removed.
                    </p>
                    <a href="{{ route('products.index') }}" class="btn btn-primary bg-indigo-600 text-white px-6 py-2 rounded-md hover:bg-indigo-700">
                        Back to Products
                    </a>
                </div>
            </div>
        </div>
    @endif
@endsection