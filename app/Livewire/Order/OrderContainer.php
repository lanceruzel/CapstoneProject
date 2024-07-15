<?php

namespace App\Livewire\Order;

use App\Enums\Status;
use App\Models\Affiliate;
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
        if($this->order->affiliate_code){
            $this->updateAffiliateCommission();
        }

        $this->order->status = Status::OrderBuyerReceived;
        $this->order->save();
    }

    public function updateAffiliateCommission(){
        $affiliate = Affiliate::where('affiliate_code', $this->order->affiliate_code)->first();

        if($affiliate){
            $rate = floatval($affiliate->rate) / 100;

            $affiliate->totalCommissioned += ($rate * $this->order->total);
            $affiliate->save();
        }
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
