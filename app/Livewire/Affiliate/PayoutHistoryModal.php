<?php

namespace App\Livewire\Affiliate;

use App\Models\Payout;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class PayoutHistoryModal extends Component
{
    use WithPagination;

    public $id;
    public $payouts;

    protected $listeners = [
        'refresh-affiliate-tables' => '$refresh',
        'get-payout-info' => 'getData',
        'clearaffiliatePayoutHistoryData' => 'clearData'
    ];

    public function getData($id){
        $this->id = $id;

        if($id){
            $this->payouts = Payout::where('user_id', $this->id)->orderBy('id', 'DESC')->get();
        }else{
            $this->payouts = Payout::where('user_id', Auth::id())->orderBy('id', 'DESC')->get();
        }
    }

    public function clearData(){
        $this->reset([
            'id',
            'payouts'
        ]);
    }

    public function render()
    {
        return view('livewire.Affiliate.payout-history-modal');
    }
}
