<?php

namespace App\Livewire\Etc;

use App\Enums\Status;
use App\Enums\UserType;
use App\Models\Product;
use App\Models\StoreInformation;
use App\Models\UserInformation;
use Livewire\Component;

class NavbarSearch extends Component
{
    public $search = '';

    public function render()
    {
        $searchTerm = trim($this->search);

        if(empty($searchTerm)) {
            return view('livewire.Etc.navbar-search', [
                'products' => collect(),
                'users' => collect(),
                'stores' => collect(),
            ]);
        }

        $searchTerm = '%' . $searchTerm . '%';

        return view('livewire.Etc.navbar-search', [
            'products' => Product::where('name', 'like', $searchTerm)
                                ->where('status', Status::Available)
                                ->get(),
            'users' => UserInformation::where(function($query) use ($searchTerm) {
                                $query->where('first_name', 'like', $searchTerm)
                                      ->orWhere('last_name', 'like', $searchTerm);
                            })->whereHas('user', function ($query) {
                                $query->where('role', '<>', UserType::Admin);
                            })->get(),
            'stores' => StoreInformation::where('name', 'like', $searchTerm)->get(),
        ]);
    }
}
