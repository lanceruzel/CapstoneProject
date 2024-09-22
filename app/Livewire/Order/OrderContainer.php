<?php

namespace App\Livewire\Order;

use App\Classes\UserNotif;
use App\Enums\NotificationType;
use App\Enums\Status;
use App\Models\Affiliate;
use App\Models\ProductFeedback;
use App\Models\Report;
use App\Models\ReturnRequest;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

class OrderContainer extends Component
{
    use WireUiActions;

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
        }
    }

    public function hasReported(){
        return Report::where('order_id', $this->order->id)->exists();
    }

    public function orderReceivedConfirmation(){
        $this->dialog()->confirm([
            'title' => 'Are you Sure?',
            'description' => 'You have received this order?',
            'acceptLabel' => 'Yes',
            'method' => 'receivedOrder',
        ]);
    }

    public function downloadReceipt(){
        $pdf = Pdf::loadView('pdf.orderReceipt', ['order' => $this->order]);

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, 'receipt.pdf');
    }

    public function receivedOrder(){
        if($this->order->affiliate_code){
            $this->updateAffiliateCommission();
        }

        $this->order->status = Status::OrderBuyerReceived;
        $this->order->is_paid = true;

        UserNotif::sendNotif($this->order->seller_id, 'Order #' . $this->order->id . ' has been received buy the buyer.' , NotificationType::Order);

        $this->order->save();

        $this->dialog()->show([
            'icon' => 'info',
            'title' => 'Return Policy!',
            'description' => 'To facilitate a smooth return, please return items within 24 hours of receipt. If the item is damaged or defective, kindly include photos or other proof with your return request. Thank you for your cooperation!',
        ]);
    }

    public function isReturnOrderApplicable(){
        if($this->order->status == Status::OrderBuyerReceived){
            $orderedDate = Carbon::parse($this->order->updated_at);
            $isWithinLast24Hours = $orderedDate->greaterThanOrEqualTo(Carbon::now()->subDay());

            if($isWithinLast24Hours){
                return true;
            }
        }

        return false;
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

    public function refreshOrderContainer($id)
    {
        if ($this->order->id == $id) {
            $this->mount($this->order); 
        }
    }

    public function render(){
        return view('livewire.Order.order-container',[
            'hasReported' => $this->hasReported()
        ]);
    }
}
