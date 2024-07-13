<?php

namespace App\Livewire\Affiliate;

use App\Models\Affiliate;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class StoreAffiliatesTable extends Component
{
    use WithPagination;

    public $filterStatus = [];

    public function getData(){
        $filter = $this->filterStatus;

        if(empty($filter)){
            return Affiliate::orderBy('id', 'desc')->paginate(10);
        }else{
            return Affiliate::query()
            ->Where(function ($query) use($filter) {
                for ($i = 0; $i < count($filter); $i++){
                    $query->orwhere('status', 'like',  '%' . $filter[$i] .'%');
                }  
            })
            ->orderBy('id', 'desc')
            ->paginate(10);
        }
    }

    public function render()
    {
        return view('livewire.Affiliate.store-affiliates-table',[
            'affiliates' => $this->getData()
        ]);
    }
}
