<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'guest'], function(){
    Route::get('/signin', function () {
        return view('livewire.Pages.signin');
    })->name('signin');

    Route::get('/signup', function () {
        return view('livewire.Pages.signup');
    })->name('signup');
});

Route::group([], function(){
    Route::get('/', function () {
        return view('livewire.Tabs.home');
    })->name('home');
});

