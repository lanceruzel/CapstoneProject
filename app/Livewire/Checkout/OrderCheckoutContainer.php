<?php

namespace App\Livewire\Checkout;

use Livewire\Component;

class OrderCheckoutContainer extends Component
{
    public $order = null;

    public function render()
    {
        return view('livewire.Checkout.order-checkout-container');
    }
}
