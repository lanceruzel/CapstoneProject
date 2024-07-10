<?php

namespace App\Livewire\Order;

use App\Enums\Status;
use App\Models\ProductFeedback;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class OrderContainer extends Component
{
    public $order;

    public $orderedProducts = [];

    protected $listeners = [
        'refresh-order-container' => 'refreshOrderContainer'
    ];

    public function mount($order = null){
        $this->order = $order;

        if($order){
            $this->orderedProducts = $order->orderedItems;
        }
    }

    public function receivedOrder(){
        $this->order->status = Status::OrderBuyerReceived;
        $this->order->save();
    }

    public function refreshOrderContainer($id)
    {
        if ($this->order->id == $id) {
            $this->mount($this->order); 
        }
    }

    public function render()
    {
        return view('livewire.Order.order-container');
    }
}
