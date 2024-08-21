<?php

namespace App\Classes;

class CustomDateTimeFormat
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public static function formatAgo($date){
        $seconds_ago = (time() - strtotime($date));
        $dateTimeDisplay = '';

        if ($seconds_ago >= 31536000) {
            $dateTimeDisplay = date_format($date, "M d, Y");
        } elseif ($seconds_ago >= 2419200) {
            $dateTimeDisplay = date_format($date, "M d, Y");
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
}
