<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Affiliate extends Model
{
    use HasFactory;

    protected $fillable = [
        'store_id',
        'promoter_id',
        'affiliate_code',
        'totalCommissioned',
        'rate',
        'status',
    ];

    public function store(){
        return $this->belongsTo(User::class, 'store_id');
    }

    public function user(){
        return $this->belongsTo(User::class, 'promoter_id');
    }

    public function orders(){
        return $this->hasMany(Order::class, 'affiliate_code');
    }
}
