<?php

namespace App\Classes;

class WordFilter
{
    
    private static $blockedWords = [
        'fuck',
        'fvck',
        'bitch',
        'asshole',
        'shit',
        'cunt',
        'bastard',
        'whore',
        'dick',
        'pussy',
        'motherfucker',
        'cock',
        'fag',
        'slut',
        'douchebag',
        'retard',
        'gaylord',
        'nigger',
        'chink',
        'spic',
        'kike',
        'gook',
        'wop',
        'sandnigger',
        'coon',
        'tranny',
        'sexist',
        'rape',
        'incest',
        'pedophile',
        'zoophilia',
        'scum',
        'junkie',
        'crackhead',
        'bitchass',
        'cockhead',
        'dumbass',
        'fucking',
        'fucks',
        'stfu',
        'suckmydick',
        'eatshit',
        'fistfuck',
        'shithead',
        'twat',
        'piss',
        'bimbo',
        'maggot',
        'whorehouse',
        'cocksucker',
        'faggot',
        'motherfucking',
        'fuckedup',
        'retarded',
        'blowjob',
        'handjob',
        'asslicker',
        'shitfaced',
        'bullshit',
        'cockblock',
        'cumshot',
        'sexist',
        'blowme',
        'hooker',
        'sexslave',
        'slutty',
        'cumdumpster',
        'pimp',
        'prostitute',
        'jerkoff',
        'masturbation',
        'tits',
        'boobs',
        'ballsack',
        'nipples',
        'putangina',
        'puta',
        'gago',
        'ulol',
        'bobo',
        'tanga',
        'bitch',
        'linta',
        'bayot',
        'tangina',
        'engot',
        'yawa',
        'pabibo',
        'pakamatay',
        'putangbabae',
        'pwedeka',
        'burat',
        'bangag',
        'mangmang',
        'luko-luko',
        'chupa',
        'tarantado',
        'siraulo',
        'imbecile',
        'sundot',
        'laki-ng-buto',
        'buwisit',
        'kalaswaan',
        'salot',
        'mamatayka',
        'paksyet',
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
