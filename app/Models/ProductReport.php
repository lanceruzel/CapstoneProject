<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'reporter_id',
        'user_id',
        'content',
        'images',
    ];

    public function account(){
        return $this->belongsTo(User::class, 'reporter_id');
    }

    public function reportAppeal(){
        return $this->hasOne(ReportAppeal::class);
    }

    public function product(){
        return $this->belongsTo(Product::class);
    }
}
