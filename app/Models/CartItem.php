<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class CartItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'seller_id',
        'product_id',
        'quantity',
        'variation',
        'for_checkout',
    ];

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function seller(){
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function product(){
        return $this->belongsTo(Product::class);
    }

    public function getTotalPrice(){
        $price = 0;

        foreach(json_decode($this->product->variations) as $variation){
            if($variation->name == $this->variation){
                $price = $variation->price * $this->quantity;
            }
        }

        return $price;
    }

    public static function groupBySeller()
    {
        $cartItems = self::where('user_id', Auth::id())->get();

        $groupedItems = [];

        foreach ($cartItems as $item) {
            $groupedItems[$item->seller->storeInformation->name][] = $item;
        }

        return $groupedItems;
    }

    // public static function groupBySellerCheckout()
    // {
    //     $cartItems = self::where('user_id', Auth::id())->where('for_checkout', true)->get();

    //     $groupedItems = [];

    //     foreach ($cartItems as $item) {
    //         $item->total_price = $item->getTotalPrice();
    //         $groupedItems[$item->seller->storeInformation->name][] = $item;
    //     }

    //     return $groupedItems;
    // }

    public static function groupBySellerCheckout(){
        $cartItems = self::where('user_id', Auth::id())->where('for_checkout', true)->get();

        $groupedItems = [];
        $totalPrices = [];

        foreach ($cartItems as $item) {
            $totalPrice = $item->getTotalPrice();
            $groupedItems[$item->seller->storeInformation->name][] = $item;

            if (!isset($totalPrices[$item->seller->storeInformation->name])) {
                $totalPrices[$item->seller->storeInformation->name] = 0;
            }
            $totalPrices[$item->seller->storeInformation->name] += $totalPrice;
        }

        return ['orders' => $groupedItems, 'totalPrices' => $totalPrices];
    }
}

