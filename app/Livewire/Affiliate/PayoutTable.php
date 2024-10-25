<?php

namespace App\Livewire\Affiliate;

use App\Models\Payout;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class PayoutTable extends Component
{
    protected $listeners = [
        'refresh-payout-tables' => '$refresh'
    ];

    public $search = '';

    public $filterStatus = [];

    public function getData(){
        $filter = $this->filterStatus;

        if(empty($filter)){
            return Payout::where('requested_to', Auth::id())->orderBy('id', 'desc')->paginate(10);
        }else{
            return Payout::query()
            ->where('account_name', 'like', '%' . $this->search . '%')
            ->where(function ($query) use ($filter) {
                for ($i = 0; $i < count($filter); $i++) {
                    $query->orWhere('status', 'like', '%' . $filter[$i] . '%');
                }
            })
            ->where('requested_to', Auth::id())
            ->orderBy('id', 'desc')
            ->paginate(10);
        }
    }

    public function render()
    {
        return view('livewire.Affiliate.payout-table', [
            'payouts' =>  $this->getData()
        ]);
    }
}
