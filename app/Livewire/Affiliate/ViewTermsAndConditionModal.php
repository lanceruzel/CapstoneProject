<?php

namespace App\Livewire\Affiliate;

use App\Enums\Status;
use App\Models\Affiliate;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

class ViewTermsAndConditionModal extends Component
{
    use WireUiActions;

    public $affiliate = null;

    protected $listeners = [
        'view-terms-and-condition-info' => 'getData',
        'clearAffiliateTermsAndConditionModalData' => 'clearData'
    ];

    public function clearData(){
        $this->affiliate = null;
    }

    public function getData($id){
        $this->affiliate = Affiliate::findOrFail($id);
    }

    public function accept(){
        $this->affiliate->status = Status::Active;
        $this->saveAffiliate();
    }

    public function decline(){
        $this->affiliate->status = Status::Declined;
        $this->saveAffiliate();
    }

    public function saveAffiliate(){
        if($this->affiliate->save()){
            $this->notification()->send([
                'icon' => 'success',
                'title' => 'Success!',
                'description' => 'Successfully updated.',
            ]);

            $this->dispatch('close-modal', ['modal' => 'affiliateTermsAndConditionModal']);
            $this->dispatch('refresh-invitation-modals');
        }
    }

    public function render(){
        return view('livewire.Affiliate.view-terms-and-condition-modal');
    }
}
