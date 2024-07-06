<?php

namespace App\Livewire\Checkout\TotalSummary;

use App\Models\CartItem;
use Livewire\Component;

class OrderTotalSummary extends Component
{
    public function getMerchandiseTotal(){
        $orders = CartItem::groupBySellerCheckout();

        $totalPrices = $orders['totalPrices'];

        return array_sum($totalPrices);
    }

    public function render()
    {
        $merchandiseTotal = $this->getMerchandiseTotal();
        $shippingTotal = 150;

        return view('livewire.Checkout.TotalSummary.order-total-summary', [
            'merchandiseTotal' => $merchandiseTotal,
            'shippingTotal' => $shippingTotal
        ]);
    }
}
