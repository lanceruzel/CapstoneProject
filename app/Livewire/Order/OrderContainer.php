<?php

namespace App\Livewire\Order;

use Livewire\Component;

class OrderContainer extends Component
{
    public $order;

    public $orderedProducts = [];

    public function mount($order = null){
        $this->order = $order;

        if($order){
            $this->orderedProducts = $order->orderedItems;
        }
    }

    public function render()
    {
        return view('livewire.Order.order-container');
    }
}
