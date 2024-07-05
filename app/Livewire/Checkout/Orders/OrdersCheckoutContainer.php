<?php

namespace App\Livewire\Checkout\Orders;

use App\Models\CartItem;
use Livewire\Component;

class OrdersCheckoutContainer extends Component
{
    public function render()
    {
        $carItem = CartItem::groupBySellerCheckout();

        return view('livewire.Checkout.Orders.orders-checkout-container', [
            'orders' => $carItem['orders'],
            'totalPrices' =>  $carItem['totalPrices']
        ]);
    }
}
