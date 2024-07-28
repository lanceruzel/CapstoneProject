<?php

namespace App\Livewire\Affiliate;

use App\Classes\UserNotif;
use App\Enums\NotificationType;
use App\Enums\Status;
use App\Models\Affiliate;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

class ViewTermsAndConditionModal extends Component
{
    use WireUiActions;

    public $affiliate = null;
    public $mode = 'promoter';

    protected $listeners = [
        'view-terms-and-condition-info' => 'getData',
        'clearAffiliateTermsAndConditionModalData' => 'clearData'
    ];

    public function clearData(){
        $this->affiliate = null;
        $this->mode = 'promoter';
    }

    public function getData($id, $mode = null){
        $this->affiliate = Affiliate::findOrFail($id);

        if($mode){
            $this->mode = $mode;
        }
    }

    public function activeConfirmation(){
        $this->notification()->confirm([
            'title' => 'Are you Sure?',
            'description' => 'Set this affiliate to active?',
            'acceptLabel' => 'Yes',
            'method' => 'active',
        ]);
    }

    public function inactiveConfirmation(){
        $this->notification()->confirm([
            'title' => 'Are you Sure?',
            'description' => 'Set this affiliate to inactive?',
            'acceptLabel' => 'Yes',
            'method' => 'inactive',
        ]);
    }

    public function active(){
        $this->affiliate->status = Status::Active;
        $this->saveAffiliate();
    }

    public function inactive(){
        $this->affiliate->status = Status::Inactive;
        $this->saveAffiliate();
    }

    public function accept(){
        $this->affiliate->status = Status::Active;
        UserNotif::sendNotif($this->affiliate->store_id, 'Promoter ' . $this->affiliate->user->name() . ' have accepted your affiliate invitation.' , NotificationType::Affiliate);
        $this->saveAffiliate();
    }

    public function decline(){
        $this->affiliate->status = Status::Declined;
        UserNotif::sendNotif($this->affiliate->store_id, 'Promoter ' . $this->affiliate->user->name() . ' have accepted your declined invitation.', NotificationType::Affiliate);
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
            $this->dispatch('refresh-affiliate-tables');
        }
    }

    public function render(){
        return view('livewire.Affiliate.view-terms-and-condition-modal');
    }
}
