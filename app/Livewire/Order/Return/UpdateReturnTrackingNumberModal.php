<?php

namespace App\Livewire\Order\Return;

use App\Classes\UserNotif;
use App\Enums\NotificationType;
use App\Enums\Status;
use App\Models\ReturnRequest;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

class UpdateReturnTrackingNumberModal extends Component
{
    use WireUiActions;

    public $listOfCourriers = null;
    public $trackingNumber;
    public $courrier;
    public $returnRequest;

    protected $listeners = [
        'clearReturnUpdateTrackingModal' => 'clearData',
        'req-return-info' => 'getData',
    ];

    public function mount(){
        $this->listOfCourriers = $this->getCourriers();
    }

    public function getData($id){
        $this->returnRequest = ReturnRequest::findOrFail($id);

        $this->courrier = $this->returnRequest->courrier;
        $this->trackingNumber = $this->returnRequest->tracking_number;
    }

    public function clearData(){
        $this->reset([
            'trackingNumber',
            'returnRequest',
            'courrier'
        ]);
    }
    
    public function updateTrackingNumber(){
        $validated = $this->validate([
            'courrier' => 'required',
            'trackingNumber' => 'required'
        ]);

        $this->returnRequest->courrier = $validated['courrier'];
        $this->returnRequest->tracking_number = $validated['trackingNumber'];
        $this->returnRequest->status = Status::ReturnRequestBuyerShipped;

        if($this->returnRequest->save()){
            $this->notification()->send([
                'icon' => 'success',
                'title' => 'Success!',
                'description' => 'Successfully Updated.',
            ]);

            $this->dispatch('close-modal', ['modal' => 'returnUpdateTrackingModal']);
            $this->dispatch('refresh-return-order-container', ['id' => $this->returnRequest->id]);

            UserNotif::sendNotif($this->returnRequest->seller_id, 'Return request #' . $this->returnRequest->id . ' has been updated.' , NotificationType::ReturnRequest);
        }
    }

    public function getCourriers() {
        $courriersJsonFile = public_path('json/courriers.json');
        $courriers = json_decode(file_get_contents($courriersJsonFile), true);

        sort($courriers);
    
        return $courriers; 
    }

    public function render()
    {
        return view('livewire.Order.Return.update-return-tracking-number-modal');
    }
}
