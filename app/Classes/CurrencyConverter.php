<?php

namespace App\Classes;

class CurrencyConverter{
    public static $currencyData = null;


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

        $url = "https://api.exchangerate-api.com/v4/latest/usd";
        $response = file_get_contents($url);

        $data = json_decode($response, true);

        if (isset($data['rates'])) {
            foreach ($data['rates'] as $currency => $rate) {
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

        return $currencies; 
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