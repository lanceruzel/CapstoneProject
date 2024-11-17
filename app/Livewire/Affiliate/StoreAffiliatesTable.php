<?php

namespace App\Livewire\Affiliate;

use App\Models\Affiliate;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class StoreAffiliatesTable extends Component
{
    use WithPagination;

    protected $listeners = [
        'refresh-affiliate-tables' => '$refresh'
    ];

    public $search = '';

    public $filterStatus = [];

    public function getData(){
        $filter = $this->filterStatus;

        if(empty($filter)){
            return Affiliate::whereHas('user', function($query){
                $query->whereHas('userinformation', function($query){
                    $query->where('first_name', 'like', '%' . $this->search . '%')
                    ->orWhere('last_name', 'like', '%' . $this->search . '%');
                });
            })
            ->where('store_id', Auth::id())->orderBy('id', 'desc')
            ->paginate(10);
        }else{
            return Affiliate::query()
            ->Where(function ($query) use($filter) {
                for ($i = 0; $i < count($filter); $i++){
                    $query->orwhere('status', $filter[$i]);
                }  
            })
            ->whereHas('user', function($query){
                $query->whereHas('userinformation', function($query){
                    $query->where('first_name', 'like', '%' . $this->search . '%')
                    ->orWhere('last_name', 'like', '%' . $this->search . '%');
                });
            })
            ->where('store_id', Auth::id())
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
