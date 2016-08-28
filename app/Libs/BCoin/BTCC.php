<?php

namespace App\Jobs\BCoin;

class BTCC{

	const WEB_BASE = 'https://pro-data.btcc.com/';//BTCC 中国站
	const API_BASE = 'data/pro/';

	static private $api_key;
	static private $api_secret;
	public function __construct($api_key, $api_secret) {
		self::$api_key = $api_key;
		self::$api_secret = $api_secret;
	}
	/**
	 * 获取 BTCC 的kline
	 * @param string $symbol
	 * @param int $limit
	 * @param int $since
	 * @param string $sincetype
	 * 默认返回前一百条
	 *
	 * @return json
	 */
	static public function klineDataApi( $symbol =  'XBTCNY', $limit = 100 , $since = 0, $sincetype = 'id' )
	{
		$url = self::WEB_BASE . self::API_BASE.'historydata';
		$params = [];
		!empty($since) && $params['since'] =  $since;
		!empty($limit) && $params['limit'] =  $limit;
		!empty($symbol) && $params['symbol'] =  $symbol;
		!empty($sincetype) && $params['sincetype'] =  $sincetype;
		if(!empty($params)){
			$url = $url.'?'.http_build_query($params);
		}
		$res = httpRequest( $url );
		return json_decode($res, true);
	}

	/**
	 *
	 * 获取 BTCC 历史交易数据
	 * @param string $symbol
	 * @param int $limit
	 * @return array
	 *
	 */
	static public function tradesApi( $symbol =  'XBTCNY', $limit = 100 )
	{

		$url = self::WEB_BASE.self::API_BASE.'ticker';
		$params = [];
		!empty($since) && $params['since'] =  $since;
		!empty($limit) && $params['limit'] =  $limit;
		!empty($symbol) && $params['symbol'] =  $symbol;
		!empty($sincetype) && $params['sincetype'] =  $sincetype;
		if(!empty($params)){
			$url = $url.'?'.http_build_query($params);
		}
		$res = httpRequest( $url );
		return json_decode($res, true);
	}
	/**
	 * 获取 BTCC 的实时行情
	 * @param string $symbol btc ltc
	 * @return array
	 */
	static public function tickerApi( $symbol )
	{
		$params['symbol'] = $symbol == 'btc' ? 'XBTCNY' : 'XBTUSD' ;
		$url = self::WEB_BASE . self::API_BASE.'ticker';
		$res = httpRequest( $url , $params);
		$tickerData = json_decode($res);
		$resData = [
			'time' 	=> floor( $tickerData->ticker-> Timestamp/1000 ),
			'buy' 	=> $tickerData->ticker-> BidPrice,
			'high' 	=> $tickerData->ticker-> High,
			'last' 	=> $tickerData->ticker-> Last,
			'low' 	=> $tickerData->ticker-> Low,
			'sell' 	=> $tickerData->ticker-> AskPrice,
			'vol' 	=> $tickerData->ticker-> Volume,
			'symbol' 	=> $symbol,
		];
		return $resData;
	}
}