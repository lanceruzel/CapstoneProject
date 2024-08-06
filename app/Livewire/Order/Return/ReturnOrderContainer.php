<?php

namespace App\Livewire\Order\Return;

use App\Enums\Status;
use App\Livewire\Pages\ReturnProducts;
use App\Models\Product;
use App\Models\ReturnRequest;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ReturnOrderContainer extends Component
{
    public $order;
    public $orderedProducts;

    protected $listeners = [
        'refresh-return-order-container' => 'refreshReturnOrderContainer'
    ];

    public function mount($order){
        $this->order = $order;

        foreach(json_decode($order->products) as $product){
            $item = Product::findOrFail($product->id);

            $this->orderedProducts[] = $item;
        }
    }

    public function refreshReturnOrderContainer($id){
        if($this->order->id == $id) {
            $this->mount($this->order); 
        }
    }

    public function render(){
        return view('livewire.Order.Return.return-order-container');
    }
}
