<?php

namespace App\Livewire\Affiliate;

use App\Enums\Status;
use App\Models\Affiliate;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class AffiliateInvitationModal extends Component
{
    use WithPagination;

    protected $listeners = [
        'refresh-invitation-modals' => '$refresh'
    ];

    public function render()
    {
        return view('livewire.Affiliate.affiliate-invitation-modal',[
            'affiliates' => Affiliate::where('promoter_id', Auth::id())->where('status', Status::Invitation)->orderBy('id', 'DESC')->paginate(10)
        ]);
    }
}
