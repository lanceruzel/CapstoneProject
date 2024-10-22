<?php

namespace App\Classes;

class Location
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public static function getLocation(){
        $ipaddress = '';
        if (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        } else if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else if (isset($_SERVER['HTTP_X_FORWARDED'])) {
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        } else if (isset($_SERVER['HTTP_FORWARDED_FOR'])) {
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        } else if (isset($_SERVER['HTTP_FORWARDED'])) {
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        } else if (isset($_SERVER['REMOTE_ADDR'])) {
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        } else {
            $ipaddress = 'UNKNOWN';
        }

        if($ipaddress !=  '127.0.0.1'){
            $json = unserialize(file_get_contents("http://www.geoplugin.net/php.gp?ip=$ipaddress"));
            return $json["geoplugin_countryName"];
        }

        return $ipaddress;
    }

    public static function getCountryCode($countryName) {
        $apiUrl = 'https://restcountries.com/v3.1/name/' . urlencode($countryName);
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
    
        $data = json_decode($response, true);
    
        if (isset($data[0]['cca2'])) {
            return $data[0]['cca2']; // Return the 2-letter country code
        }
    
        return null; // Return null if the country code is not found
    }

    public static function getGeolocationCountry($latitude, $longitude){
        $request = 'https://us1.locationiq.com/v1/reverse?key=' . env('LOCATIONIQ_API') . '&lat=' . $latitude . '&lon=' . $longitude . '&format=json&'; 
        $file_contents = file_get_contents($request);
        $json_decode = json_decode($file_contents);

        if(isset($json_decode->address->country)){
            if(isset($json_decode->address->state)){
                return $json_decode->address->state . ', ' . $json_decode->address->country;
            }

            if(isset($json_decode->address->region)){
                return $json_decode->address->region . ', ' . $json_decode->address->country;
            }

            return $json_decode->address->country;
        }

        return 'Uknown';
    }
}
