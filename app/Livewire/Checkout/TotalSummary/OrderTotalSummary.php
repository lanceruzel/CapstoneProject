<?php

namespace App\Livewire\Checkout\TotalSummary;

use App\Models\CartItem;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

class OrderTotalSummary extends Component
{
    use WireUiActions;

    public $merchandiseTotal = 0;
    public $shippingTotal = 0;

    protected $listeners = [
        'payment-completed' => 'test'
    ];

    public function test($status){

        

        $this->notification()->send([
            'icon' => 'error',
            'title' => 'Error Notification!',
            'description' => 'Woops,' . $status,
        ]);
    }

    public function getMerchandiseTotal(){
        $orders = CartItem::groupBySellerCheckout();

        $totalPrices = $orders['totalPrices'];

        return array_sum($totalPrices);
    }

    public function render()
    {
        $this->merchandiseTotal = $this->getMerchandiseTotal();
        $this->shippingTotal = 150;

        return view('livewire.Checkout.TotalSummary.order-total-summary', [
            'merchandiseTotal' => $this->merchandiseTotal,
            'shippingTotal' => $this->shippingTotal
        ]);
    }
}
