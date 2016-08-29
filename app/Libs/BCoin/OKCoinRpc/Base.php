<?php
namespace App\Jobs\BCoin\OKCoinRpc;

if (!function_exists('curl_init')) {
	throw new \Exception('The OKCoin client library requires the CURL PHP extension.');
}
class OKCoinBase {
	const API_BASE = '/api/v1/';
	
//	const WEB_BASE = 'https://www.okcoin.com/';//OKCoin国际站
	const WEB_BASE = 'https://www.okcoin.cn/';//OKCoin中国站
	private $_rpc;
	private $_authentication;
	// This constructor is deprecated.
	public function __construct($authentication, $apiKey = null, $apiKeySecret = null) {
		// First off, check for a legit authentication class type
		if ($authentication instanceof OKCoin_Authentication) {
			$this -> _authentication = $authentication;
		} else {
			$this -> _authentication = new OKCoin_ApiKeyAuthentication($apiKey, $apiKeySecret);
		}

		$this -> _rpc = new OKCoin_Rpc(new OKCoin_Requestor(), $this -> _authentication);
	}

	// Used for unit testing only
	public function setRequestor($requestor) {
		$this -> _rpc = new OKCoin_Rpc($requestor, $this -> _authentication);
		return $this;
	}

	public function get($path, $params = array()) {
		return $this -> _rpc -> request("GET", $path, $params);
	}

	public function post($path, $params = array()) {
		return $this -> _rpc -> request("POST", $path, $params);
	}

	public function delete($path, $params = array()) {
		return $this -> _rpc -> request("DELETE", $path, $params);
	}

	public function put($path, $params = array()) {
		return $this -> _rpc -> request("PUT", $path, $params);
	}

}
