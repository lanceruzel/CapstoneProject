<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportAppeal extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'conversation_id'
    ];

    public function conversation(){
        return $this->belongsTo(Conversation::class);
    }

    public function product(){
        return $this->belongsTo(Product::class);
    }
}
