<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Cart;

class CartItem extends Component
{
    public $cartItem;
    public $quantity;

    public function mount(Cart $cartItem)
    {
        $this->cartItem = $cartItem;
        $this->quantity = $cartItem->quantity;
    }

    public function updateQuantity()
    {
        if ($this->quantity < 1) {
            $this->quantity = 1;
        }

        $this->cartItem->update([
            'quantity' => $this->quantity
        ]);

        $this->dispatch('cart-updated');
    }

    public function removeItem()
    {
        $this->cartItem->delete();
        $this->dispatch('cart-updated');
        return redirect()->route('cart.index')->with('success', 'Item removed from cart');
    }

    public function render()
    {
        return view('livewire.cart-item');
    }
}
