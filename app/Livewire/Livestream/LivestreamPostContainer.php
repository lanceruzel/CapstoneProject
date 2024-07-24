<?php

namespace App\Livewire\Livestream;

use App\Models\Livestream;
use Livewire\Component;

class LivestreamPostContainer extends Component
{
    public $livestream;

    public function mount(Livestream $livestream){
        $this->livestream = $livestream;
    }

    public function getDateTimeDiff(){
        $seconds_ago = (time() - strtotime($this->livestream->created_at));
        $dateTimeDisplay = '';

        if ($seconds_ago >= 31536000) {
            $dateTimeDisplay = intval($seconds_ago / 31536000) . " years ago";
        } elseif ($seconds_ago >= 2419200) {
            $dateTimeDisplay = intval($seconds_ago / 2419200) . " months ago";
        } elseif ($seconds_ago >= 86400) {
            $dateTimeDisplay = intval($seconds_ago / 86400) . " days ago";
        } elseif ($seconds_ago >= 3600) {
            $dateTimeDisplay = intval($seconds_ago / 3600) . " hours ago";
        } elseif ($seconds_ago >= 120) {
            $dateTimeDisplay = intval($seconds_ago / 60) . " minutes ago";
        } elseif ($seconds_ago >= 60) {
            $dateTimeDisplay = "1 minute ago";
        } else {
            $dateTimeDisplay = "Less than a minute ago";
        }

        return $dateTimeDisplay;
    }

    public function render(){
        return view('livewire.Livestream.livestream-post-container');
    }
}
