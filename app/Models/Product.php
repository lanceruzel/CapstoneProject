<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'seller_id',
        'name',
        'category',
        'description',
        'variations',
        'images',
        'status',
        'remarks',
    ];

    public function totalStocks(){
        $totalStocks = 0;

        foreach (json_decode($this->variations) as $variation) {
            $totalStocks += $variation->stocks;
        }

        return $totalStocks;
    }

    public function priceRange(){
        $prices = [];

        foreach (json_decode($this->variations) as $variation) {
            $prices[] += (float) $variation->price;
        }

        return count($prices) === 1 ? '$' . $prices[0] : '$' . min($prices) . ' ~ ' . '$' . max($prices);
    }

    public function seller(){
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function feedbacks(){
        return $this->hasMany(ProductFeedback::class);
    }

    public function orderItems(){
        return $this->hasMany(OrderedItem::class);
    }

    public function appeal(){
        return $this->hasOne(ReportAppeal::class);
    }

    public function reports(){
        return $this->hasMany(ProductReport::class);
    }

    public function posts(){
        return $this->hasMany(Post::class);
    }
}
