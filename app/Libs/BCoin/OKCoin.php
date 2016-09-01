<?php
namespace App\Jobs\BCoin;

use App\Jobs\BCoin\OKCoinRpc\OKCoinBase;
use Carbon\Carbon;

class OKCoin extends OKCoinBase {

	//构造函数
	function __construct($authentication) {
		parent::__construct($authentication);
	}

	/**
	 * 获取OKCoin行情（盘口数据）
	 * @param string $symbol b种类型
	 *
	 * date: 返回数据时服务器时间
	 * buy: 买一价
	 * high: 最高价
	 * last: 最新成交价
	 * low: 最低价
	 * sell: 卖一价
	 * vol: 成交量(最近的24小时)
	 * @return array
	 */
	public function tickerApi( $symbol ) {
		// 格式化参数
		$params['symbol']  = $symbol =='btc' ? 'btc_cny' : 'ltc_cny';
		// 格式化返回值
		$tickerData = $this -> get("/api/v1/ticker.do", $params);
		$resData = [
			'time' 	=> $tickerData['date'] ,
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
	
	//获取OKCoin市场深度
	public function depthApi($symbol = 'btc' , $size = 0 , $merge = 0 ) {
		$params['symbol']  = $symbol =='btc' ? 'btc_cny' : 'ltc_cny';
		!empty($size) && $params['size'] = $size;
		!empty($merge) && $params['merge'] = $merge;
		return $this -> get("/api/v1/depth.do", $params);
	}

	/**
	 * 获取OKCoin历史交易信息
	 * @param string $symbol	btc_cny:比特币 ltc_cny :莱特币
	 * @param int $since		从某一tid开始访问最近600条数据(非必填项)
	 *
	 * 返回值解释
	 * date:交易时间
	 * date_ms:交易时间(ms)
	 * price: 交易价格
	 * amount: 交易数量
	 * tid: 交易生成ID
	 * type: buy/sell
	 *
	 * @return mixed
	 */
	public function tradesApi( $symbol = 'btc' , $since = 0 ) {
		$params['symbol']  = $symbol =='btc' ? 'btc_cny' : 'ltc_cny';
		!empty($since) && $params['since'] = $symbol ;
		return $this -> get("/api/v1/trades.do", $params);
	}
	/**
	 * 获取比特币或莱特币的K线数据
	 * @param  string $symbol B的类型 btc_cny：比特币， ltc_cny：莱特币
	 * @param  string $type k线类型 1min : 1分钟 3min : 3分钟 5min : 5分钟 15min : 15分钟 30min : 30分钟 1day : 1日 3day : 3日 1week : 1周 1hour : 1小时 2hour : 2小时 4hour : 4小时 6hour : 6小时 12hour : 12小时
	 * @param  integer $size 指定获取数据的条数
	 * @param  int $since 时间戳（eg：1417536000000）。 返回该时间戳以后的数据
	 * 1417536000000
	 * 返回值说明
	 * [
	 * 		1417536000000,	时间戳
	 * 		2370.16,	开
	 * 		2380,		高
	 * 		2352,		低
	 * 		2367.37,	收
	 * 		17259.83	交易量
	 * ]
	 * @return mixed
	 */
	public function klineDataApi($symbol ='btc', $type = '1min', $size = 1440 , $since = 0) {
		$params['symbol']  = $symbol =='btc' ? 'btc_cny' : 'ltc_cny';
		!empty($type) && $params['type'] = $type;
		!empty($size) && $params['size'] = $size;
		$params['since'] = empty($since) ? strtotime( Carbon::yesterday()->startOfDay() )* 1000 : $since;
		return $this -> get("/api/v1/kline.do", $params);
	}
	
	//获取用户信息
	public function userinfoApi($params = null) {
		return $this -> post("/api/v1/userinfo.do", $params);
	}

	//下单交易
	public function tradeApi($params = null) {
		return $this -> post("/api/v1/trade.do", $params);
	}

	//批量下单
	public function batchTradeApi($params = null) {
		return $this -> post("/api/v1/batch_trade.do", $params);
	}

	//撤销订单
	public function cancelOrderApi($params = null) {
		return $this -> post("/api/v1/cancel_order.do", $params);
	}

	//获取用户的订单信息
	public function orderInfoApi($params = null) {
		return $this -> post("/api/v1/order_info.do", $params);
	}

	//批量获取用户订单
	public function ordersInfoApi($params = null) {
		return $this -> post("/api/v1/orders_info.do", $params);
	}

	//获取历史订单信息，只返回最近七天的信息
	public function orderHistoryApi($params = null) {
		return $this -> post("/api/v1/order_history.do", $params);
	}

	//提币BTC/LTC
	public function withdrawApi($params = null) {
		return $this -> post("/api/v1/withdraw.do", $params);
	}
	
	//取消提币BTC/LTC
	public function cancelWithdrawApi($params = null) {
		return $this -> post("/api/v1/cancel_withdraw.do", $params);
	}

	//获取OKCoin期货行情（期货盘口）
	public function tickerFutureApi($params = null) {

		return $this -> get("/api/v1/future_ticker.do", $params);
	}

	//获取OKCoin期货深度信息
	public function depthFutureApi($params = null) {
		return $this -> get("/api/v1/future_depth.do", $params);
	}

	//获取OKCoin期货交易记录信息
	public function tradesFutureApi($params = null) {
		return $this -> get("/api/v1/future_trades.do", $params);
	}

	//获取美元人民币汇率
	public function getUSD2CNYRateFutureApi($params = null) {
		return $this -> get("/api/v1/exchange_rate.do", $params);
	}

	//获取交割预估价
	public function getEstimatedPriceFutureApi($params = null) {
	    return $this -> get("/api/v1/future_estimated_price.do", $params);
	}

	//获取OKCoin期货交易历史
	public function futureTradesHistoryFutureApi($params = null) {
		return $this -> get("/api/v1/future_trades_history.do", $params);
	}

	//获取期货合约的K线数据
	public function getFutureIndexFutureApi($params = null) {
		return $this -> get("/api/v1/future_index.do", $params);
	}
	
	//获取OKCoin期货账户信息 （全仓）
	public function userinfoFutureApi($params = null) {
		return $this -> post("/api/v1/future_userinfo.do", $params);
	}

	//获取用户持仓获取OKCoin期货账户信息 （全仓）
	public function positionFutureApi($params = null) {
		return $this -> post("/api/v1/future_position.do", $params);
	}

	//期货下单
	public function tradeFutureApi($params = null) {
		return $this -> post("/api/v1/future_trade.do", $params);
	}

	//期货批量下单
	public function batchTradeFutureApi($params = null) {
		return $this -> post("/api/v1/future_batch_trade.do", $params);
	}

	//获取期货订单信息
	public function getOrderFutureApi($params = null) {
		return $this -> post("/api/v1/future_order_info.do", $params);
	}

	//取消期货订单
	public function cancelFutureApi($params = null) {
		return $this -> post("/api/v1/future_cancel.do", $params);
	}

	//获取逐仓期货账户信息
	public function fixUserinfoFutureApi($params = null) {
		return $this -> post("/api/v1/future_userinfo_4fix.do", $params);
	}

	//逐仓用户持仓查询
	public function singleBondPositionFutureApi($params = null) {
		return $this -> post("/api/v1/future_position_4fix.do", $params);
	}

}
