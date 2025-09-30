<?php

namespace App\Helpers;

use App\Models\ApiLog;
use Illuminate\Support\Facades\Auth;
class Permission
{

    public static function curl($url, $method = 'POST', $parameters, $header, $log = "no", $modal = "none", $txnid = "none")
    {
        $startTime = microtime(true); 
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_MAXREDIRS, 10);
        curl_setopt($curl, CURLOPT_ENCODING, "");
        curl_setopt($curl, CURLOPT_TIMEOUT, 0);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_VERBOSE, true);
        if (!empty($parameters)) {
            if (is_array($parameters)) {
                $parameters = http_build_query($parameters);
            }
            curl_setopt($curl, CURLOPT_POSTFIELDS, $parameters);
        }
        if (!empty($header) && is_array($header)) {
            curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        }

        $response = curl_exec($curl);
        $err = curl_error($curl);
        $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

             $executionTime = microtime(true) - $startTime; 
        if ($log != "no") {
               ApiLog::create($log);
        }
        return ["response" => $response, "error" => $err, 'code' => $code];
    } 
    

    public static function generateRequestId()
    {
        $randomPart = substr(str_shuffle(str_repeat('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', 10)), 0, 27);

        $now = now();

        $yearLastDigit = substr($now->year, -1);
        $dayOfYear = str_pad($now->dayOfYear, 3, '0', STR_PAD_LEFT);
        $hourMinute = $now->format('Hi');
        return $randomPart . $yearLastDigit . $dayOfYear . $hourMinute;
    }

    public static function generateTransactionId()
    {
        $prefix = 'CC';
        $randomNumber = str_pad(rand(1, 99), 2, '0', STR_PAD_LEFT);
        $randomLetters = strtoupper(bin2hex(random_bytes(2))); // Generates 3 characters in uppercase
        $randomDigits = str_pad(rand(10000, 99999), 5, '0', STR_PAD_LEFT);
        $transactionId = $prefix . $randomNumber . $randomLetters . $randomDigits;

        return $transactionId;
    }
    public static function generateComplaintId()
    {
        $prefix = 'CC';
        $randomNumber = str_pad(rand(1, 99), 2, '0', STR_PAD_LEFT);
        $randomLetters = strtoupper(bin2hex(random_bytes(3)));
        $randomDigits = str_pad(rand(100000, 999999), 6, '0', STR_PAD_LEFT);
        $complaintId = $prefix . $randomNumber . $randomLetters . $randomDigits;

        return $complaintId;
    }
}
 