<?php

namespace App\Livewire\Affiliate;

use Livewire\Component;

class AffiliateDashboardModal extends Component
{
    public $affiliates = [];

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
        return view('livewire.Affiliate.affiliate-dashboard-modal');
    }
}
