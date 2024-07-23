<?php

namespace App\Livewire\Livestream;

use App\Enums\Status;
use App\Models\Livestream;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

class LivestreamFormModal extends Component
{
    use WireUiActions;

    public $title;

    protected $listeners = [
        'room-created' => 'getRoomID'
    ];

    public function getRoomID($id = null){
        if($id != null){
            $this->storeLivestream($id);
        }
    }

    public function storeLivestream($id){
        $validated = $this->validate(['title' => 'required|min:5']);

        if($id){
            $livestream = Livestream::create([
                'id' => $id,
                'user_id' => Auth::id(),
                'title' => $validated['title'],
                'status' => Status::Preparing
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
    }

    public function render(){
        return view('livewire.Livestream.livestream-form-modal');
    }
}
