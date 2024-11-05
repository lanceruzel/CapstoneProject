<?php

namespace App\Livewire\UserManagement;

use App\Models\Order;
use Livewire\Component;

class ViewOrderDetails extends Component
{
    public $order;
    public $products;

    protected $listeners = [
        'viewOrderInfo' => 'getData',
        'clearOrderDetailsModal' => 'clearData'
    ];

    public function getData($id){
        $this->order = Order::findOrFail($id);

        if($this->order){
            $this->products = $this->order->orderedItems;
        }
    }

    public function clearData(){
        $this->reset([
            'order',
            'products'
        ]);
    }

    public function render(){
        return view('livewire.UserManagement.view-order-details');
    }
}
