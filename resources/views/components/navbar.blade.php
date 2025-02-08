<nav class="bg-white shadow-md" x-data="{ open: false }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <!-- Logo -->
            <div class="flex items-center">
                <a href="{{ route('homepage') }}" class="flex items-center">
                    <span class="text-xl font-bold text-indigo-600">RevParts</span>
                </a>
            </div>

            <!-- Navigation Links -->
            <div class="hidden sm:flex items-center space-x-4">
                <a href="{{ route('homepage') }}" 
                   class="text-gray-700 hover:text-indigo-600 px-3 py-2 rounded-md text-sm font-medium transition duration-300 ease-in-out
                   {{ request()->routeIs('homepage') ? 'text-indigo-600' : '' }}">
                    Home
                </a>
                <a href="{{ route('products.index') }}" 
                   class="text-gray-700 hover:text-indigo-600 px-3 py-2 rounded-md text-sm font-medium transition duration-300 ease-in-out
                   {{ request()->routeIs('products.*') ? 'text-indigo-600' : '' }}">
                    Products
                </a>
                @auth
                    <a href="{{ route('cart.index') }}" 
                       class="text-gray-700 hover:text-indigo-600 px-3 py-2 rounded-md text-sm font-medium transition duration-300 ease-in-out
                       {{ request()->routeIs('cart.*') ? 'text-indigo-600' : '' }}">
                        Cart
                    </a>
                    <a href="{{ route('orders.index') }}" 
                       class="text-gray-700 hover:text-indigo-600 px-3 py-2 rounded-md text-sm font-medium transition duration-300 ease-in-out
                       {{ request()->routeIs('orders.*') ? 'text-indigo-600' : '' }}">
                        My Orders
                    </a>
                    @if(Auth::user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}" 
                           class="text-gray-700 hover:text-indigo-600 px-3 py-2 rounded-md text-sm font-medium transition duration-300 ease-in-out
                           {{ request()->routeIs('admin.*') ? 'text-indigo-600' : '' }}">
                            Admin Dashboard
                        </a>
                    @endif
                @endauth
            </div>

            <!-- Auth Links -->
            <div class="hidden sm:flex items-center space-x-4">
                @auth
                    <div class="flex items-center space-x-3">
                        <span class="text-gray-700">Welcome, {{ Auth::user()->name }}</span>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-gray-700 hover:text-indigo-600 px-3 py-2 rounded-md text-sm font-medium transition duration-300 ease-in-out">
                                Logout
                            </button>
                        </form>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="text-gray-700 hover:text-indigo-600 px-3 py-2 rounded-md text-sm font-medium transition duration-300 ease-in-out">
                        Login
                    </a>
                    <a href="{{ route('register') }}" class="bg-indigo-600 text-white hover:bg-indigo-700 px-4 py-2 rounded-md text-sm font-medium transition duration-300 ease-in-out">
                        Register
                    </a>
                @endauth
            </div>

            <!-- Mobile menu button -->
            <div class="flex items-center sm:hidden">
                <button @click="open = !open" 
                        class="inline-flex items-center justify-center p-2 rounded-md text-gray-700 hover:text-indigo-600 focus:outline-none">
                    <svg class="h-6 w-6" x-show="!open" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    <svg class="h-6 w-6" x-show="open" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile menu -->
    <div class="sm:hidden" x-show="open">
        <div class="px-2 pt-2 pb-3 space-y-1">
            <a href="{{ route('homepage') }}" 
               class="block text-gray-700 hover:text-indigo-600 px-3 py-2 rounded-md text-base font-medium
               {{ request()->routeIs('homepage') ? 'text-indigo-600' : '' }}">
                Home
            </a>
            <a href="{{ route('products.index') }}" 
               class="block text-gray-700 hover:text-indigo-600 px-3 py-2 rounded-md text-base font-medium
               {{ request()->routeIs('products.*') ? 'text-indigo-600' : '' }}">
                Products
            </a>
            @auth
                @if(Auth::user()->role === 'admin')
                    <a href="{{ route('admin.dashboard') }}" 
                       class="block text-gray-700 hover:text-indigo-600 px-3 py-2 rounded-md text-base font-medium
                       {{ request()->routeIs('admin.*') ? 'text-indigo-600' : '' }}">
                        Admin Dashboard
                    </a>
                @endif
                <a href="{{ route('cart.index') }}" 
                   class="block text-gray-700 hover:text-indigo-600 px-3 py-2 rounded-md text-base font-medium
                   {{ request()->routeIs('cart.*') ? 'text-indigo-600' : '' }}">
                    Cart
                </a>
                <a href="{{ route('orders.index') }}" 
                   class="block text-gray-700 hover:text-indigo-600 px-3 py-2 rounded-md text-base font-medium
                   {{ request()->routeIs('orders.*') ? 'text-indigo-600' : '' }}">
                    My Orders
                </a>
                <div class="px-3 py-2">
                    <span class="block text-gray-700 font-medium mb-2">Welcome, {{ Auth::user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left text-gray-700 hover:text-indigo-600 py-2 text-base font-medium transition duration-300 ease-in-out">
                            Logout
                        </button>
                    </form>
                </div>
            @else
                <a href="{{ route('login') }}" 
                   class="block text-gray-700 hover:text-indigo-600 px-3 py-2 rounded-md text-base font-medium">
                    Login
                </a>
                <a href="{{ route('register') }}" 
                   class="block bg-indigo-600 text-white hover:bg-indigo-700 px-3 py-2 rounded-md text-base font-medium">
                    Register
                </a>
            @endauth
        </div>
    </div>
</nav>
