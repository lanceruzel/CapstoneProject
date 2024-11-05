<?php

namespace App\Livewire\UserManagement;

use App\Models\Affiliate;
use Livewire\Component;
use Livewire\WithPagination;

class ViewAffiliates extends Component
{
    use WithPagination;

    public $id;
    public $mode;
    public $affiliates;

    protected $listeners = [
        'viewAffiliates' => 'getData',
        'clearViewAffiliatesModal' => 'clearData'
    ];

    public function getData($id, $mode){
        $this->id = $id; 
        $this->mode = $mode; 

        $this->affiliates = $this->mode == 'store' ? Affiliate::where('store_id', $this->id)->get() : Affiliate::where('promoter_id', $this->id)->get();
    }

    public function clearData(){
        $this->reset([
            'id',
            'mode',
            'affiliates'
        ]);
    }

    public function render(){
        

        return view('livewire.UserManagement.view-affiliates');
    }
}
