<div class="bg-white shadow-md rounded-lg p-4 mb-4 flex items-center">
    <div class="w-24 h-24 flex-shrink-0">
        @if($cartItem->product->product_image)
            <img src="{{ asset('storage/' . $cartItem->product->product_image) }}" 
                 alt="{{ $cartItem->product->name }}" 
                 class="w-full h-full object-cover rounded">
        @else
            <div class="w-full h-full bg-gray-200 flex items-center justify-center rounded">
                <span class="text-gray-500 text-sm">No Image</span>
            </div>
        @endif
    </div>
    
    <div class="flex-grow ml-4">
        <h2 class="text-xl font-semibold text-gray-800">{{ $cartItem->product->name }}</h2>
        <p class="text-gray-600">Unit Price: LKR {{ number_format($cartItem->product->price, 2) }}</p>
        
        <div class="flex items-center mt-2">
            <div class="flex items-center border rounded">
                <button wire:click="$set('quantity', quantity - 1)" 
                        wire:loading.attr="disabled"
                        class="px-3 py-1 bg-gray-100 hover:bg-gray-200 border-r">
                    -
                </button>
                <input type="number" 
                       wire:model.live="quantity" 
                       wire:change="updateQuantity"
                       class="w-16 px-2 py-1 text-center focus:outline-none" 
                       min="1">
                <button wire:click="$set('quantity', quantity + 1)"
                        wire:loading.attr="disabled"
                        class="px-3 py-1 bg-gray-100 hover:bg-gray-200 border-l">
                    +
                </button>
            </div>
            <button wire:click="removeItem" 
                    class="ml-4 text-red-500 hover:text-red-700">
                Remove
            </button>
        </div>
        
        <p class="mt-2 text-gray-800 font-semibold">
            Subtotal: LKR {{ number_format($cartItem->product->price * $quantity, 2) }}
        </p>
    </div>
    
    <div wire:loading wire:target="updateQuantity, removeItem" 
         class="absolute inset-0 bg-white bg-opacity-50 flex items-center justify-center">
        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-500"></div>
    </div>
</div>
