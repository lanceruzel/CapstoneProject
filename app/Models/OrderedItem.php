<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderedItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'seller_id',
        'order_id',
        'product_id',
        'quantity',
        'subtotal',
    ];

    public function seller(){
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function order(){
        return $this->belongsTo(Order::class);
    }

    public function product(){
        return $this->belongsTo(Product::class);
    }
}
