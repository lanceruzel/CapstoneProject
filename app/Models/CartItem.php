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

    public static function groupBySeller(){
        $cartItems = self::where('user_id', Auth::id())->get();

        $groupedItems = [];

        foreach ($cartItems as $item) {
            $sellerName = $item->seller->storeInformation->name;
            if (!isset($groupedItems[$sellerName])) {
                $groupedItems[$sellerName] = [
                    'seller' => $item->seller,
                    'items' => [],
                ];
            }
            $groupedItems[$sellerName]['items'][] = $item;
        }

        return $groupedItems;
    }

    public static function groupBySellerCheckout(){
        $cartItems = self::where('user_id', Auth::id())->where('for_checkout', true)->get();
    
        $groupedItems = [];
        $totalPrices = [];
    
        foreach ($cartItems as $item) {
            $sellerName = $item->seller->storeInformation->name;
            $totalPrice = $item->getTotalPrice();
    
            if (!isset($groupedItems[$sellerName])) {
                $groupedItems[$sellerName] = [
                    'seller' => $item->seller,
                    'items' => [],
                ];
                $totalPrices[$sellerName] = 0;
            }
    
            $groupedItems[$sellerName]['items'][] = $item;
            $totalPrices[$sellerName] += $totalPrice;
        }
    
        return ['orders' => $groupedItems, 'totalPrices' => $totalPrices];
    }
}

