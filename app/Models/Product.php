<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'seller_id',
        'name',
        'category',
        'description',
        'price',
        'stocks',
        'images',
        'status',
        'remarks',
    ];

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
