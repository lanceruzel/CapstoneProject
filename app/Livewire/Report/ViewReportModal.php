<?php

namespace App\Livewire\Report;

use App\Enums\Status;
use App\Models\Product;
use App\Models\ProductReport;
use Barryvdh\DomPDF\Facade\Pdf;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

class ViewReportModal extends Component
{
    use WireUiActions;

    public $report = null;

    public $content;
    public $images;

    public $product;

    protected $listeners = [
        'viewReportInformation' => 'getData',
        'productReportModalData' => 'clearData'
    ];

    public function suspensionConfirmation(){
        $this->notification()->confirm([
            'title' => 'Are you Sure?',
            'description' => 'Suspend this product?',
            'acceptLabel' => 'Yes, suspend it',
            'method' => 'suspendProduct',
        ]);
    }

    public function suspendProduct(){
        if($this->report){
            $product = Product::findOrFail($this->report->product_id);

            if($product){
                $product->status = Status::Suspended;
                $product->remarks = 'Your product has been reported multiple times. And with this we decided to suspend your product.';

                if($product->save()){
                    $this->notification()->send([
                        'icon' => 'success',
                        'title' => 'Success!',
                        'description' => 'Product has been suspended.',
                    ]);
    
                    $this->dispatch('close-modal', ['modal' => 'productReportModal']);
                }
            }
        }
    }

    public function exportReport(){
        $pdf = Pdf::loadView('pdf.productReport', ['report' => $this->report]);

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, 'invoice.pdf');
    }

    public function getData($id){
        $this->report = ProductReport::findOrFail($id);

        if($this->report){
            $this->images = json_decode($this->report->images);
        }
    }

    public function clearData(){
        $this->reset([
            'images',
            'content'
        ]);

        $this->report = null;
    }

    public function render()
    {
        return view('livewire.Report.view-report-modal');
    }
}
