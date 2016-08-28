<?php
namespace App\Jobs\BCoin\CoinRpc;

class CoinRequest
{

    public function doCurlRequest($curl)
    {
        $response = curl_exec($curl);
        // Check for errors
        if($response === false) {
            $error = curl_errno($curl);
            $message = curl_error($curl);
            curl_close($curl);
            throw new CoinException("Network error " . $message . " (" . $error . ")");
        }
        // Check status code
        $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);
        if($statusCode != 200) {
            throw new CoinException("Status code " . $statusCode, $statusCode, $response);
        }

        return array( "statusCode" => $statusCode, "body" => $response );
    }

}
