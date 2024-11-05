<?php

use App\Models\Conversation;
use App\Models\Livestream;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'guest'], function () {
    Route::get('/signin', function () {
        return view('livewire.Pages.signin');
    })->name('login');

    Route::get('/signup/{type?}', function ($type = null) {
        return view('livewire.Pages.signup', [
            'type' => $type
        ]);
    })->name('signup');

    Route::get('/signup-store', function () {
        return view('livewire.Pages.store-signup');
    })->name('store-signup');

    Route::get('/forgot-password', function () {
        return view('livewire.Pages.forgot-password');
    })->name('password.request');

    Route::get('/reset-password/{token}', function (string $token) {
        return view('livewire.Pages.reset-password', ['token' => $token]);
    })->middleware('guest')->name('password.reset');
});

Route::get('/signout', function () {
    Auth::logout();
    return redirect()->route('login');
})->middleware('auth')->name('signout');

Route::group(['middleware' => 'role:store,travelpreneur,content-creator'], function () {
    Route::get('/', function () {
        return view('livewire.Pages.home');
    })->name('home');

    Route::get('/profile/{username?}', function ($username = null) {
        return view('livewire.Pages.profile', [
            'user' => $username ? User::where('username', $username)->firstOrFail() : auth()->user()
        ]);
    })->name('profile');

    Route::get('/messages/{username?}', function ($username = null) {
        $authUserId = Auth::id();
        
        $id = null;
 
        if($username){
            $id = User::where('username', $username)->first()->id;
            
            if($id){
                $conversationExists = Conversation::where(function ($query) use ($authUserId, $id) {
                    $query->where('user_1', $authUserId)
                        ->where('user_2', $id);
                })->orWhere(function ($query) use ($authUserId, $id) {
                    $query->where('user_1', $id)
                        ->where('user_2', $authUserId);
                })->first();

                if(!$conversationExists) {
                    $conversation = Conversation::create([
                        'user_1' => $authUserId,
                        'user_2' => $id,
                        'status' => 'active'
                    ]);

                    $id = $conversation->id;
                }else{
                    $id = $conversationExists->id;
                }
            }

        }else{
            $conversation = Conversation::where(function($query) use ($authUserId) {
                $query->where('user_1', '<>', 1)
                ->where('user_2', '<>', 1)
                ->where(function($query) use ($authUserId) {
                    $query->where('user_1', $authUserId)
                          ->orWhere('user_2', $authUserId);
                });
            })->where('last_message_id', '<>', '')
            ->orderBy('updated_at', 'desc')->first();

            if($conversation){
                $id = $conversation->id;
            }
        }

        return view('livewire.Pages.message', ['id' => $id]);
    })->name('message');

    Route::get('/market', function () {
        return view('livewire.Pages.market');
    })->name('market');

    Route::get('/cart', function () {
        return view('livewire.Pages.cart');
    })->name('cart');

    Route::get('/orders', function () {
        return view('livewire.Pages.orders');
    })->name('orders');

    Route::get('/checkout', function () {
        return view('livewire.Pages.checkout');
    })->name('checkout');

    Route::get('/livestream/{id?}', function ($id = null) {
        $role = null;
        $livestream = null;
        $userId = null;

        if($id != null){
            $livestream = Livestream::findOrFail($id);
            $userId = $livestream['user_id'];

            if($userId == Auth::id()){
                $role = 'host';
            }else{
                $role = 'viewer';
            }
        }
        
        return view('livewire.Pages.livestream',['id' => $livestream->id, 'role' => $role]);
    })->name('livestream');
});

Route::group(['middleware' => 'role:admin'], function () {
    Route::get('/admin/dashboard', function () {
        return view('livewire.Pages.admin-dashboard');
    })->name('admin.dashboard');

    Route::get('/admin/user-management', function () {
        return view('livewire.Pages.user-management');
    })->name('admin.user-management');

    Route::get('/admin/store-registrations', function () {
        return view('livewire.Pages.store-registrations');
    })->name('admin.store-registrations');

    Route::get('/admin/product-registrations', function () {
        return view('livewire.Pages.product-registrations');
    })->name('admin.product-registrations');

    Route::get('/admin/reports', function () {
        return view('livewire.Pages.reports');
    })->name('admin.reports');

    Route::get('/admin/report-appeals', function () {
        return view('livewire.Pages.report-appeals');
    })->name('admin.report-appeals');
});

Route::group(['middleware' => 'role:store,travelpreneur'], function () {
    Route::get('/store/dashboard', function () {
        return view('livewire.Pages.store-dashboard');
    })->name('store.dashboard');

    Route::get('/store/affiliates', function () {
        return view('livewire.Pages.affiliates');
    })->name('store.affiliates');

    Route::get('/store/product-management', function () {
        return view('livewire.Pages.product-management');
    })->name('store.product-management');

    Route::get('/store/return-products', function () {
        return view('livewire.Pages.return-products');
    })->name('store.return-products');

    Route::get('/store/order-management', function () {
        return view('livewire.Pages.order-management');
    })->name('store.order-management');

    Route::get('/store/ordered-products', function () {
        return view('livewire.Pages.ordered-product');
    })->name('store.ordered-products');

    Route::get('/store/payouts', function () {
        return view('livewire.Pages.store-payoutRequests');
    })->name('store.payouts');
});

