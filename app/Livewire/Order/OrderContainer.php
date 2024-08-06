<?php

namespace App\Livewire\Order;

use App\Classes\UserNotif;
use App\Enums\NotificationType;
use App\Enums\Status;
use App\Models\Affiliate;
use App\Models\ProductFeedback;
use App\Models\ReturnRequest;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class OrderContainer extends Component
{
    public $order;
    public $hasRequest;

    public $orderedProducts = [];

    protected $listeners = [
        'refresh-order-container' => 'refreshOrderContainer'
    ];

    public function mount($order = null){
        $this->order = $order;

        if($order){
            $this->orderedProducts = $order->orderedItems;
            $this->hasRequest = $this->hasReturnRequest();
        }
    }

    public function receivedOrder(){
        if($this->order->affiliate_code){
            $this->updateAffiliateCommission();
        }

        $this->order->status = Status::OrderBuyerReceived;
        $this->order->is_paid = true;

        UserNotif::sendNotif($this->order->seller_id, 'Order #' . $this->order->id . ' has been received buy the buyer.' , NotificationType::Order);

        $this->order->save();
    }

    public function updateAffiliateCommission(){
        $affiliate = Affiliate::where('affiliate_code', $this->order->affiliate_code)->first();

        if($affiliate){
            $affiliate->totalCommissioned += $this->order->commission;

            if($affiliate->save()){
                UserNotif::sendNotif($affiliate->promoter_id, 'You have received a commission.' , NotificationType::Affiliate);
            }
        }
    }

    public function hasReturnRequest(){
        return ReturnRequest::where('order_id', $this->order->id)->exists();
    }

    public function refreshOrderContainer($id)
    {
        if ($this->order->id == $id) {
            $this->mount($this->order); 
        }
    }

    public function render(){
        return view('livewire.Order.order-container');
    }
}
