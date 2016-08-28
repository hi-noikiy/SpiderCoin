<?php
namespace App\Jobs\BCoin;

class HuoBi {
	static private $api_key;
	static private $api_secret;

	const WEB_BASE = "http://api.huobi.com/";
	const API_BASE = 'staticmarket/';

	public function __construct($api_key, $api_secret) {
		self::$api_key = $api_key;
		self::$api_secret = $api_secret;
	}
	
	/**
	 * 获取火币的kline
	 * @param string $symbol  btc ltc
	 * @param string $period	001  1分钟线 005 5分钟
	 * @return array
	 */
	static public function klineDataApi( $symbol = 'btc' , $period = '001' )
	{
		$res = httpRequest( self::WEB_BASE . self::API_BASE . $symbol.'_kline_'.$period.'_json.js', '');
		return json_decode($res, true);
	}
	/**
	 * 获取火币的实时行情
	 * @param string $symbol btc ltc
	 * @return array
	 */
	static public function tickerApi( $symbol )
	{
		$symbol = $symbol == 'btc' ? 'btc' : 'ltc' ;
		$res = httpRequest( self::WEB_BASE .self::API_BASE.'ticker_'.$symbol.'_json');
		$tickerData = json_decode($res);
		$resData = [
			'time' 	=> $tickerData->time ,
			'buy' 	=> $tickerData->ticker-> buy,
			'high' 	=> $tickerData->ticker-> high,
			'last' 	=> $tickerData->ticker-> last,
			'low' 	=> $tickerData->ticker-> low,
			'sell' 	=> $tickerData->ticker-> sell,
			'vol' 	=> $tickerData->ticker-> vol,
			'symbol' 	=> $symbol,
		];
		return $resData;
	}
	/**
	 * 获取火币的深度数据
	 * @param string $symbol btc ltc
	 * @param integer $size X表示返回多少条深度数据，可取值 1-150
	 * @return array
	 */
	static public function depthApi( $symbol ,$size = 0)
	{
		$symbol = $symbol == 'btc' ? 'btc' : 'ltc' ;
		$size   = !empty($size) ? 'json' : $size ;
		$res = httpRequest( self::WEB_BASE .self::API_BASE.'depth_'.$symbol.'_'.$size);
		$tickerData = json_decode($res);
		$resData = [
			'date' 	=> $tickerData->time ,
			'buy' 	=> $tickerData->ticker-> buy,
			'high' 	=> $tickerData->ticker-> high,
			'last' 	=> $tickerData->ticker-> last,
			'low' 	=> $tickerData->ticker-> low,
			'sell' 	=> $tickerData->ticker-> sell,
			'vol' 	=> $tickerData->ticker-> vol,
			'symbol' 	=> $symbol,
		];
		return $resData;
	}
}