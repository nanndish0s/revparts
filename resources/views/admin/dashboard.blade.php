@extends('layouts.app')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Personalized Welcome Message -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6 p-6">
                <h1 class="text-2xl font-bold text-gray-900">
                    Welcome, {{ Auth::user()->name }} to Admin Dashboard
                </h1>
                <p class="text-gray-600 mt-2">
                    You have full access to manage products, users, and site settings.
                </p>
            </div>

            <!-- Success Message -->
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Quick Stats -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Total Products</h3>
                    <p class="text-3xl font-bold text-indigo-600">{{ $totalProducts ?? 0 }}</p>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Total Users</h3>
                    <p class="text-3xl font-bold text-indigo-600">{{ $totalUsers ?? 0 }}</p>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Total Orders</h3>
                    <p class="text-3xl font-bold text-indigo-600">{{ $totalOrders ?? 0 }}</p>
                </div>
            </div>

            <!-- User Statistics Card -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4">User Statistics</h3>
                <div class="space-y-2">
                    <p>Total Users: <span class="font-bold">{{ $totalUsers }}</span></p>
                    <p>Admin Users: <span class="font-bold text-blue-600">{{ $adminUsers }}</span></p>
                    <p>Regular Users: <span class="font-bold text-green-600">{{ $regularUsers }}</span></p>
                </div>
            </div>

            <!-- Recent Users Card -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4">Recent Users</h3>
                <ul class="space-y-2">
                    @foreach($recentUsers as $user)
                        <li class="flex justify-between">
                            <span>{{ $user->name }}</span>
                            <span class="text-sm text-gray-500">{{ $user->created_at->diffForHumans() }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>

            <!-- Recent Products Card -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4">Recent Products</h3>
                <ul class="space-y-2">
                    @foreach($products as $product)
                        <li class="flex justify-between items-center">
                            <div>
                                <span class="font-medium">{{ $product->name }}</span>
                                <span class="text-sm text-gray-500 block">{{ $product->category }}</span>
                            </div>
                            <span class="text-green-600 font-bold">
                                LKR {{ number_format($product->price, 2) }}
                            </span>
                        </li>
                    @endforeach
                </ul>
                <div class="mt-4 text-center">
                    <a href="{{ route('admin.products.index') }}" class="text-blue-500 hover:underline">
                        View All Products
                    </a>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mb-6">
                <h3 class="text-lg font-semibold mb-4">Quick Actions</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <a href="{{ route('admin.products.create') }}" 
                       class="inline-flex items-center justify-center px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Add New Product
                    </a>
                    <a href="{{ route('admin.user-management') }}" 
                       class="inline-flex items-center justify-center px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                        Manage Users
                    </a>
                    <a href="{{ route('admin.orders.index') }}" 
                       class="inline-flex items-center justify-center px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        View Orders
                    </a>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4">Recent Activity</h3>
                <div class="space-y-4">
                    @forelse($recentActivities ?? [] as $activity)
                        <div class="flex items-center justify-between border-b pb-4">
                            <div>
                                <p class="font-medium">{{ $activity->description }}</p>
                                <p class="text-sm text-gray-600">{{ $activity->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-600">No recent activity to display.</p>
                    @endforelse
                </div>
            </div>

            {{-- <!-- Product Creation Form -->
            <div class="mt-6 bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4">Create New Product</h3>
                <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="name" :value="__('Product Name')" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="category" :value="__('Category')" />
                            <select id="category" name="category" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                <option value="">Select Category</option>
                                <option value="engine-parts">Engine Parts</option>
                                <option value="brake-system">Brake System</option>
                                <option value="electrical">Electrical</option>
                                <option value="suspension">Suspension</option>
                                <option value="transmission">Transmission</option>
                            </select>
                            <x-input-error :messages="$errors->get('category')" class="mt-2" />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="price" :value="__('Price (LKR)')" />
                            <x-text-input id="price" class="block mt-1 w-full" type="number" name="price" :value="old('price')" step="0.01" required />
                            <x-input-error :messages="$errors->get('price')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="stock_quantity" :value="__('Stock Quantity')" />
                            <x-text-input id="stock_quantity" class="block mt-1 w-full" type="number" name="stock_quantity" :value="old('stock_quantity')" required />
                            <x-input-error :messages="$errors->get('stock_quantity')" class="mt-2" />
                        </div>
                    </div>

                    <div>
                        <x-input-label for="description" :value="__('Description')" />
                        <textarea id="description" name="description" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" rows="3">{{ old('description') }}</textarea>
                        <x-input-error :messages="$errors->get('description')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="product_image" :value="__('Product Image')" />
                        <input id="product_image" type="file" name="product_image" class="block mt-1 w-full" accept="image/*" />
                        <x-input-error :messages="$errors->get('product_image')" class="mt-2" />
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        <x-primary-button class="ms-4">
                            {{ __('Create Product') }}
                        </x-primary-button>
                    </div>
                </form>
            </div> --}}
        </div>
    </div>
@endsection