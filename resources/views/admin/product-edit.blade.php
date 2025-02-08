@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto bg-white shadow-md rounded-lg overflow-hidden">
        <div class="p-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Edit Product: {{ $product->name }}</h2>
            
            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.products.update', $product->_id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Left Column --}}
                    <div>
                        {{-- Product Name --}}
                        <div class="mb-4">
                            <label for="name" class="block text-gray-700 font-bold mb-2">Product Name</label>
                            <input type="text" name="name" id="name" 
                                   value="{{ old('name', $product->name) }}" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" 
                                   required>
                        </div>

                        {{-- Description --}}
                        <div class="mb-4">
                            <label for="description" class="block text-gray-700 font-bold mb-2">Description</label>
                            <textarea name="description" id="description" 
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" 
                                      rows="4">{{ old('description', $product->description) }}</textarea>
                        </div>

                        {{-- Category --}}
                        <div class="mb-4">
                            <label for="category" class="block text-gray-700 font-bold mb-2">Category</label>
                            <select name="category" id="category" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" 
                                    required>
                                <option value="">Select Category</option>
                                @php
                                    $categories = [
                                        'engine-parts' => 'Engine Parts', 
                                        'brake-system' => 'Brake System', 
                                        'electrical' => 'Electrical', 
                                        'suspension' => 'Suspension', 
                                        'transmission' => 'Transmission'
                                    ];
                                @endphp
                                @foreach($categories as $value => $label)
                                    <option value="{{ $value }}" 
                                            {{ old('category', $product->category) == $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Right Column --}}
                    <div>
                        {{-- Price --}}
                        <div class="mb-4">
                            <label for="price" class="block text-gray-700 font-bold mb-2">Price (LKR)</label>
                            <input type="number" name="price" id="price" 
                                   value="{{ old('price', $product->price) }}" 
                                   step="0.01" min="0" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" 
                                   required>
                        </div>

                        {{-- Stock Quantity --}}
                        <div class="mb-4">
                            <label for="stock_quantity" class="block text-gray-700 font-bold mb-2">Stock Quantity</label>
                            <input type="number" name="stock_quantity" id="stock_quantity" 
                                   value="{{ old('stock_quantity', $product->stock_quantity) }}" 
                                   min="0" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" 
                                   required>
                        </div>

                        {{-- Product Image --}}
                        <div class="mb-4">
                            <label for="product_image" class="block text-gray-700 font-bold mb-2">Product Image</label>
                            <input type="file" name="product_image" id="product_image" 
                                   accept="image/*" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            
                            @if($product->product_image)
                                <div class="mt-4">
                                    <p class="text-sm text-gray-600 mb-2">Current Image:</p>
                                    <img src="{{ asset('storage/' . $product->product_image) }}" 
                                         alt="{{ $product->name }}" 
                                         class="h-48 w-48 object-cover rounded-lg shadow-md">
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Submit Button --}}
                <div class="flex justify-end mt-6">
                    <button type="submit" 
                            class="bg-blue-500 text-white px-6 py-2 rounded-md hover:bg-blue-600 transition duration-300">
                        Update Product
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
