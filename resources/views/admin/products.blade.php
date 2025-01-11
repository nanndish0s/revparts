<x-app-layout>
    @php
        function formatPrice($price) {
            return 'LKR ' . number_format($price, 2);
        }
    @endphp

    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Product Management') }}
            </h2>
            <a href="{{ route('admin.dashboard') }}" class="text-blue-500 hover:underline">
                Back to Dashboard
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Success Message -->
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Products Table -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <table class="w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Image</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stock</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($products as $product)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($product->product_image)
                                            <img src="{{ asset('storage/' . $product->product_image) }}" 
                                                 alt="{{ $product->name }}" 
                                                 class="h-16 w-16 object-cover rounded-lg">
                                        @else
                                            <div class="h-16 w-16 bg-gray-200 rounded-lg flex items-center justify-center text-gray-500">
                                                No Image
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $product->name }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            @if($product->category == 'engine-parts') bg-blue-100 text-blue-800
                                            @elseif($product->category == 'brake-system') bg-red-100 text-red-800
                                            @elseif($product->category == 'electrical') bg-yellow-100 text-yellow-800
                                            @elseif($product->category == 'suspension') bg-green-100 text-green-800
                                            @elseif($product->category == 'transmission') bg-purple-100 text-purple-800
                                            @endif">
                                            {{ ucfirst(str_replace('-', ' ', $product->category)) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ formatPrice($product->price) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm 
                                        {{ $product->stock_quantity > 10 ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $product->stock_quantity }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex justify-end space-x-2">
                                            <a href="{{ route('admin.products.edit', $product->_id) }}" class="text-indigo-600 hover:text-indigo-900">
                                                Edit
                                            </a>
                                            <form action="{{ route('admin.products.destroy', $product->_id) }}" method="POST" class="inline-block">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900" 
                                                    onclick="return confirm('Are you sure you want to delete this product?')">
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                        No products found. 
                                        <a href="{{ route('admin.dashboard') }}" class="text-blue-500 hover:underline">
                                            Create your first product
                                        </a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $products->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
