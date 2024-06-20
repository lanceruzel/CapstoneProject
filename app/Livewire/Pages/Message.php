<?php

namespace App\Livewire\Pages;

use App\Models\Conversation;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Message extends Component
{
    public function mount($username){
        // if(Conversation::where(function ($query) use ($id) {
        //     $query->where('user_id', Auth::id())
        //           ->where('receiver_id', $id);
        // })->orWhere(function ($query) use ($id) {
        //     $query->where('user_id', $id)
        //           ->where('receiver_id', Auth::id());
        // })->exists()){
            
        // }else{
        //     Conversation::create([
        //         'user_id' => Auth::id(),
        //         'receiver_id' => $id,
        //         'status' => 'active'
        //     ]);
        // }
    }

    public function render()
    {
        return view('livewire.Pages.message');
    }
}
