<?php

namespace App\Models;

use App\Enums\Status;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class OrderedItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'variation',
        'quantity',
        'subtotal',
    ];

    public function order(){
        return $this->belongsTo(Order::class);
    }

    public function product(){
        return $this->belongsTo(Product::class);
    }

    public function hasFeedback(){
        $userId = Auth::id();
        $orderStatus = Status::OrderBuyerReceived;

        return $this->whereHas('order', function ($query) use ($orderStatus) {
                    $query->where('status', $orderStatus);
                })
                ->whereHas('product.feedbacks', function ($query) use ($userId) {
                    $query->where('user_id', $userId);
                })
                ->where('product_id', $this->product_id)
                ->exists();
    }

    public function hasReport(){
        $userId = Auth::id();
        $orderStatus = Status::OrderBuyerReceived;

        return $this->whereHas('order', function ($query) use ($orderStatus) {
                    $query->where('status', $orderStatus);
                })
                ->whereHas('product.reports', function ($query) use ($userId) {
                    $query->where('reporter_id', $userId);
                })
                ->where('product_id', $this->product_id)
                ->exists();
    }
}
