<?php

namespace App\Livewire\Affiliate;

use App\Enums\Status;
use App\Models\Affiliate;
use App\Models\User;
use App\Classes\UserNotif;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

class AffiliateInviteFormModal extends Component
{
    use WireUiActions;

    public $commissionRate;
    public $email; 
    public $affiliateCode;

    protected $listeners = [
        'clearAffiliateInviteFormModalData' => 'clearData'
    ];

    public function sendAffiliateInvitation(){
        $validated = $this->validate([
            // 'email' => 'required|email|exists:users,email',
            'email' => 'required|email',
            'commissionRate' => 'required|numeric',
            'affiliateCode' => 'required|min:10|max:15|alpha_num|unique:affiliates,affiliate_code'
        ]);

        //Check if email is existing
        if(User::where('email', $validated['email'])->exists()){
            $promoterId = User::where('email', $validated['email'])->pluck('id');

            $affiliate = Affiliate::create([
                'store_id' => Auth::id(),
                'promoter_id' => $promoterId[0],
                'affiliate_code' => $validated['affiliateCode'],
                'rate' => $validated['commissionRate'],
                'status' => Status::Invitation
            ]);

            if($affiliate){
                UserNotif::sendNotif($promoterId[0], 'test');

                $this->notification()->send([
                    'icon' => 'success',
                    'title' => 'Success!',
                    'description' => 'Your invitation has been successfully sent.',
                ]);

                $this->dispatch('close-modal', ['modal' => 'affiliateInviteFormModal']);
            }
        }else{
            $this->notification()->send([
                'icon' => 'info',
                'title' => 'Info!',
                'description' => 'This email does not exists on our records.',
            ]);
        }
    }

    public function clearData(){
        $this->reset([
            'email',
            'commissionRate',
            'affiliateCode'
        ]);
    }

    public function render()
    {
        return view('livewire.Affiliate.affiliate-invite-form-modal');
    }
}
