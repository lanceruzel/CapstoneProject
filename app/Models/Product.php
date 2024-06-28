<?php

namespace App\Models;

use App\Enums\Status;
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

        return count($prices) === 1 ? '$' . number_format($prices[0], 2) : '$' . number_format(min($prices), 2) . ' ~ ' . '$' . number_format(max($prices), 2);
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

    public function getTotalRatings(){
        $totalRating = 0;

        foreach ($this->feedbacks as $feedback) {
            $totalRating += $feedback->rating;
        }
    
        return $totalRating;
    }

    public function getTotalFeedback(){
        return count($this->feedbacks);
    }

    public function getAverageRate(){
        $totalRating = 0;
        $feedbackCount = count($this->feedbacks);
        
        foreach ($this->feedbacks as $feedback) {
            $totalRating += $feedback->rating;
        }

        return number_format(($feedbackCount > 0 ? $totalRating / $feedbackCount : 0), 2, '.', '');
       
    }

    public function getRatingStars(?string $size = null){
        $stars = '';
        $averageRating = $this->getAverageRate();
    
        $product_roundedRating = round($averageRating * 2) / 2; // Round to the nearest 0.5
        $product_fullStars = floor($product_roundedRating); // Get the integer part of the rating
        $product_halfStar = $product_roundedRating - $product_fullStars == 0.5; // Check if there's a half star
    
        for($i = 1; $i <= 5; $i++){
            if($i <= $product_fullStars){
                $stars .= "<i class='ri-star-fill text-yellow-300 ri-{$size}'></i>";
            }else if($product_halfStar && $i == $product_fullStars + 1){
                $stars .= "<i class='ri-star-half-fill text-yellow-300 ri-{$size}'></i>";
            }else{
                $stars .= "<i class='ri-star-fill text-gray-500 ri-{$size}'></i>";
            }
        }
    
        return $stars; // Return the generated HTML string
    }

    public function getSoldCount(){
        return $this->orderItems()
                ->whereHas('order', function ($query) {
                    $query->where('status', Status::Delivered);
                })
                ->count();
    }
}
