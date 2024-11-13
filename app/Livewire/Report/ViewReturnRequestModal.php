<?php

namespace App\Livewire\Report;

use App\Classes\UserNotif;
use App\Enums\NotificationType;
use App\Enums\Status;
use App\Mail\ReturnRequestAcceptedMail;
use App\Models\Report;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;
use Str;
use WireUi\Traits\WireUiActions;

class ViewReturnRequestModal extends Component
{
    use WireUiActions;
    
    public $request;
    public $media;

    protected $listeners = [
        'viewReturnRequest' => 'getData',
        'clearvViewReturnRequestModal' => 'clearData'
    ];

    public function getData($id){
        $this->request = Report::findOrFail($id);

        if($this->request){
            $this->media = json_decode($this->request->media);
        }
    }

    public function acceptRequest(){
        $this->request->status = Status::Accepted;
        $this->updateRequest();

        Mail::to($this->request->reporter->email)->send(new ReturnRequestAcceptedMail($this->request));
    }

    public function declineRequest(){
        $this->request->status = Status::Declined;
        $this->updateRequest();
    }

    public function markAsReceievedRequest(){
        $this->request->status = Status::ReturnRequestReceieved;
        $this->updateRequest();
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
        $this->reset([
            'request',
            'media'
        ]);
    }

    public function render(){
        return view('livewire.Report.view-return-request-modal');
    }
}
