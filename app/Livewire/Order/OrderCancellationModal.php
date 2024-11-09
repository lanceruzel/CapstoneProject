<?php

namespace App\Livewire\Order;

use App\Enums\Status;
use App\Models\Order;
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
