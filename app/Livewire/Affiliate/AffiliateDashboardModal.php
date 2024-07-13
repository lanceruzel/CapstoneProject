<?php

namespace App\Livewire\Affiliate;

use App\Enums\Status;
use App\Models\Affiliate;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class AffiliateDashboardModal extends Component
{
    public $overallCommissioned = 0;
    public $todayCommissioned = 0;
    public $lastWeekCommissioned = 0;
    public $thisMonthCommissioned = 0;

    protected $listeners = [
        'clearaffiliateDashboardModalData' => 'clearData'
    ];

    public function clearData(){
        
    }

    public function render()
    {
        return view('livewire.Affiliate.affiliate-dashboard-modal', [
            'affiliates' => Affiliate::where('promoter_id', Auth::id())->where('status', '<>', Status::Invitation)->orderBy('id', 'DESC')->paginate(10)
        ]);
    }
}
