<?php

namespace App\Livewire\StoreRegistration;

use Livewire\Component;

class PDFViewModal extends Component
{
    public $file = 0;
    public $fileType = '';
    
    protected $listeners = [
        'clearpdfViewModalData' => 'clearData',
        'view-pdf' => 'getPDF'
    ];

    public function getPDF($filename){
        //Only works in hosting
        $this->file = str_replace('https://', 'http://', url('uploads/documents/' . $filename));

        $this->fileType = pathinfo($this->file, PATHINFO_EXTENSION);
    }

    public function clearData(){
        $this->reset(['file', 'fileType']);
    }

    public function render()
    {
        return view('livewire.StoreRegistration.p-d-f-view-modal');
    }
}
