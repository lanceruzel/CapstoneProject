<?php

namespace App\Livewire\Order;

use App\Models\Report;
use Livewire\Component;

class ViewReportDetailsInfoModal extends Component
{
    public $report;

    public $media;
    public $description;
    public $products;
    public $type;


    protected $listeners = [
        'viewReportInformation' => 'getData',
        'clearViewReportDetailsModal' => 'clearData'
    ];

    public function getData($id){
        $this->report = Report::where('order_id', $id)->first();

        if($this->report){
            $this->description = $this->report->description;
            $this->media = json_decode($this->report->media);
            $this->type = $this->report->type;
        }
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

    public function render()
    {
        return view('livewire.Order.view-report-details-info-modal');
    }
}
