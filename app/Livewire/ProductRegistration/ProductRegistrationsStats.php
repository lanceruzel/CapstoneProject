<?php

namespace App\Livewire\ProductRegistration;

use App\Enums\Status;
use App\Models\Product;
use Livewire\Component;

class ProductRegistrationsStats extends Component
{
    public function render(){
        $totalProducts = Product::where('id', '<>', 1)->count();
        $productCounts = Product::selectRaw('status, COUNT(*) as count')
            ->whereIn('status', [Status::Available, Status::ForReview, Status::Suspended])
            ->groupBy('status')
            ->pluck('count', 'status');

        return view('livewire.ProductRegistration.product-registrations-stats',[
            'totalProducts' => $totalProducts,
            'totalAvailable' => $productCounts[Status::Available] ?? 0,
            'totalForReview' => $productCounts[Status::ForReview] ?? 0,
            'totalSuspended' => $productCounts[Status::Suspended] ?? 0,
        ]);
    }
}
