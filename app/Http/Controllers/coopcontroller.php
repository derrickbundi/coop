<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class coopcontroller extends Controller
{
    public function index() {
        $token = self::generateSandBoxToken();
        // dd($token);
        $requestPayload='{
        "MessageReference": "pmDevMP_1CR_1K_20191106",
        "CallBackUrl": "http://7e8e2f9ac25a.ngrok.io/coop/callback",
        "Source": {
            "AccountNumber": "01143007558700",
            "Amount": 777,
            "TransactionCurrency": "KES",
            "Narration": "Payment to supplier"
        },
        "Destinations": [
            {
            "ReferenceNumber": "pmDevMP_1CR_1K_20191106_1",
            "MobileNumber": "254799770833",
            "Amount": 777,
            "Narration": "payment to supplier"
            }
        ]
        }';
        $url = 'https://developer.co-opbank.co.ke:8243/FundsTransfer/External/A2M/Mpesa/v1.0.0';                         
        $headers = array('Content-Type: application/json',"Authorization: Bearer {b6a35764-6c6b-387c-a677-7728dbbf6b4c}");
        $process = curl_init();
        curl_setopt($process, CURLOPT_URL, $url);
        curl_setopt($process, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($process, CURLOPT_POSTFIELDS, $requestPayload);
        curl_setopt($process, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($process, CURLOPT_TIMEOUT, 30);
        curl_setopt($process, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($process, CURLOPT_RETURNTRANSFER, TRUE);
        $return = curl_exec($process);
        curl_close($process);
    }
    public function generateSandboxToken() {
        $consumer_key = config("app.coop_consumer_key");
        $consumer_secret = config("app.coop_consumer_secret");
        $url = 'https://developer.co-opbank.co.ke:8243/token';
        $authorization = base64_encode("$consumer_key:$consumer_secret");
        $header = array("Authorization: Basic {$authorization}");
        $content = "grant_type=client_credentials";
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_HTTPHEADER => $header,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $content
        ));
        $response = curl_exec($curl);
        if ($response === false) {
            echo "Failed";
            echo curl_error($curl);
            echo "Failed";
            exit(0);
        }
        // dd($response);
        $token= json_decode($response)->access_token;
        return $token;
    }
    public function callback(Request $request) {
        $data = file_get_contents('php://input');
        Log::info("Hit this");
        Log::info($data);
    }
}
