<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'guest'], function(){
    Route::get('/signin', function () {
        return view('pages.signin');
    })->name('signin');

    Route::get('/signup', function () {
        return view('pages.signup');
    })->name('signup');
});

Route::group([], function(){
    Route::get('/', function () {
        return view('livewire.Tabs.home');
    })->name('home');
});

