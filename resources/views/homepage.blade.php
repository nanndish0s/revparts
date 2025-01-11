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
                <h2 class="text-3xl font-extrabold text-gray-900">Featured Categories</h2>
                <p class="mt-2 text-lg text-gray-600">Explore our most popular automotive parts</p>
            </div>
            
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                @php
                    $categories = [
                        [
                            'name' => 'Engine Parts',
                            'icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253',
                            'route' => 'products.index',
                            'params' => ['category' => 'engine-parts']
                        ],
                        [
                            'name' => 'Brake System',
                            'icon' => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z',
                            'route' => 'products.index',
                            'params' => ['category' => 'brake-system']
                        ],
                        [
                            'name' => 'Electrical',
                            'icon' => 'M13 10V3L4 14h7v7l9-11h-7z',
                            'route' => 'products.index',
                            'params' => ['category' => 'electrical']
                        ],
                        [
                            'name' => 'Suspension',
                            'icon' => 'M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z',
                            'route' => 'products.index',
                            'params' => ['category' => 'suspension']
                        ]
                    ];
                @endphp

                @foreach($categories as $category)
                    <a href="{{ route($category['route'], $category['params']) }}" 
                       class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition duration-300 transform hover:-translate-y-1">
                        <div class="text-center">
                            <div class="mx-auto h-12 w-12 text-indigo-600">
                                <svg class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $category['icon'] }}"/>
                                </svg>
                            </div>
                            <h3 class="mt-4 text-lg font-medium text-gray-900">{{ $category['name'] }}</h3>
                            <p class="mt-2 text-sm text-gray-500">
                                Browse our selection of {{ strtolower($category['name']) }}
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
