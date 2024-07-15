<?php

namespace App\Livewire\Affiliate;

use App\Enums\Status;
use App\Models\Affiliate;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class AffiliateDashboardModal extends Component
{
    public $overallCommissioned = 0;
    public $todayCommissioned = 0;
    public $lastWeekCommissioned = 0;
    public $thisMonthCommissioned = 0;

    public function retrieve_commissions(){
        $affiliates = Affiliate::where('promoter_id', Auth::id())->get();

        foreach($affiliates as $affiliate){
            $affiliateUpdatedAt = Carbon::parse($affiliate->updated_at); 

            $this->overallCommissioned += $affiliate->totalCommissioned();

            //Today
            if($affiliate->updated_at->format('Y-m-d') == date('Y-m-d')){
                $this->todayCommissioned += $affiliate->totalCommissioned();
            }

            //Last Week
            $lastWeekStart = $affiliateUpdatedAt->startOfWeek()->subWeek(); 
            $lastWeekEnd = $lastWeekStart->copy()->endOfWeek();  

            if($affiliateUpdatedAt->between($lastWeekStart, $lastWeekEnd)){
                $this->lastWeekCommissioned += $affiliate->totalCommissioned();
            }

            //This month
            if($affiliateUpdatedAt->between($affiliateUpdatedAt->startOfMonth(), $affiliateUpdatedAt->endOfMonth())){
                $this->thisMonthCommissioned += $affiliate->totalCommissioned();
            }
        }
    }

    public function render(){
        $this->retrieve_commissions();

        return view('livewire.Affiliate.affiliate-dashboard-modal', [
            'affiliates' => Affiliate::where('promoter_id', Auth::id())->where('status', '<>', Status::Invitation)->orderBy('id', 'DESC')->paginate(10)
        ]);
    }
}
