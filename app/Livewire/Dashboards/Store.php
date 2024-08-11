<?php

namespace App\Livewire\Dashboards;

use App\Livewire\Pages\Orders;
use App\Models\Order;
use App\Models\OrderedItem;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Store extends Component
{
    public function recentOrders(){
        return Order::where('seller_id', Auth::id())->orderBy('id', 'desc')->take(6)->get();
    }

    public function topSoldProduct(){
        $sellerId = Auth::id();
        return OrderedItem::select('product_id', \DB::raw('COUNT(*) as total_count'))
                      ->whereHas('order', function ($query) use ($sellerId) {
                          $query->where('seller_id', $sellerId);
                      })
                      ->groupBy('product_id')
                      ->orderBy('total_count', 'desc')
                      ->with('product') // Eager load the related product
                      ->take(6) // Limit the results to the top 6
                      ->get();
    }

    public function render(){
        return view('livewire.Dashboards.store', [
            'recentOrders' => $this->recentOrders(),
            'topSold' => $this->topSoldProduct()
        ]);
    }
}
