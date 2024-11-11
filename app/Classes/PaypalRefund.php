<?php

namespace App\Classes;

class PaypalRefund
{
    private $token = null;

    public function __construct(){
        $client_id = 'ATe6XOxr_O16kSbwVRv-dMnInI2E4BmCD32_GepoFj1irtqU1XwkkkUmegHh21h6-UNhCLwMvNCSAQvo';
        $client_secret = 'EFda-1_Al2NN79tC3kJUB9UC5SSrVWmS7LZPsTZpovkC_4XNQRkdP-U06tb6vQStHj2fD7QU2u_f5NBY';

        // PayPal OAuth URL
        $url = "https://api.sandbox.paypal.com/v1/oauth2/token";

        // Set up the request headers and data
        $headers = [
            "Content-Type: application/x-www-form-urlencoded"
        ];

        $data = [
            'grant_type' => 'client_credentials'
        ];

        // Initialize cURL session
        $ch = curl_init();

        // Set the cURL options
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_USERPWD, $client_id . ':' . $client_secret);

        // Execute cURL and get the response
        $response = curl_exec($ch);

        // Check for cURL errors
        if(curl_errno($ch)){
            dd('cURL Error: ' . curl_error($ch));
            exit;
        }

        // Close cURL session
        curl_close($ch);

        // Decode the JSON response
        $responseData = json_decode($response, true);

        // Check if the token is available
        if (isset($responseData['access_token'])) {
            //Access token
            $this->token = $responseData['access_token'];
        } else {
            // dd("Error: Could not retrieve the access token.");
            dd($responseData);
        }
    }

    public function processRefund($refNumber){
        if($this->token == null){
            return;
        }

        $header = Array(
            "Content-Type: application/json",
            "Authorization: Bearer $this->token",
        );

        $ch = curl_init("https://api.sandbox.paypal.com/v1/payments/sale/$refNumber/refund");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, '{}');
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
       
        // Execute cURL and get the response
        $response = curl_exec($ch);

        // Check for cURL errors
        if(curl_errno($ch)){
            dd('cURL Error: ' . curl_error($ch));
            exit;
        }

        // Close cURL session
        curl_close($ch);

        // Decode the JSON response
        $responseData = json_decode($response, true);

        if($responseData['state'] == 'completed'){
            return true;
        }

        return false;
    }
}
