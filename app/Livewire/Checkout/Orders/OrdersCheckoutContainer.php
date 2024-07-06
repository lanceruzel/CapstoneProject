<?php

namespace App\Livewire\Checkout\Orders;

use App\Models\CartItem;
use Livewire\Component;

class OrdersCheckoutContainer extends Component
{
    public function render()
    {
        return view('livewire.Checkout.Orders.orders-checkout-container', [
            'checkedOutSellers' => CartItem::groupBySellerCheckout(),
        ]);
    }
}
