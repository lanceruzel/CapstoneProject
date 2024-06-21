<?php

use App\Models\Conversation;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'guest'], function () {
    Route::get('/signin', function () {
        return view('livewire.Pages.signin');
    })->name('signin');

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
    return redirect()->route('signin');
})->name('signout');

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
});

Route::group([], function () {
    Route::get('/admin/store-registrations', function () {
        return view('livewire.Pages.store-registrations');
    })->name('admin.store-registrations');
});
