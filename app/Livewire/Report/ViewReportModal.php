<?php

namespace App\Livewire\Report;

use App\Enums\Status;
use App\Models\Product;
use App\Models\Report;
use Barryvdh\DomPDF\Facade\Pdf;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

class ViewReportModal extends Component
{
    use WireUiActions;

    public $report;

    public $images;
    public $description;
    public $products;
    public $type;


    protected $listeners = [
        'viewReport' => 'getData',
        'clearViewReportModalData' => 'clearData'
    ];

    public function getData($id){
        $this->report = Report::findOrFail($id);

        if($this->report){
            $this->description = $this->report->description;
            $this->images = json_decode($this->report->images);
            $this->type = $this->report->type;
        }
    }

    public function exportReport(){
        $pdf = Pdf::loadView('pdf.productReport', ['report' => $this->report]);

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, 'report.pdf');
    }

    public function confirmSuspend(): void
    {
        $this->dialog()->confirm([
            'title' => 'Are you Sure?',
            'description' => 'Suspend this products?',
            'acceptLabel' => 'Yes, suspend it',
            'method' => 'suspendProducts',
        ]);
    }

    public function suspendProducts(){
        foreach(json_decode($this->report->products) as $item){
            $product = Product::findOrFail($item->id);

            if($product){
                $product->status = Status::Suspended;
                $product->save();
            }
        }

        $this->report->status = Status::AdminProductSuspend;
        $this->report->save();

        $this->notification()->send([
            'icon' => 'success',
            'title' => 'Success!',
            'description' => 'Successfully Suspended.',
        ]);
    }

    public function clearData(){
        $this->reset([
            'report',
            'images',
            'description',
            'products',
            'type'
        ]);
    }

    public function render(){
        return view('livewire.Report.view-report-modal');
    }
}
