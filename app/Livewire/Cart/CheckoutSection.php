<?php

namespace App\Livewire\Cart;

use App\Models\CartItem;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class CheckoutSection extends Component
{
    protected $listeners = [
        'update-totalCheckout' => '$refresh'
    ];

    public function getTotal(){
        $total = 0;

        $cartItems = CartItem::where('user_id', Auth::id())->where('for_checkout', true)->get();

        foreach($cartItems as $cartItem){
            $variations = json_decode($cartItem->product->variations);

            foreach($variations as $variation){
                if($variation->name == $cartItem->variation){
                    $total += ($variation->price * $cartItem->quantity);
                }
            }
        }

        return $total;
    }

    public function render()
    {
        return view('livewire.Cart.checkout-section', [
            'total' => $this->getTotal()
        ]);
    }
}
