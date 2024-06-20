<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'guest'], function(){
    Route::get('/signin', function () {
        return view('livewire.Pages.signin');
    })->name('signin');

    Route::get('/signup', function () {
        return view('livewire.Pages.signup');
    })->name('signup');
});

Route::get('/signout', function () {
    Auth::logout();
    return redirect()->route('signin');
})->name('signout');

Route::group([], function(){
    Route::get('/', function () {
        return view('livewire.Pages.home');
    })->name('home');
    
    Route::get('/profile/{username?}', function ($username = null) {
        return view('livewire.Pages.profile' , [
            'user' => $username ? User::where('username', $username)->firstOrFail() : auth()->user()
        ]);
    })->name('profile');

    Route::get('/messages/{username?}', function ($username = null) {
        return view('livewire.Pages.message');
    })->name('message');
});

