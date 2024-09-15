<?php

namespace App\Livewire\Order;

use Livewire\Component;

class ViewReceiptModal extends Component
{
    
    protected $listeners = [
        'clearReceiptModal' => 'clearData',
        'view-receipt-order' => 'getData'
    ];

    public function clearData(){

    }

    public function getData($id){
        
    }

    public function downloadReceipt(){
        
    }

    public function render()
    {
        return view('livewire.Order.view-receipt-modal');
    }
}
