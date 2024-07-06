<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'seller_id',
        'name',
        'address',
        'postal',
        'contact',
        'total',
        'payment_method',
        'tracking_number',
        'status',
        'is_paid'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function seller(){
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function orderedItems(){
        return $this->hasMany(OrderedItem::class);
    }
}
