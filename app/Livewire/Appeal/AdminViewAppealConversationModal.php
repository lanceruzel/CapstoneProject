<?php

namespace App\Livewire\Appeal;

use App\Classes\UserNotif;
use App\Enums\NotificationType;
use App\Enums\Status;
use App\Mail\AvailableProductsMail;
use App\Models\Product;
use App\Models\ReportAppeal;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
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
            'report'
        ]);

        $this->report = null;
        $this->dispatch('clear-appeal-convo-view');
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
        try{
            $this->product->status = Status::Available;
            $this->product->remarks = '';

            if($this->product->save() && $this->updateConversationStatus()){
                $this->notification()->send([
                    'icon' => 'success',
                    'title' => 'Success!',
                    'description' => 'Product has been successfully unsuspended.',
                ]);
    
                $this->dispatch('close-modal', ['modal' => 'reportAppealFormModal']);
                $this->dispatch('refresh-report-appeals-table');
    
                UserNotif::sendNotif($this->product->seller_id, $this->product->name . ' has been unsuspended and is now available again.' , NotificationType::Appeal);
                
                Mail::to($this->product->seller->email)->send(new AvailableProductsMail($this->product->name, $this->product->seller->name()));

                $this->report->status = Status::Resolved;
                $this->report->save();
            }
        }catch(\Exception $e){
            $this->notification()->send([
                'icon' => 'error',
                'title' => 'Error!',
                'description' => 'Woops, its an error.',
            ]);

            Log::error('Error UnsuspendProduct: ' . $e->getMessage());
        }
    }

    public function exportReport(){
        $pdf = Pdf::loadView('pdf.invoice', ['product' => $this->product, 'report' => $this->report]);

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, 'invoice.pdf');
    }
    
    public function render()
    {
        return view('livewire.Appeal.admin-view-appeal-conversation-modal');
    }
}
