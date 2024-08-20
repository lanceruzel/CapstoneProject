<?php

namespace App\Livewire\TravelProfile;

use App\Models\Post;
use Livewire\Component;

class ViewTravelModal extends Component
{
    public $selectedCountry;
    public $posts;

    protected $listeners = [
        'clearViewTravelModal' => 'clearData',
        'get-travel-info' => 'getData'
    ];

    public function getData($country, $userId){
        $this->selectedCountry = $country;
        
        $this->posts = Post::where('user_id', $userId)->where('country', $country)->where('include_compilation', 1)->orderBy('id', 'desc')->get();
    }

    public function clearData(){
        $this->reset([
            'selectedCountry',
            'posts'
        ]);
    }

    public function render(){
        return view('livewire.TravelProfile.view-travel-modal');
    }
}
