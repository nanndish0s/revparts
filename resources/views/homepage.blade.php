@extends('layouts.app')

@section('content')
    {{-- Hero Section --}}
    <header class="bg-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="text-4xl tracking-tight font-extrabold text-gray-900 sm:text-5xl md:text-6xl">
                    Find the Perfect Automotive Parts
                </h1>
                <p class="mt-3 max-w-md mx-auto text-base text-gray-500 sm:text-lg md:mt-5 md:text-xl md:max-w-3xl">
                    Quality components for every vehicle. Browse our extensive collection of reliable parts for unmatched performance.
                </p>
                <div class="mt-5 max-w-md mx-auto sm:flex sm:justify-center md:mt-8">
                    <div class="rounded-md shadow">
                        <a href="{{ route('products.index') }}" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 md:py-4 md:text-lg md:px-10">
                            Browse Products
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    {{-- Featured Categories --}}
    <section class="bg-gray-50 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-extrabold text-gray-900">Explore Our Categories</h2>
                <p class="mt-2 text-lg text-gray-600">Find the perfect parts for your vehicle</p>
            </div>
            
            <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-4">
                @php
                    $categories = [
                        [
                            'name' => 'Engine Parts',
                            'description' => 'High-performance components',
                            'route' => 'products.index',
                            'params' => ['category' => 'engine-parts'],
                            'bg_image' => '/images/categories/engine-parts.jpg'
                        ],
                        [
                            'name' => 'Brake System',
                            'description' => 'Safety and precision',
                            'route' => 'products.index',
                            'params' => ['category' => 'brake-system'],
                            'bg_image' => '/images/categories/brake-system.jpg'
                        ],
                        [
                            'name' => 'Electrical',
                            'description' => 'Advanced electrical systems',
                            'route' => 'products.index',
                            'params' => ['category' => 'electrical'],
                            'bg_image' => '/images/categories/electrical.jpg'
                        ],
                        [
                            'name' => 'Suspension',
                            'description' => 'Smooth ride technology',
                            'route' => 'products.index',
                            'params' => ['category' => 'suspension'],
                            'bg_image' => '/images/categories/suspension.jpg'
                        ]
                    ];
                @endphp

                @foreach($categories as $category)
                    <a href="{{ route($category['route'], $category['params']) }}" 
                       class="relative overflow-hidden rounded-xl shadow-lg transform transition duration-300 hover:scale-105 hover:shadow-xl group">
                        <div class="absolute inset-0 bg-black bg-opacity-40 group-hover:bg-opacity-50 transition duration-300"></div>
                        <img src="{{ $category['bg_image'] }}" 
                             alt="{{ $category['name'] }}" 
                             class="w-full h-64 object-cover absolute inset-0 z-0">
                        <div class="relative z-10 p-6 text-white text-center h-64 flex flex-col justify-center">
                            <h3 class="text-2xl font-bold mb-2 transform transition duration-300 group-hover:scale-105">
                                {{ $category['name'] }}
                            </h3>
                            <p class="text-sm opacity-0 group-hover:opacity-100 transition duration-300">
                                {{ $category['description'] }}
                            </p>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Why Choose Us --}}
    <section class="bg-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-extrabold text-gray-900">Why Choose Us</h2>
                <p class="mt-2 text-lg text-gray-600">We offer the best automotive parts with unmatched service</p>
            </div>

            <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3">
                <div class="text-center">
                    <div class="mx-auto h-12 w-12 text-indigo-600">
                        <svg class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <h3 class="mt-4 text-lg font-medium text-gray-900">Quality Guaranteed</h3>
                    <p class="mt-2 text-base text-gray-500">All our parts meet or exceed OEM specifications</p>
                </div>

                <div class="text-center">
                    <div class="mx-auto h-12 w-12 text-indigo-600">
                        <svg class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="mt-4 text-lg font-medium text-gray-900">Fast Shipping</h3>
                    <p class="mt-2 text-base text-gray-500">Quick delivery to your doorstep</p>
                </div>

                <div class="text-center">
                    <div class="mx-auto h-12 w-12 text-indigo-600">
                        <svg class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                    </div>
                    <h3 class="mt-4 text-lg font-medium text-gray-900">Expert Support</h3>
                    <p class="mt-2 text-base text-gray-500">24/7 customer service to assist you</p>
                </div>
            </div>
        </div>
    </section>
@endsection
