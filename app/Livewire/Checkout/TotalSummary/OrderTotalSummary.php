<?php

namespace App\Livewire\Checkout\TotalSummary;

use App\Models\CartItem;
use Livewire\Component;

class OrderTotalSummary extends Component
{
    public function getMerchandiseTotal(){
        $orders = CartItem::groupBySellerCheckout();

        $totalPrices = $orders['totalPrices'];

        return number_format(array_sum($totalPrices), 2);
    }

    public function render()
    {
        return view('livewire.Checkout.TotalSummary.order-total-summary', [
            'totalMerchandiseTotal' => $this->getMerchandiseTotal()
        ]);
    }
}
