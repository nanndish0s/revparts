@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold">Product Management</h2>
                    <a href="{{ route('admin.products.create') }}" 
                       class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">
                        Add New Product
                    </a>
                </div>

                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                @endif

                @if($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                        @foreach($errors->all() as $error)
                            <span class="block sm:inline">{{ $error }}</span>
                        @endforeach
                    </div>
                @endif

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Image</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stock</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($products as $product)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($product->product_image)
                                            <img src="{{ Storage::url($product->product_image) }}" 
                                                 alt="{{ $product->name }}"
                                                 class="h-10 w-10 rounded-full object-cover">
                                        @else
                                            <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center">
                                                <span class="text-gray-500">No img</span>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $product->name }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                            {{ Str::title(str_replace('-', ' ', $product->category)) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        ${{ number_format($product->price, 2) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $product->stock_quantity }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="{{ route('admin.products.edit', $product->_id) }}" 
                                           class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>
                                        <form action="{{ route('admin.products.destroy', $product->_id) }}" 
                                              method="POST" 
                                              class="inline-block"
                                              onsubmit="return confirm('Are you sure you want to delete this product?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="text-red-600 hover:text-red-900">
                                                Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $products->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
