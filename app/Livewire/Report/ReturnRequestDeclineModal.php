<?php

namespace App\Livewire\Report;

use App\Classes\UserNotif;
use App\Enums\NotificationType;
use App\Enums\Status;
use App\Models\Report;
use App\Models\User;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

class ReturnRequestDeclineModal extends Component
{
    use WireUiActions;
    
    public $reason;
    public $description;
    public $report;
    public $mode;

    protected $listeners =[
        'clearReturnReuqestCancellationModalFormData' => 'clearData',
        'cancellationRequest' => 'getData',
        'updatedReturnReason' => '$refresh'
    ];

    public function getData($id){
        $this->report = Report::find($id);
    }

    public function confirmation(){
        $this->validateForm();

        $this->dialog()->confirm([
            'title' => 'Are you Sure?',
            'description' => 'Decline this request?',
            'acceptLabel' => 'Yes, decline it',
            'method' => 'declineRequest',
            'params' => '',
        ]);
    }

    public function declineRequest(){
        if($this->report){
            $validated = $this->validateForm();

            $this->report->status = Status::Declined;
            $this->report->cancel_reason = $validated['description'] ?? $validated['reason'];
            
            if($this->report->save()){
                UserNotif::sendNotif($this->report->reporter_id, 'Your return request has been declined.' , NotificationType::ReturnRequest);

                $this->dispatch('close-modal', ['modal' => 'viewReturnRequestModal']);
                $this->dispatch('close-modal', ['modal' => 'returnReuqestCancellationModal']);
                $this->dispatch('refresh-return-product-table');

                $this->notification()->send([
                    'icon' => 'success',
                    'title' => 'Success!',
                    'description' => 'Request has been declined',
                ]);
            }
        }
    }

    public function clearData(){
        $this->reset([
            'reason',
            'description',
            'report'
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
        return view('livewire.Report.return-request-decline-modal');
    }
}
