<?php

namespace App\Livewire\Cart;

use App\Models\CartItem;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class CartItemsContainer extends Component
{
    protected $listeners = [
        'refresh-cartContainer' => '$refresh',
    ];

    public function render()
    {
        return view('livewire.Cart.cart-items-container', [
            'products' => CartItem::where('user_id', Auth::id())->get()
        ]);
    }
}
