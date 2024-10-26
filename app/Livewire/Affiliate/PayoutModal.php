<?php

namespace App\Livewire\Affiliate;

use App\Enums\Status;
use App\Enums\UserType;
use App\Models\Affiliate;
use App\Models\Payout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

class PayoutModal extends Component
{
    use WireUiActions;

    public $payout;
    public $reference;

    protected $listeners = [
        'clearPayoutModalData' => 'clearData',
        'view-payout-request' => 'getData'
    ];

    public function getData($id){
        $this->payout = Payout::find($id);
    }

    public function confirmUpdate(){
        $this->dialog()->confirm([
            'title' => 'Are you Sure?',
            'description' => 'Updating this payout will also mean that you have successfully sent the money. Do you confirm?',
            'acceptLabel' => 'Yes, update it',
            'method' => 'update',
            'params' => '',
        ]);
    }

    public function update(){
        $validated = $this->validate([
            'reference' => 'required|min:10'
        ]);

        try{
            $this->payout->reference_id = $validated['reference'];
            $this->payout->status = Status::PayoutSent;
        
            if(auth()->user()->role == UserType::Store){
                $this->deductAffiliate($this->payout->user_id, $this->payout->amount);
            }

            if($this->payout->save()){
                $this->notification()->send([
                    'icon' => 'success',
                    'title' => 'Success!',
                    'description' => 'Successfully Updated.',
                ]);

                $this->dispatch('refresh-payout-tables');
            }
        }catch(\Exception $e){
            $this->notification()->send([
                'icon' => 'error',
                'title' => 'Error!',
                'description' => 'Woops, its an error.',
            ]);

            Log::error('Error updating payout on payout modal: ' . $e->getMessage());
        }
    }

    public function deductAffiliate($userID, $amount){
        $affiliate = Affiliate::where('store_id', Auth::id())
            ->where('promoter_id', $userID)
            ->where('status', '<>', Status::Declined)
            ->first();

        if($affiliate){
            $affiliate->unclaimed -= $amount;
            $affiliate->save();
        }
    }

    public function clearData(){
        $this->reset([
            'payout',
            'reference'
        ]);
    }

    public function render()
    {
        return view('livewire.Affiliate.payout-modal');
    }
}
