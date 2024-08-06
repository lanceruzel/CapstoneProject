<?php

namespace App\Livewire\Return;

use App\Classes\UserNotif;
use App\Enums\NotificationType;
use App\Enums\Status;
use App\Models\Order;
use App\Models\OrderedItem;
use App\Models\ReturnRequest;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

class ReturnRequestViewModal extends Component
{
    use WireUiActions;

    public $request = null;
    public $images;

    protected $listeners = [
        'clearViewReturnRequestModalData' => 'clearData',
        'viewReturnProductInformation' => 'getData'
    ];
    
    public function getData($id){
        $this->request = ReturnRequest::findOrFail($id);

        if($this->request){
            $this->images = json_decode($this->request->images);
        }
    }

    public function acceptRequest(){
        $this->request->status = Status::Accepted;
        $this->updateRequest();
    }

    public function declineRequest(){
        $this->request->status = Status::Declined;
        $this->updateRequest();
    }

    public function markAsReceievedRequest(){
        $this->request->status = Status::ReturnRequestReceieved;
        $this->updateRequest();
    }

    public function getOrderInformation($id){
        return Order::findOrFail($id);
    }

    public function updateRequest(){
        if($this->request->save()){
            $this->notification()->send([
                'icon' => 'success',
                'title' => 'Success!',
                'description' => 'Successfully updated.',
            ]);

            $this->dispatch('close-modal', ['modal' => 'viewReturnRequestModal']);
            $this->dispatch('refresh-return-product-table');

            UserNotif::sendNotif($this->request->reporter_id, 'Your return request has been updated.' , NotificationType::ReturnRequest);
        }else{
            $this->notification()->send([
                'icon' => 'error',
                'title' => 'Error!',
                'description' => 'Woops, its an error. There seems to be a problem updating this request.',
            ]);
        }
    }

    public function clearData(){
        $this->request = null;

        $this->reset('images');
    }

    public function render()
    {
        return view('livewire.Return.return-request-view-modal');
    }
}
