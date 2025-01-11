<div class="container mx-auto px-4">
    <!-- Filters Section -->
    <div class="mb-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-4">
            <!-- Search Input -->
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700">Search Products</label>
                <input 
                    type="text" 
                    wire:model.live="search" 
                    placeholder="Search by name or description" 
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                >
            </div>

            <!-- Category Filter -->
            <div>
                <label for="category" class="block text-sm font-medium text-gray-700">Category</label>
                <select 
                    wire:model.live="category" 
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                >
                    <option value="">All Categories</option>
                    @foreach($categories as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Sort Options -->
            <div>
                <label for="sort" class="block text-sm font-medium text-gray-700">Sort By</label>
                <select 
                    wire:model.live="sortOption" 
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                >
                    <option value="default">Most Recent</option>
                    <option value="name_asc">Name (A-Z)</option>
                    <option value="name_desc">Name (Z-A)</option>
                    <option value="price_asc">Price (Low to High)</option>
                    <option value="price_desc">Price (High to Low)</option>
                </select>
            </div>
        </div>

        <!-- Clear Filters Button -->
        @if($search || $category || $sortOption !== 'default')
            <div class="flex justify-end">
                <button 
                    wire:click="clearFilters" 
                    class="inline-flex items-center px-4 py-2 bg-gray-100 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                >
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    Clear Filters
                </button>
            </div>
        @endif
    </div>

    <!-- Products Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($products as $product)
            <div class="bg-white overflow-hidden shadow-lg rounded-lg transition-all duration-300 hover:shadow-xl flex flex-col">
                <div class="relative">
                    @if($product->product_image)
                        <img src="{{ asset('storage/' . $product->product_image) }}" 
                             alt="{{ $product->name }}" 
                             class="w-full h-64 object-cover">
                    @else
                        <div class="w-full h-64 bg-gray-200 flex items-center justify-center text-gray-500">
                            No Image Available
                        </div>
                    @endif
                    
                    <!-- Category Badge -->
                    <span class="absolute top-4 right-4 px-3 py-1 bg-indigo-600 text-white text-xs rounded-full">
                        @php
                            $categoryLabels = [
                                'engine-parts' => 'Engine Parts',
                                'brake-system' => 'Brake System',
                                'electrical' => 'Electrical',
                                'suspension' => 'Suspension',
                                'transmission' => 'Transmission'
                            ];
                        @endphp
                        {{ $categoryLabels[$product->category] ?? ucfirst($product->category) }}
                    </span>
                </div>

                <div class="p-6 flex-grow flex flex-col">
                    <h2 class="text-xl font-bold text-gray-900 mb-2 truncate">
                        {{ $product->name }}
                    </h2>

                    <p class="text-gray-600 mb-4 line-clamp-2 flex-grow">
                        {{ $product->description }}
                    </p>

                    <div class="flex justify-between items-center mt-auto">
                        <span class="text-lg font-bold text-indigo-600">
                            LKR {{ number_format($product->price, 2) }}
                        </span>

                        <a href="{{ route('products.show', $product->_id) }}" 
                           class="btn btn-sm bg-white text-black border border-gray-300 px-3 py-1 rounded-md hover:bg-gray-100">
                            View Details
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-3 text-center py-12 bg-white rounded-lg shadow-md">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">No Products Found</h2>
                <p class="text-gray-600 mb-6">
                    @if($search || $category)
                        There are no products matching your current filters.
                        <br>
                        <button wire:click="$set('search', '')" wire:click="$set('category', '')" class="text-indigo-600 hover:underline mt-2">
                            Clear all filters
                        </button>
                    @else
                        We don't have any products available at the moment.
                    @endif
                </p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($products->hasPages())
        <div class="mt-8">
            {{ $products->links() }}
        </div>
    @endif
</div>