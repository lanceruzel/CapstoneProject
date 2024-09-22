<?php

namespace App\Livewire\Order;

use App\Models\Report;
use Livewire\Component;

class ViewReportDetailsInfoModal extends Component
{
    public $report;

    public $images;
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
            $this->images = json_decode($this->report->images);
            $this->type = $this->report->type;
        }
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

    public function render()
    {
        return view('livewire.Order.view-report-details-info-modal');
    }
}
