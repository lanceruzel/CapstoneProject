<?php

namespace App\Livewire\Order\Return;

use App\Enums\Status;
use App\Models\ReturnRequest;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ReturnOrdersContainer extends Component
{
    public function render()
    {
        return view('livewire.Order.Return.return-orders-container', [
            'orders' => ReturnRequest::where('reporter_id', Auth::id())->where('status', '<>', Status::ReturnRequestReview)->get()
        ]);
    }
}
