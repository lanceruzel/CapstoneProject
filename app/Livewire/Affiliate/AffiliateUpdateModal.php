<?php

namespace App\Livewire\Affiliate;

use App\Enums\Status;
use App\Mail\AffiliateUpdatePositive;
use App\Models\Affiliate;
use App\Models\Payout;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

class AffiliateUpdateModal extends Component
{
    use WireUiActions;

    public $affiliate;

    public $email;
    public $commissionRate;
    public $discount;
    public $affiliateCode;

    protected $listeners = [
        'get-affilaite-data' => 'getData',
        'clearAffiliateUpdateFormModalData' => 'clearData'
    ];

    public function getData($id){
        $this->affiliate = Affiliate::find($id);

        if($this->affiliate){
            $this->email = $this->affiliate->user->email;
            $this->commissionRate = $this->affiliate->rate;
            $this->discount = $this->affiliate->discount;
            $this->affiliateCode = $this->affiliate->affiliate_code;
        }
    }

    public function updateConfirmation(){
        $this->notification()->confirm([
            'title' => 'Are you Sure?',
            'description' => 'Update this affiliate?',
            'acceptLabel' => 'Yes, save it',
            'method' => 'update',
            'params' => '',
        ]);
    }

    public function update(){
        $rules = [
            'commissionRate' => 'required|numeric',
            'discount' => 'required|numeric',
        ];

        if($this->affiliateCode == $this->affiliate->affiliate_code){
            $rules['affiliateCode'] = 'required|min:10|max:15|alpha_num';
        }else{
            if($this->checkIfHavePendingPayouts($this->affiliate->affiliate_code)){
                return;
            }

            $rules['affiliateCode'] = 'required|min:10|max:15|alpha_num|unique:affiliates,affiliate_code';
        }

        $validated = $this->validate($rules);

        $this->affiliate->rate = $validated['commissionRate'];
        $this->affiliate->discount = $validated['discount'];
        $this->affiliate->affiliate_code = $validated['affiliateCode'];
        $this->affiliate->status = Status::Active;

        if($this->affiliate->save()){
            $this->notification()->send([
                'icon' => 'success',
                'title' => 'Success!',
                'description' => 'Affilaite has been successfully updated.',
            ]);

            $this->dispatch('close-modal', ['modal' => 'affiliateUpdateFormModal']);
            $this->dispatch('close-modal', ['modal' => 'affiliateTermsAndConditionModal']);
            $this->dispatch('refresh-affiliate-tables');

            Mail::to($this->affiliate->user->email)->send(new AffiliateUpdatePositive($this->affiliate->store->name(), $this->affiliate->user->name()));
        }
    }

    public function checkIfHavePendingPayouts($code){
        $payout = Payout::where('code', $code)->where('status', '<>', Status::PayoutSent)->exists();

        if($payout){
            $this->notification()->send([
                'icon' => 'error',
                'title' => 'Error!',
                'description' => 'Woops, its an error. Please process first this promoter\'s payout request before changing they\'re affiliate code.',
            ]);

            return true;
        }
    }

    public function clearData(){
        $this->reset([
            'affiliate',
            'email',
            'commissionRate',
            'discount',
            'affiliateCode'
        ]);
    }

    public function render()
    {
        return view('livewire.Affiliate.affiliate-update-modal');
    }
}
