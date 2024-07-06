<?php

namespace App\Livewire\Checkout\TotalSummary;

use App\Models\CartItem;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

class OrderTotalSummary extends Component
{
    use WireUiActions;

    public $merchandiseTotal = 0;
    public $shippingTotal = 150;

    protected $listeners = [
        'payment-completed' => 'placeOrder'
    ];

    public function placeOrder($status = null){
        $checkedOutSellers = CartItem::groupBySellerCheckout();

        if($status && $status == 'COMPLETED'){ //Paypal
            
        }else{ // COD
            foreach($checkedOutSellers as $checkedOutSeller){
                dd($checkedOutSeller);
            }
        }
    }

    public function getMerchandiseTotal(){
        $total = 0;

        foreach(CartItem::groupBySellerCheckout() as $item){
            $total += $item['total'];
        }

        return $total;
    }

    public function render()
    {
        $this->merchandiseTotal = $this->getMerchandiseTotal();

        return view('livewire.Checkout.TotalSummary.order-total-summary', [
            'merchandiseTotal' => $this->merchandiseTotal,
            'shippingTotal' => $this->shippingTotal
        ]);
    }
}
