<?php

namespace App\Livewire\Report;

use App\Enums\Status;
use App\Models\Product;
use App\Models\Report;
use Barryvdh\DomPDF\Facade\Pdf;
use Livewire\Component;
use Str;
use WireUi\Traits\WireUiActions;

class ViewReportModal extends Component
{
    use WireUiActions;

    public $report;

    public $media;
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
            $this->media = json_decode($this->report->media);
            $this->type = $this->report->type;
        }
    }


    public function exportReport(){
        $pdf = Pdf::loadView('pdf.productReport', ['report' => $this->report]);

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, 'report.pdf');
    }

    public function confirmSuspend(): void{
        $this->notification()->confirm([
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

        $this->dispatch('close-modal', ['modal' => 'viewReportModal']);
        $this->dispatch('refresh-reports-table');
    }

    public function clearData(){
        $this->reset([
            'report',
            'media',
            'description',
            'products',
            'type'
        ]);
    }

    public function render(){
        return view('livewire.Report.view-report-modal');
    }
}
