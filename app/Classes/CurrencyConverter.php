<?php

namespace App\Classes;

use DateTime;
use DateTimeZone;

class CurrencyConverter{
    public static $currencyData = null;
    public static $date = null;


    public function __construct(){
        // Only fetch currency data if it's not already cached
        if (self::$currencyData === null) {
            self::$currencyData = $this->getCurrencyData();
        }
    }

    public static function loadCurrencyData()
    {
        if (self::$currencyData === null) {
            self::$currencyData = self::getCurrencyData();
        }
    }

    public static function getRate($currency){
        self::loadCurrencyData();

        foreach (self::$currencyData as $item) {
            if ($item['currency'] == $currency) {
                return $item['rate'];
            }
        }
        return null;
    }

    public static function getCurrencyData(){
        $currencies = [];

        $url = "https://v6.exchangerate-api.com/v6/". env("EXCHANGE_RATE_API", "") ."/latest/USD";
        $response = file_get_contents($url);

        $data = json_decode($response, true);

        if(isset($data['conversion_rates'])) {
            foreach ($data['conversion_rates'] as $currency => $rate) {
                if (in_array($currency, [
                    'USD',
                    'PHP',
                    'EUR',
                    'JPY',
                    'KRW',
                ])) {
                    $currencies[] = [
                        'currency' => $currency,
                        'rate' => $rate
                    ];
                }
            }
        }

        if(isset($data['time_last_update_utc'])){
            self::$date = self::epochToDate($data['time_last_update_utc']);
        }

        return $currencies; 
    }

    public static function epochToDate($epoch){
        $dt = new DateTime("$epoch");
        $dt->setTimezone(new DateTimeZone('GMT+8'));

        return $dt->format('M d, Y') . ' 12:00 AM GMT+8';
    }

    public static function formatPrice($amount){
        $currency = auth()->user()->currency;
        $moneySign = '$';

        switch($currency){
            case 'USD':
                $moneySign = '$';
                break;
            case 'PHP':
                $moneySign = '₱';
                break;
            case 'EUR':
                $moneySign = '€';
                break;
            case 'JPY':
                $moneySign = '¥';
                break;
            case 'KRW':
                $moneySign = '₩';
                break;
        }

        return $moneySign . number_format($amount * self::getRate($currency), 2);
    }
}