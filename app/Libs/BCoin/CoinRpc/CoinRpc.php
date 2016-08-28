<?php
namespace App\Jobs\BCoin\CoinRpc;

class CoinRpc {
	private $_request;
	private $_authentication;

	public function __construct( $request , $authentication) {
		$this -> _request = $request;
		$this -> _authentication = $authentication;
	}
	public function request($method, $url, $params , $headers = '') {
		$ch = curl_init();
		$method = strtolower($method);
		if ($method == 'get') {
			curl_setopt($ch, CURLOPT_HTTPGET, 1);
			if ($params != null) {
				$queryString = http_build_query($params);
				$url .= "?" . $queryString;
			}
		} else if ($method == 'post') {
			curl_setopt($ch, CURLOPT_POST, 1);
			// Create query string
			curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));

		}
		// CURL options
		curl_setopt( $ch, CURLOPT_URL,  $url);
		curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, FALSE);

		// Do request
		$response = $this -> _request -> doCurlRequest($ch);
		// Decode response
		$json = json_decode( $response['body'] , true );

		return $json;
	}

}
