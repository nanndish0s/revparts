<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit Product') }}: {{ $product->name }}
            </h2>
            <div class="space-x-2">
                <a href="{{ route('admin.products.index') }}" class="text-blue-500 hover:underline">
                    Back to Products
                </a>
                <a href="{{ route('admin.dashboard') }}" class="text-green-500 hover:underline">
                    Dashboard
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form action="{{ route('admin.products.update', $product->_id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Left Column -->
                        <div>
                            <!-- Product Name -->
                            <div class="mb-4">
                                <x-input-label for="name" :value="__('Product Name')" />
                                <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" 
                                    value="{{ old('name', $product->name) }}" required />
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>

                            <!-- Description -->
                            <div class="mb-4">
                                <x-input-label for="description" :value="__('Description')" />
                                <textarea id="description" name="description" 
                                    class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" 
                                    rows="4">{{ old('description', $product->description) }}</textarea>
                                <x-input-error :messages="$errors->get('description')" class="mt-2" />
                            </div>

                            <!-- Category -->
                            <div class="mb-4">
                                <x-input-label for="category" :value="__('Category')" />
                                <select id="category" name="category" 
                                    class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" 
                                    required>
                                    <option value="">Select Category</option>
                                    <option value="engine-parts" 
                                        {{ old('category', $product->category) == 'engine-parts' ? 'selected' : '' }}>
                                        Engine Parts
                                    </option>
                                    <option value="brake-system" 
                                        {{ old('category', $product->category) == 'brake-system' ? 'selected' : '' }}>
                                        Brake System
                                    </option>
                                    <option value="electrical" 
                                        {{ old('category', $product->category) == 'electrical' ? 'selected' : '' }}>
                                        Electrical
                                    </option>
                                    <option value="suspension" 
                                        {{ old('category', $product->category) == 'suspension' ? 'selected' : '' }}>
                                        Suspension
                                    </option>
                                    <option value="transmission" 
                                        {{ old('category', $product->category) == 'transmission' ? 'selected' : '' }}>
                                        Transmission
                                    </option>
                                </select>
                                <x-input-error :messages="$errors->get('category')" class="mt-2" />
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div>
                            <!-- Price -->
                            <div class="mb-4">
                                <x-input-label for="price" :value="__('Price (LKR)')" />
                                <x-text-input id="price" class="block mt-1 w-full" type="number" 
                                    name="price" value="{{ old('price', $product->price) }}" 
                                    step="0.01" min="0" required />
                                <x-input-error :messages="$errors->get('price')" class="mt-2" />
                            </div>

                            <!-- Stock Quantity -->
                            <div class="mb-4">
                                <x-input-label for="stock_quantity" :value="__('Stock Quantity')" />
                                <x-text-input id="stock_quantity" class="block mt-1 w-full" type="number" 
                                    name="stock_quantity" value="{{ old('stock_quantity', $product->stock_quantity) }}" 
                                    min="0" required />
                                <x-input-error :messages="$errors->get('stock_quantity')" class="mt-2" />
                            </div>

                            <!-- Product Image -->
                            <div class="mb-4">
                                <x-input-label for="product_image" :value="__('Product Image')" />
                                <input id="product_image" type="file" name="product_image" 
                                    class="block mt-1 w-full" accept="image/*" />
                                <x-input-error :messages="$errors->get('product_image')" class="mt-2" />

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

                    <!-- Submit Button -->
                    <div class="flex items-center justify-end mt-4">
                        <x-primary-button class="ms-4">
                            {{ __('Update Product') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
