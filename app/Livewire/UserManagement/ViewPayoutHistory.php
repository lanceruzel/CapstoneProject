<?php

namespace App\Livewire\UserManagement;

use App\Models\Payout;
use Livewire\Component;
use Livewire\WithPagination;

class ViewPayoutHistory extends Component
{
    use WithPagination;

    public $promoter;
    public $store;
    public $payouts;

    protected $listeners = [
        'clearPayoutHistoryModal' => 'clearData',
        'viewPayout' => 'getData'
    ];

    public function getData($promoter, $store){
        $this->promoter = $promoter;
        $this->store = $store;
        $this->payouts = Payout::where('user_id', $this->promoter)
        ->where('requested_to', $this->store)
        // ->paginate(10)
        ->get();
    }

    public function clearData(){
        $this->reset([
            'promoter',
            'store',
            'payouts'
        ]);
    }

    public function render(){
        return view('livewire.UserManagement.view-payout-history');
    }
}
