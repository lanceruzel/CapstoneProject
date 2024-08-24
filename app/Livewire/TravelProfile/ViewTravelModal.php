<?php

namespace App\Livewire\TravelProfile;

use App\Enums\Status;
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
        
        $this->posts = Post::where('user_id', $userId)->where('status', Status::Available)->where('country', $country)->where('include_compilation', true)->orderBy('id', 'desc')->get();
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
