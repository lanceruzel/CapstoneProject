<?php

namespace App\Classes;

class WordFilter
{
    
    private static $blockedWords = [
        'fuck',
        'fvck',
        'putangina',
    ];
    public function __construct()
    {
        //
    }

    public static function filteredInput($value) {
        $inputText = $value;
    
        foreach(self::$blockedWords as $word){
            if(strpos($value, $word) !== false){
                $replacement = str_repeat('*', strlen($word));
                $inputText = str_replace($word, $replacement, $inputText);
            }
        }
    
        return $inputText;
    }
}
