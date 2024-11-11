<?php

namespace App\Livewire\Livestream;

use App\Classes\Location;
use App\Classes\WordFilter;
use App\Enums\Status;
use App\Models\Livestream;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

class LivestreamFormModal extends Component
{
    use WireUiActions;

    public $title;

    public $location;

    protected $listeners = [
        'room-created' => 'getRoomID',
        'getGeolocation' => 'getGeolocation'
    ];

    public function getRoomID($id = null){
        if($id != null){
            $this->storeLivestream($id);
        }
    }

    public function getGeolocation($latitude, $longitude){
        $this->location = Location::getGeolocationCountry($latitude, $longitude);

        if($this->location == 'Error' || $this->location == 'API key missing'){
            $this->dialog()->show([
                'icon' => 'error',
                'title' => 'Error!',
                'description' => 'Woops, its an error. There seems to be a problem creating your livestream.',
            ]);
        }
    }

    public function storeLivestream($id){
        if($this->location){
            if($this->checkIfHaveExistingLivestreamRoom()){
                $this->dialog()->show([
                    'icon' => 'error',
                    'title' => 'Error!',
                    'description' => 'You must end your current livestream session before starting a new one. Visit your profile’s livestream section to end the active stream.',
                ]);
    
                $this->dispatch('close-modal', ['modal' => 'livestreamFormModal']);
                return;
            }
    
            $validated = $this->validate(['title' => 'required|min:5']);
    
            if($id){
                $livestream = Livestream::create([
                    'id' => $id,
                    'user_id' => Auth::id(),
                    'title' => WordFilter::filteredInput($validated['title']),
                    'status' => 'created',
                    'reactions' => json_encode([
                        '1' => ['count' => 0],
                        '2' => ['count' => 0],
                        '3' => ['count' => 0],
                        '4' => ['count' => 0],
                    ]),
                    'location' => $this->location
                ]);
    
                if($livestream){
                    return redirect()->route('livestream', $id);
                }else{
                    $this->notification()->send([
                        'icon' => 'error',
                        'title' => 'Error!',
                        'description' => 'Woops, its an error. Could not create your livestream id.',
                    ]);
                }
            }else{
                $this->notification()->send([
                    'icon' => 'error',
                    'title' => 'Error!',
                    'description' => 'Woops, its an error. ID not found',
                ]);
            }  
        }else{
            $this->notification()->send([
                'icon' => 'error',
                'title' => 'Error',
                'description' => 'GPS permission is denied. Please enable location services and grant permission.',
            ]);

            $this->dispatch('askLocation');
        }

        
    }

    public function checkIfHaveExistingLivestreamRoom(){
        return Livestream::where('user_id', Auth::id())->where('status', '<>',  'ended')->exists();
    }

    public function render(){
        return view('livewire.Livestream.livestream-form-modal');
    }
}
