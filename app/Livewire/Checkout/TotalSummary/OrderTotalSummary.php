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
        'payment-completed' => 'test'
    ];

    public function test($status){
        $prices = $this->getMerchandiseTotal();


        if($status == 'COMPLETED'){
            


        }
    }

    public function getMerchandiseTotal(){
        $orders = CartItem::groupBySellerCheckout();

        $totalPrices = $orders['totalPrices'];

        return $totalPrices;
    }

    public function render()
    {
        $this->merchandiseTotal = array_sum($this->getMerchandiseTotal());

        return view('livewire.Checkout.TotalSummary.order-total-summary', [
            'merchandiseTotal' => $this->merchandiseTotal,
            'shippingTotal' => $this->shippingTotal
        ]);
    }
}
