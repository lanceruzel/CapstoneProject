<?php

namespace App\Livewire\Affiliate;

use App\Enums\Status;
use App\Models\Affiliate;
use App\Models\Payout;
use Exception;
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
    public $affiliateCode;

    protected $listeners = [
        'clearaffiliatePayoutRequestFormModalData' => 'clearData',
        'affiliateStore' => 'getStoreID'
    ];

    public function getStoreID($id, $affiliateCode){
        $this->storeID = $id;
        $this->affiliateCode = $affiliateCode;
    }

    public function sendPayout(){
        $validated = $this->validate([
            'amount' => 'required|numeric|min:20|max:1000',
            'paypalEmail' => 'required|email',
            'accountName' => 'required|min:5'
        ]);

        try{
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
                        'code' => $this->affiliateCode,
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
        }catch(Exception $e){
            $this->notification()->send([
                'icon' => 'error',
                'title' => 'Error!',
                'description' => 'Woops, its an error.',
            ]);

            Log::error('Error Payout Form: ' . $e->getMessage());
        }
    }

    public function checkUnclaimedBalance($amount){
        $affiliate = Affiliate::where('affiliate_code', $this->affiliateCode)
        ->first();

        if($affiliate->status == Status::Declined){
            $this->dialog()->show([
                'icon' => 'info',
                'title' => 'Info!',
                'description' => 'Your affiliate is declined. You may contact the store owner for information.',
            ]);

            return false;
        }

        if($affiliate){
            if($amount > $affiliate->unclaimed){
                $this->dialog()->show([
                    'icon' => 'info',
                    'title' => 'Info!',
                    'description' => 'The amount exceeds your unclaimed balance.',
                ]);
                
                return false;
            }

            return true;
        }else{
            return false;
        }
    }
    
    public function clearData(){
        $this->reset([
            'amount',
            'storeID',
            'paypalEmail',
            'accountName',
            'affiliateCode'
        ]);
    }

    public function render()
    {
        return view('livewire.Affiliate.affiliate-payout-form-modal');
    }
}
