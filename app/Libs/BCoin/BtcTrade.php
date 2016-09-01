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
	 * @param int $interval
	 * 	$url = 'http://k.btctrade.com/index/index/chart/?symbol=1&interval=60&last=0&nonce=1472559935004';
	 * @return array
	 */
	static public function klineDataApi( $symbol =  'btc' ,$interval = 60 )
	{
		$url = 'http://k.btctrade.com/index/index/chart/';
		switch ($symbol){
			case 'btc':
				$params['symbol'] =  1;
				break;
			case 'ltc':
				$params['symbol'] =  2;
				break;
			case 'eth':
				$params['symbol'] =  33;
				break;
			case 'ybc':
				$params['symbol'] =  3;
				break;
			case 'doge':
				$params['symbol'] =  4;
				break;
			default :
				$params['symbol'] =  1;
		}
		$params['interval'] =  $interval;
		$params['last'] =  1000;
		list($t1, $t2) = explode(' ', microtime());
		$params['nonce'] = (float)sprintf('%.0f',(floatval($t1)+floatval($t2))*1000);
		$res = httpRequest( $url , $params);
		return $res;
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
		$url = self::WEB_BASE.self::API_BASE.'depth';
		!empty($symbol) && $url = $url.'?'.http_build_query(['coin'=>$symbol]);
		return httpRequest( $url );
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
		return httpRequest( $url , $params );
	}

	/**
	 * 获取 BtcTrade 历史交易记录
	 * @param string $symbol	btc,eth,ltc,doge,ybc
	 * @param int $since 根据记录id返回，默认返回最新的100条交易记录
	 * @return array
	 *
	 */
	static public function tradesApi( $symbol =  'btc', $since = 0 )
	{
		$params['coin']  = $symbol ;
		!empty($since) && $params['since']  = $since ;
		$url = self::WEB_BASE . self::API_BASE . 'trades';
		return httpRequest( $url , $params );
	}
}