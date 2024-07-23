<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Enums\UserType;
use App\Livewire\Pages\Affiliates;
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

    public function profilePicture(){
        $dp = null;

        if($this->role == UserType::Store){
            $dp = $this->storeInformation->profile_picture;
        }else{
            $dp = $this->userInformation->profile_picture;
        }

        return $dp;
    }

    public function name(){
        $name = null;

        if($this->role == UserType::Store){
            $name = $this->storeInformation->name;
        }else{
            $name = $this->userInformation->fullname();
        }

        return $name;
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

    public function products(){
        return $this->hasMany(Product::class, 'seller_id');
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

    public function affiliates(){
        if($this->role === UserType::ContentCreator){
            return $this->hasMany(Affiliate::class, 'promoter_id');
        }else{
            return $this->hasMany(Affiliate::class, 'store_id');
        }
    }
}


