<?php

namespace App\Livewire\Affiliate;

use App\Enums\Status;
use App\Models\Affiliate;
use App\Models\User;
use App\Classes\UserNotif;
use App\Enums\NotificationType;
use App\Enums\UserType;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

class AffiliateInviteFormModal extends Component
{
    use WireUiActions;

    public $commissionRate;
    public $email; 
    public $affiliateCode;
    public $discount;

    protected $listeners = [
        'clearAffiliateInviteFormModalData' => 'clearData'
    ];

    public function sendAffiliateInvitation(){
        $validated = $this->validate([
            // 'email' => 'required|email|exists:users,email',
            'email' => 'required|email',
            'commissionRate' => 'required|numeric',
            'discount' => 'required|numeric',
            'affiliateCode' => 'required|min:10|max:15|alpha_num|unique:affiliates,affiliate_code'
        ]);

        try{
            //Check if email is existing
            if(User::where('email', $validated['email'])->exists()){
                $promoter = User::where('email', $validated['email'])->first();

                $promoterId = $promoter->id;

                if($promoter->role == UserType::Store){
                    $this->notification()->send([
                        'icon' => 'error',
                        'title' => 'Error!',
                        'description' => 'Woops, its an error. This user is a seller.',
                    ]);

                    return;
                }

                if(Affiliate::where('store_id', Auth::id())->where('promoter_id', $promoterId[0])->where('status', '<>', Status::Declined)->exists()){
                    $this->dialog()->show([
                        'icon' => 'info',
                        'title' => 'Info!',
                        'description' => 'You have existing affiliate with this user.',
                    ]);
                    return;
                }

                $affiliate = Affiliate::create([
                    'store_id' => Auth::id(),
                    'promoter_id' => $promoterId[0],
                    'affiliate_code' => $validated['affiliateCode'],
                    'rate' => $validated['commissionRate'],
                    'discount' => $validated['discount'],
                    'status' => Status::Invitation
                ]);

                if($affiliate){
                    UserNotif::sendNotif($promoterId[0], 'You have received an affiliate invitation.', NotificationType::Affiliate);

                    $this->notification()->send([
                        'icon' => 'success',
                        'title' => 'Success!',
                        'description' => 'Your invitation has been successfully sent.',
                    ]);

                    $this->dispatch('close-modal', ['modal' => 'affiliateInviteFormModal']);
                    $this->dispatch('refresh-affiliate-tables');
                }
            }else{
                $this->notification()->send([
                    'icon' => 'info',
                    'title' => 'Info!',
                    'description' => 'This email does not exists on our records.',
                ]);
            }
        }catch(\Exception $e){
            $this->notification()->send([
                'icon' => 'error',
                'title' => 'Error!',
                'description' => 'Woops, its an error.',
            ]);

            Log::error('Error AffiliateInvite: ' . $e->getMessage());
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
