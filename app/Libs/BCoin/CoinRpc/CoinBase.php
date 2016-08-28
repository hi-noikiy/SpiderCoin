<?php
namespace App\Jobs\BCoin\CoinRpc;

if (!function_exists('curl_init')) {
	throw new \Exception('The OKCoin client library requires the CURL PHP extension.');
}
class CoinBase {
	
	private $_rpc;
	private $_authentication;

	public function __construct( $apiKey = null, $apiKeySecret = null) {
		$this -> _authentication = new CoinApiKeyAuthentication( $apiKey , $apiKeySecret);
		$this -> _rpc = new CoinRpc(new CoinRequest(), $this -> _authentication);
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
