<?php

namespace App\Livewire\Order;

use App\Enums\Status;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class OrdersContainer extends Component
{
    public $orders;

    public function mount($status){
        if($status == 'all'){
            $this->orders = Order::where('user_id', Auth::id())->orderBy('created_at', 'DESC')->get();
        }else if($status == 'to_receieved'){
            $this->orders = Order::where('user_id', Auth::id())
                ->where('status', Status::OrderSellerShipped)
                ->orWhere('status', Status::OrderSellerShipped)
                ->orWhere('status', Status::OrderSellerPreparing)
                ->orderBy('created_at', 'DESC')->get();
        }else if($status == 'cancelled'){
            $this->orders = Order::where('user_id', Auth::id())->where('status', Status::OrderSellerCancel)->orWhere('status', Status::OrderBuyerCancel)->orderBy('created_at', 'DESC')->get();
        }else if($status == 'received'){
            $this->orders = Order::where('user_id', Auth::id())->where('status', Status::OrderBuyerReceived)->orderBy('created_at', 'DESC')->get();
        }else{
            $this->orders = Order::where('user_id', Auth::id())->orderBy('created_at', 'DESC')->get();
        }
    }

    public function render()
    {
        return view('livewire.Order.orders-container', [
            'orders' => $this->orders
        ]);
    }
}
