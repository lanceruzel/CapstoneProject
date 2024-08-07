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
});

Route::get('/signout', function () {
    Auth::logout();
    return redirect()->route('login');
})->middleware('auth')->name('signout');

Route::group(['middleware' => 'auth'], function () {
    Route::get('/', function () {
        return view('livewire.Pages.home');
    })->name('home');

    Route::get('/profile/{username?}', function ($username = null) {
        return view('livewire.Pages.profile', [
            'user' => $username ? User::where('username', $username)->firstOrFail() : auth()->user()
        ]);
    })->name('profile');

    Route::get('/messages/{username?}', function ($username = null) {
        $id = null;

        if ($username) {
            $id = User::where('username', $username)->firstOrFail()->id;

            //Check if convo exists
            if (!Conversation::where(function ($query) use ($id) {
                $query->where('user_1', Auth::id())
                    ->where('user_2', $id);
            })->orWhere(function ($query) use ($id) {
                $query->where('user_1', $id)
                    ->where('user_2', Auth::id());
            })->exists()) {
                //If convo does not exists
                Conversation::create([
                    'user_1' => Auth::id(),
                    'user_2' => $id,
                    'status' => 'active'
                ]);
            }
        } 

        return view('livewire.Pages.message', [
            'id' => $id
        ]);
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

        return view('livewire.Pages.livestream',['role' => $role, 'meetingId' => $livestream->id, 'name' => $livestream->user->name()]);
    })->name('livestream');
});

Route::group(['middleware' => 'role:admin'], function () {
    Route::get('/admin/store-registrations', function () {
        return view('livewire.Pages.store-registrations');
    })->name('admin.store-registrations');

    Route::get('/admin/product-registrations', function () {
        return view('livewire.Pages.product-registrations');
    })->name('admin.product-registrations');

    Route::get('/admin/product-reports', function () {
        return view('livewire.Pages.product-reports');
    })->name('admin.product-reports');

    Route::get('/admin/report-appeals', function () {
        return view('livewire.Pages.report-appeals');
    })->name('admin.report-appeals');
});

Route::group(['middleware' => 'role:store,travelpreneur'], function () {
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
});

