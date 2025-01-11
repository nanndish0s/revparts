@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold">Order Details</h2>
                    <a href="{{ route('admin.orders.index') }}" 
                       class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600">
                        Back to Orders
                    </a>
                </div>

                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                @endif

                <!-- Order Information -->
                <div class="bg-gray-50 rounded-lg p-6 mb-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-lg font-semibold mb-4">Order Information</h3>
                            <p><span class="font-medium">Order ID:</span> {{ $order->_id }}</p>
                            <p><span class="font-medium">Date:</span> {{ $order->created_at->format('M d, Y H:i') }}</p>
                            <p><span class="font-medium">Status:</span>
                                <form action="{{ route('admin.orders.update-status', $order) }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('PATCH')
                                    <select name="status" onchange="this.form.submit()" 
                                            class="ml-2 text-sm rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50
                                            {{ match($order->status) {
                                                'pending' => 'bg-yellow-100 text-yellow-800',
                                                'processing' => 'bg-blue-100 text-blue-800',
                                                'shipped' => 'bg-purple-100 text-purple-800',
                                                'delivered' => 'bg-green-100 text-green-800',
                                                'cancelled' => 'bg-red-100 text-red-800',
                                                default => 'bg-gray-100 text-gray-800'
                                            } }}">
                                        <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Processing</option>
                                        <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>Shipped</option>
                                        <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>Delivered</option>
                                        <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                    </select>
                                </form>
                            </p>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold mb-4">Customer Information</h3>
                            <p><span class="font-medium">Name:</span> {{ $order->user->name }}</p>
                            <p><span class="font-medium">Email:</span> {{ $order->user->email }}</p>
                            <p><span class="font-medium">Phone:</span> {{ $order->shipping_address['phone'] ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Shipping Address -->
                <div class="bg-gray-50 rounded-lg p-6 mb-6">
                    <h3 class="text-lg font-semibold mb-4">Shipping Address</h3>
                    <p>{{ $order->shipping_address['street'] ?? '' }}</p>
                    <p>{{ $order->shipping_address['city'] ?? '' }}, {{ $order->shipping_address['state'] ?? '' }} {{ $order->shipping_address['zip'] ?? '' }}</p>
                    <p>{{ $order->shipping_address['country'] ?? '' }}</p>
                </div>

                <!-- Order Items -->
                <div class="bg-gray-50 rounded-lg p-6">
                    <h3 class="text-lg font-semibold mb-4">Order Items</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($order->items as $item)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $item['product_name'] }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            ${{ number_format($item['unit_price'], 2) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $item['quantity'] }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            ${{ number_format($item['subtotal'], 2) }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-gray-50">
                                <tr>
                                    <td colspan="3" class="px-6 py-4 text-right font-medium">Total:</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        ${{ number_format($order->total_amount, 2) }}
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
