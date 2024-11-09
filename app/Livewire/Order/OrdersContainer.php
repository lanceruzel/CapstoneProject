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
        $userId = Auth::id();

        if($status == 'all'){
            $this->orders = Order::where('user_id', $userId)->orderBy('id', 'DESC')->get();
        }else if($status == 'to_receieved'){
            $this->orders = Order::where('user_id', $userId)
                ->where(function ($query) {
                    $query->where('status', Status::OrderSellerShipped)
                        ->orWhere('status', Status::OrderSellerShipped)
                        ->orWhere('status', Status::OrderSellerPreparing);
                })
                ->orderBy('id', 'DESC')
                ->get();
        }else if($status == 'cancelled'){
            $this->orders = Order::where('user_id', $userId)->where('status', Status::OrderSellerCancel)->orWhere('status', Status::OrderBuyerCancel)->orderBy('id', 'DESC')->get();
        }else if($status == 'received'){
            $this->orders = Order::where('user_id', $userId)->where('status', Status::OrderBuyerReceived)->orderBy('id', 'DESC')->get();
        }else{
            $this->orders = Order::where('user_id', $userId)->orderBy('id', 'DESC')->get();
        }
    }

    public function render()
    {
        return view('livewire.Order.orders-container', [
            'orders' => $this->orders
        ]);
    }
}
