<?php

namespace App\Livewire\Search;

use App\Enums\Status;
use App\Enums\UserType;
use App\Models\Product;
use App\Models\StoreInformation;
use App\Models\UserInformation;
use Livewire\Component;

class SearchContainer extends Component
{
    public $search = '';

    public function render()
    {
        $searchTerm = trim($this->search);

        if (empty($searchTerm)) {
            return view('livewire.Search.search-container', [
                'products' => collect(),
                'users' => collect(),
                'stores' => collect(),
            ]);
        }

        // Use the search term to fetch results if it's not empty
        $searchTerm = '%' . $searchTerm . '%';

        return view('livewire.Search.search-container', [
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