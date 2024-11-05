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

    protected $listeners = [
        'viewAffiliates' => 'getData',
        'clearViewAffiliatesModal' => 'clearData'
    ];

    public function getData($id, $mode){
        $this->id = $id; 
        $this->mode = $mode; 
    }

    public function clearData(){
        $this->reset([
            'id',
            'mode'
        ]);
    }

    public function render(){
        $affiliate = $this->mode == 'store' ? Affiliate::where('store_id', $this->id)->paginate(10) : Affiliate::where('promoter_id', $this->id)->paginate(10);

        return view('livewire.UserManagement.view-affiliates', [
            'affiliates' => $affiliate
        ]);
    }
}
