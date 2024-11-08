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

    public function getTotal(){
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
                    'products' => [],
                    'latest_cart_item_created_at' => $item->created_at,
                ];
            }
            $groupedItems[$sellerName]['products'][] = $item;
    
            if ($item->created_at > $groupedItems[$sellerName]['latest_cart_item_created_at']) {
                $groupedItems[$sellerName]['latest_cart_item_created_at'] = $item->created_at;
            }
        }
    
        foreach ($groupedItems as &$group) {
            usort($group['products'], function ($a, $b) {
                return $b->product->created_at <=> $a->product->created_at;
            });
        }
    
        uasort($groupedItems, function ($a, $b) {
            return $b['latest_cart_item_created_at'] <=> $a['latest_cart_item_created_at'];
        });
    
        return $groupedItems;
    }

    public static function groupBySellerCheckout(){
        $cartItems = self::where('user_id', Auth::id())->where('for_checkout', true)->get();
    
        $groupedItems = [];
    
        foreach ($cartItems as $item) {
            $sellerName = $item->seller->storeInformation->name;
            $total = $item->getTotal();
    
            if (!isset($groupedItems[$sellerName])) {
                $groupedItems[$sellerName] = [
                    'seller' => $item->seller,
                    'products' => [],
                    'total' => 0,
                ];
            }
    
            $groupedItems[$sellerName]['products'][] = $item;
            $groupedItems[$sellerName]['total'] += $total;
        }
    
        return $groupedItems;
    }

    public static function deleteCheckoutItems(){
        self::where('user_id', Auth::id())
            ->where('for_checkout', true)
            ->delete();
    }
}

