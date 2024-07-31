<?php

namespace App\Livewire\Appeal;

use App\Classes\UserNotif;
use App\Enums\NotificationType;
use App\Enums\Status;
use App\Models\Product;
use App\Models\ReportAppeal;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

class AdminViewAppealConversationModal extends Component
{
    use WireUiActions;

    public $report;
    
    public $product;
    public $seller;

    protected $listeners = [
        'view-report-appeal-conversation' => 'getData',
        'clearReportAppealFormModalData' => 'clearData',
    ];

    public function getData($id){
        $this->report = ReportAppeal::findOrFail($id);

        if($this->report){
            $this->product = $this->report->product;
            $this->seller = $this->report->product->seller->storeInformation->name;
            $this->dispatch('view-appeal-convo', ['id' => $this->report->conversation_id]);
        }
    }

    public function clearData(){
        $this->reset([
            'product',
            'seller',
        ]);

        $this->report = null;
    }

    public function unsuspendedConfirmation(){
        $this->notification()->confirm([
            'title' => 'Are you sure?',
            'description' => 'Unsuspend this product?',
            'acceptLabel' => 'Yes, unsuspend it',
            'method' => 'unsuspendProduct',
        ]);
    }

    public function updateConversationStatus(){
        $this->report->conversation->status = Status::Inactive;
        return $this->report->conversation->save();
    }

    public function unsuspendProduct(){
        $this->product->status = Status::Available;

        if($this->product->save() && $this->updateConversationStatus()){
            $this->notification()->send([
                'icon' => 'success',
                'title' => 'Success!',
                'description' => 'Product has been successfully unsuspended.',
            ]);

            $this->dispatch('close-modal', ['modal' => 'reportAppealFormModal']);
            $this->dispatch('refresh-report-appeals-table');

            UserNotif::sendNotif($this->product->seller_id, $this->product->name . ' has been unsuspended and is now available again.' , NotificationType::Appeal);
        }
    }

    public function exportReport(){
        
    }
    
    public function render()
    {
        return view('livewire.Appeal.admin-view-appeal-conversation-modal');
    }
}
