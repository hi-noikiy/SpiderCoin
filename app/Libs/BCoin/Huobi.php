<?php
namespace App\Jobs\BCoin;

class HuoBi {
	const WEB_BASE = "http://api.huobi.com/";
	const API_BASE = 'staticmarket/';
	
	/**
	 * 获取火币的kline
	 * @param string $symbol  btc ltc
	 * @param string $period	001  1分钟线 005 5分钟
	 * @return array
	 */
	static public function klineDataApi( $symbol = 'btc' , $period = '001' )
	{
		$res = httpRequest( self::WEB_BASE . self::API_BASE . $symbol.'_kline_'.$period.'_json.js', '');
		return $res ;
	}
	/**
	 * 获取火币的实时行情
	 * @param string $symbol btc ltc
	 * @return array
	 */
	static public function tickerApi( $symbol )
	{
		$symbol = $symbol == 'btc' ? 'btc' : 'ltc' ;
		$tickerData = httpRequest( self::WEB_BASE .self::API_BASE.'ticker_'.$symbol.'_json');
		$resData = [
			'time' 	=> $tickerData['time'] ,
			'buy' 	=> $tickerData['ticker']['buy'],
			'high' 	=> $tickerData['ticker']['high'],
			'last' 	=> $tickerData['ticker']['last'],
			'low' 	=> $tickerData['ticker']['low'],
			'sell' 	=> $tickerData['ticker']['sell'],
			'vol' 	=> $tickerData['ticker']['vol'],
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
	static public function depthApi( $symbol = 'btc' ,$size = 0)
	{
		$symbol = $symbol == 'btc' ? 'btc' : 'ltc' ;
		$size   = empty($size) ? 'json' : $size ;
		$tickerData = httpRequest( self::WEB_BASE .self::API_BASE.'depth_'.$symbol.'_'.$size);
		return $tickerData;
	}
	/**
	 * 获取火币买卖盘实时成交数据
	 * @param string $symbol *       btc ltc
	 *  	amount: 63165 //成交量
	 *      level: 86.999 //涨幅
	 *      buys: Array[10] //买10
	 *      p_high: 4410 //最高
	 *      p_last: 4275 &nbsp;//收盘价
	 *      p_low: 4250 //最低
	 *      p_new: 4362 //最新
	 *      p_open: 4275 //开盘
	 *      sells: Array[10] //卖10
	 *      top_buy: Array[5] //买5
	 *      top_sell: Object //卖5
	 *      total: 273542407.24361 //总量（人民币）
	 *      trades: Array[15] //实时成交
	 *      symbol:"btccny" //类型
	 * @return array
	 */
	static public function tradesApi( $symbol = 'btc' )
	{
		$symbol = $symbol == 'btc' ? 'btc' : 'ltc' ;
		return httpRequest( self::WEB_BASE .self::API_BASE.'detail_'.$symbol.'_json')['trades'];
	}
}