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

    public static function groupBySeller()
    {
        $cartItems = self::where('user_id', Auth::id())->get();

        $groupedItems = [];

        foreach ($cartItems as $item) {
            $groupedItems[$item->seller->storeInformation->name][] = $item;
        }

        return $groupedItems;
    }
}

