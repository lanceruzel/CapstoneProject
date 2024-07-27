<?php

namespace App\Livewire\StoreRegistration;

use Livewire\Component;

class PDFViewModal extends Component
{
    public $pdf = 0;
    
    protected $listeners = [
        'clearpdfViewModalData' => 'clearData',
        'view-pdf' => 'getPDF'
    ];

    public function getPDF($filename){
        // dd(public_path('uploads/documents/' . $filename));
        $this->pdf = asset('uploads/documents/' . $filename);
    }

    public function clearData(){
        $this->reset(['pdf']);
    }

    public function render()
    {
        return view('livewire.StoreRegistration.p-d-f-view-modal');
    }
}
