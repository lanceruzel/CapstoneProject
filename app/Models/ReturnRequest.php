<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturnRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'reporter_id',
        'seller_id',
        'order_id',
        'products',
        'tracking_number',
        'courrier',
        'content',
        'images',
        'status',
    ];

    public function order(){
        return $this->belongsTo(Order::class);
    }

    public function reporter(){
        return $this->belongsTo(User::class, 'reporter_id');
    }

    public function seller(){
        return $this->belongsTo(User::class, 'seller_id');
    }
}
