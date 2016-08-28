<?php

namespace App\Jobs\BCoin;

class BtcTrade{

	const WEB_BASE = 'http://api.btctrade.com/';//BTCC 中国站
	const API_BASE = 'api/';

	static private $api_key;
	static private $api_secret;
	public function __construct($api_key, $api_secret) {
		self::$api_key = $api_key;
		self::$api_secret = $api_secret;
	}
	/**
	 * 获取 BtcTrade 的成交记录
	 * @param string $symbol	btc,eth,ltc,doge,ybc
	 * @param int $since
	 * @return array
	 */
	static public function klineDataApi( $symbol =  'btc' ,$since = 0 )
	{
		$url = self::WEB_BASE . self::API_BASE.'trades';
		$params = [];
		!empty($since) && $params['since'] =  $since;
		!empty($symbol) && $params['coin'] =  $symbol;
		if(!empty($params)){
			$url = $url.'?'.http_build_query($params);
		}
		$res = httpRequest( $url );
		return json_decode($res, true);
	}

	/**
	 *
	 * 获取 BtcTrade 实时交易数据
	 * @param string $symbol	btc,eth,ltc,doge,ybc
	 * @return array
	 *
	 */
	static public function depthApi( $symbol =  'btc' )
	{
		$url = self::WEB_BASE.self::API_BASE.'ticker';
		!empty($symbol) && $url = $url.'?'.http_build_query(['coin'=>$symbol]);
		$res = httpRequest( $url );
		return json_decode($res, true);
	}
	/**
	 * 获取 BtcTrade 行情（盘口数据）
	 * @param string $symbol	btc,eth,ltc,doge,ybc
	 * @return array
	 *
	 */
	static public function tickerApi( $symbol =  'btc' )
	{
		$params['coin']  = $symbol =='btc' ? 'btc' : 'ltc';
		$url = self::WEB_BASE . self::API_BASE . 'ticker';
		$res = httpRequest( $url , $params );
		return json_decode($res, true);
	}
}