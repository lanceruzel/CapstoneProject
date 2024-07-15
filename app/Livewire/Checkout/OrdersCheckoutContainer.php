<?php

namespace App\Livewire\Checkout;

use App\Models\CartItem;
use Livewire\Component;

class OrdersCheckoutContainer extends Component
{
    public function render()
    {
        return view('livewire.Checkout.orders-checkout-container', [
            'checkedOutSellers' => CartItem::groupBySellerCheckout(),
        ]);
    }
}
