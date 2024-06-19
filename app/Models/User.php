<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Enums\UserType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'email',
        'role',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function notifications(){
        return $this->hasMany(Notification::class);
    }

    public function storeInformation(){
        if($this->role === UserType::Store || $this->role === UserType::Travelpreneur){
            return $this->hasOne(StoreInformation::class);
        }

        return null;
    }

    public function userInformation(){
        if($this->role !== UserType::Store){
            return $this->hasOne(UserInformation::class);
        }

        return null;
    }

    public function posts(){
        return $this->hasMany(Post::class);
    }

    public function postLikes(){
        return $this->hasMany(PostLike::class);
    }

    public function postComments(){
        return $this->hasMany(PostComment::class);
    }

    public function conversations(){
        return $this->hasMany(Conversation::class);
    }

    public function messages(){
        return $this->hasMany(Message::class);
    }

    public function cartItems(){
        return $this->hasMany(CartItem::class);
    }

    public function orders(){
        return $this->hasMany(Order::class);
    }

    public function orderedItems(){
        return $this->hasMany(OrderedItem::class);
    }

    public function productFeedbacks(){
        return $this->hasMany(ProductFeedback::class);
    }

    public function savedShippings(){
        return $this->hasMany(UserShippingInformation::class);
    }

    public function productReports(){
        return $this->hasMany(ProductReport::class);
    }
}
