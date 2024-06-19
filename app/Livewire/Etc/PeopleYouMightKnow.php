<?php

namespace App\Livewire\Etc;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class PeopleYouMightKnow extends Component
{
    public function render()
    {
        return view('livewire.Etc.people-you-might-know', [
            'users' => User::where('role', '<>', 'admin')->where('role', '<>', 'store')->where('id', '<>', Auth::id())->inRandomOrder()->limit(5)->get()
        ]);
    }
}
