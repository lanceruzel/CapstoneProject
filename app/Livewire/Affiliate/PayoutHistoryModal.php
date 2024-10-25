<?php

namespace App\Livewire\Affiliate;

use App\Models\Payout;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class PayoutHistoryModal extends Component
{
    use WithPagination;

    protected $listeners = [
        'refresh-affiliate-tables' => '$refresh'
    ];

    public function render()
    {
        return view('livewire.Affiliate.payout-history-modal', [
            'payouts' => Payout::where('user_id', Auth::id())->orderBy('id', 'DESC')->paginate(10)
        ]);
    }
}
