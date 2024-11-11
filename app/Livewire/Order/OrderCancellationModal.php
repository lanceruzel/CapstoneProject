<?php

namespace App\Livewire\Order;

use App\Classes\PaypalRefund;
use App\Enums\Status;
use App\Mail\RefundEmail;
use App\Models\Order;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

class OrderCancellationModal extends Component
{
    use WireUiActions;
    
    public $reason;
    public $description;
    public $order;
    public $mode;

    protected $listeners =[
        'clearOrderCancellationModalFormData' => 'clearData',
        'cancellationOrder' => 'getData',
        'updatedCancelReason' => '$refresh'
    ];

    public function getData($id, $mode){
        $this->order = Order::find($id);
        $this->mode = $mode;
    }

    public function confirmation(){
        $this->validateForm();

        $this->dialog()->confirm([
            'title' => 'Are you Sure?',
            'description' => 'Cancel this order?',
            'acceptLabel' => 'Yes, cancel it',
            'method' => 'cancelOrder',
            'params' => '',
        ]);
    }

    public function cancelOrder(){
        if($this->order){
            $validated = $this->validateForm();

            if($this->mode == 'store'){
                $this->order->status = Status::OrderSellerCancel;
            }else{
                $this->order->status = Status::OrderBuyerCancel;
            }

            $this->order->cancel_reason = $validated['description'] ?? $validated['reason'];
            
            if($this->order->save()){
                if($this->order->payment_method == 'Paypal' && ($this->order->is_paid == true || $this->order->is_paid == 1)){
                    $this->refund();
                }

                $this->dispatch('close-modal', ['modal' => 'orderCancellationModalForm']);

               if($this->mode == 'store'){
                    $this->dispatch('close-modal', ['modal' => 'orderViewModal']);
                    $this->dispatch('refresh-order-table');
               }else{
                    $this->dispatch('refresh-order-container', ['id' => $this->order->id]);
               }

                $this->notification()->send([
                    'icon' => 'success',
                    'title' => 'Success!',
                    'description' => 'Order has been cancelled successfully',
                ]);
            }
        }
    }

    public function refund(){
        $paypal = new PaypalRefund();

        if($paypal->processRefund($this->order->referenceNumber)){
            Mail::to($this->order->user->email)->send(new RefundEmail($this->order));

            $this->notification()->send([
                'icon' => 'success',
                'title' => 'Success!',
                'description' => $this->mode == 'store' ? 'Buyer\'s payment has been successfully refunded.' : 'Your refund has been successfully sent to your account.',
            ]);
        }
    }

    public function clearData(){
        $this->reset([
            'reason',
            'description',
            'order'
        ]);
    }

    public function validateForm(){
        $rules = [
            'reason' => 'required',
        ];

        if($this->reason == 'Others'){
            $rules['description'] = 'required|min:20|max:255';
        }

        return $this->validate($rules);
    }

    public function render()
    {
        return view('livewire.Order.order-cancellation-modal');
    }
}
