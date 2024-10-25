<?php

namespace App\Livewire\Affiliate;

use App\Enums\Status;
use App\Models\Affiliate;
use App\Models\Payout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

class AffiliatePayoutFormModal extends Component
{
    use WireUiActions;

    public $amount;
    public $paypalEmail;
    public $accountName;
    public $storeID;

    protected $listeners = [
        'clearaffiliatePayoutRequestFormModalData' => 'clearData',
        'affiliateStore' => 'getStoreID'
    ];

    public function getStoreID($id){
        $this->storeID = $id;
    }

    public function sendPayout(){
        $validated = $this->validate([
            'amount' => 'required|min:20|max:1000|numeric',
            'paypalEmail' => 'required|email',
            'accountName' => 'required|min:5'
        ]);

        if($this->storeID){
            if(!$this->checkUnclaimedBalance($validated['amount'])){
                return;
            }

            try{
                $storePayout = Payout::create([
                    'user_id' => Auth::id(),
                    'requested_to' => $this->storeID,
                    'account_name' => $validated['accountName'],
                    'paypal_email' => $validated['paypalEmail'],
                    'amount' => $validated['amount'],
                    'currency' => 'USD',
                    'status' => Status::PayoutPending
                ]);
    
                if($storePayout){
                    $this->notification()->send([
                        'icon' => 'success',
                        'title' => 'Success!',
                        'description' => 'Your payout request has been successfully sent.',
                    ]);
    
                    $this->dispatch('refresh-affiliate-tables');
                    $this->dispatch('close-modal', ['modal' => 'affiliatePayoutRequestForm']);
                }
            }catch(\Exception $e){
                $this->notification()->send([
                    'icon' => 'error',
                    'title' => 'Error!',
                    'description' => 'Woops, its an error.',
                ]);
    
                Log::error('Error on affiliate payout modal form: ' . $e->getMessage());
            }
        }
    }

    public function checkUnclaimedBalance($amount){
        $affiliate = Affiliate::where('store_id', $this->storeID)->where('promoter_id', Auth::id())->where('status', Status::Active)->first();
    
        if ($affiliate && ($amount > $affiliate->unclaimed)){
            $this->addError('amount', 'The amount exceeds your unclaimed balance.');
            return false;
        }
    
        return true;
    }
    
    public function clearData(){
        $this->reset([
            'amount',
            'storeID',
            'accountName'
        ]);
    }

    public function render()
    {
        return view('livewire.Affiliate.affiliate-payout-form-modal');
    }
}
