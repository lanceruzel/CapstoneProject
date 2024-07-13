<?php

namespace App\Livewire\Affiliate;

use Livewire\Component;

class AffiliateInviteFormModal extends Component
{
    protected $listeners = [
        'clearAffiliateInviteFormModalData' => 'clearData'
    ];

    public function clearData(){

    }

    public function render()
    {
        return view('livewire.Affiliate.affiliate-invite-form-modal');
    }
}
