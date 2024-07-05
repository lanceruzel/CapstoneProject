<?php

namespace App\Livewire\Checkout\Orders;

use Livewire\Component;

class OrderCheckoutContainer extends Component
{
    public $order = null;

    public function render()
    {
        return view('livewire.Checkout.Orders.order-checkout-container');
    }
}
