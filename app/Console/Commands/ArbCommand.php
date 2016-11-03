<?php

namespace App\Console\Commands;

use App\Jobs\BCoin\OKCoin;
use App\Jobs\BCoin\OKCoinRpc\OKCoin_ApiKeyAuthentication;
use App\Jobs\BCoin\OKCoinRpc\OKCoin_Exception;
use App\Models\ArbitragePriceModel;
use App\Models\ArbModel;
use App\Models\ArbOrdersModel;
use Illuminate\Console\Command;
use Log;

class ArbCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'arb:start';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'start ban ban ban';

    /**
     * Create a new command instance.
     *
     */
    public function __construct()
    {
        parent::__construct();

    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        echo "==========".__METHOD__.PHP_EOL;
        Log::error("==========".__METHOD__.PHP_EOL);

        $starttime = time();
//		sleep(5);
        try{
            // 检测现在价格

            $price = ArbitragePriceModel::orderBy('id','desc')->first();
            echo "======".$price->ratio.PHP_EOL;
            Log::error("==========".__METHOD__."ratio:".$price->ratio);
            $arbs = ArbModel::all();
            if(!$arbs)
                return;

            foreach ($arbs as $arb){
                if($arb->cn2com > floatval($price->ratio)){
                    $this->cnbuy2comsell($arb,$price);
                }elseif($arb->com2cn < floatval($price->ratio)){
                    $this->cnsell2combuy($arb,$price);
                }
            }

        }catch(OKCoin_Exception $e){
            echo "=== ".__METHOD__." END with exception:".($e->getMessage()).PHP_EOL;
            Log::error("=== ".__METHOD__." END with exception:".($e->getMessage()).PHP_EOL);
        }catch(\Exception $e){
            echo "=== DingtouCommand END with exception:".($e->getMessage()).PHP_EOL;
            Log::error("=== DingtouCommand END with exception:".($e->getMessage()).PHP_EOL);
        }
        echo "=== ".__METHOD__." END at:".time().PHP_EOL;
        Log::error("=== ".__METHOD__." END at:".time().PHP_EOL);

    }
    public function cnbuy2comsell($arb,$price){
        //查询数据库内剩余资金
        $totalcny =  ArbOrdersModel::where('status',1)->where('arbid',$arb->id)->sum('rmbbuy_amount');
        $usable_capital = $arb->cn_capital;
        if(!empty($totalcny)) {
            //already used this money, only left captial - totalcny for use
            if($arb->cn_capital <= $totalcny) return;
            $usable_capital = $arb->cn_capital - $totalcny;
        }
        Log::error("==========".__METHOD__."==>usable_capital:".$usable_capital);
        //check do you have btc to sell
        $comclient = new OKCoin(new OKCoin_ApiKeyAuthentication($arb->com_market->key, $arb->com_market->secret),0);
        $params = array('api_key' => $arb->com_market->key);
        $result = $comclient->userinfoApi($params);

        if(!$result->result || floatval($result->info->funds->free->btc) < $arb->com_btc ){
            // 国际站BTC不足
            return;
        }


        //检查国内站的资金
        $cnclient = new OKCoin(new OKCoin_ApiKeyAuthentication($arb->cn_market->key, $arb->cn_market->secret));
        $ticker = $cnclient -> tickerApi('btc_cny');
        $cnsell = floatval($ticker->ticker->sell);

        if($usable_capital < $cnsell * $arb->com_btc) return;

        $params = array('api_key' => $arb->cn_market->key);
        $result = $cnclient->userinfoApi($params);
        if(!$result->result || floatval($result->info->funds->free->cny) < $arb->com_btc *  $cnsell){
            // 国际站BTC不足
            return;
        }
        echo "===".PHP_EOL;
        Log::error("==========".__METHOD__."==>com start to sell:");

        //国际站开始卖出
        $params = array('symbol' => 'btc_usd', 'size' => 1);
        $depth = $comclient -> depthApi($params);
        var_dump($depth);
        $orderprice = $depth->bids[0][0] + ($depth->asks[0][0] - $depth->bids[0][0]) / 5;
        echo $orderprice;

        $comorder = $this->buyAndWait($comclient, $arb->com_market->key,'btc_usd', 'sell',$orderprice,$arb->com_btc);

        if(!$comorder){
            return;
        }

        var_dump($comorder);
        Log::error("==========".__METHOD__."==>cn start to buy:");
        //buy in cn
        $cnorder = NULL;
        while(true){
            $cnorder = $this->buyAndWait($cnclient, $arb->cn_market->key,'btc_cny', 'buy',$cnsell+10 ,$arb->com_btc);
            if(!$cnorder){
                continue;
            }
            break;
        }
        var_dump($cnorder);
        $params = array('api_key' => $arb->cn_market->key,'symbol'=>'btc_cny','chargefee' => 0.0001,'trade_pwd'=>'kj*6go;','withdraw_address' =>$arb->cn2com_address,'withdraw_amount'=>$cnorder->deal_amount);
        $result = $cnclient->withdrawApi($params);
        var_dump($result);
        if(!$result->result){
            $txid = $result->error_code;
        }else{
            $txid = $result->withdraw_id;
        }
        Log::error("==========".__METHOD__."==>done:");

        $arborder = new ArbOrdersModel();
        $arborder->arbid = $arb->id;
        $arborder->cnbuy = $cnorder->avg_price;
        $arborder->cnbtc_buy = $cnorder->deal_amount;
        $arborder->rmbbuy_amount = $cnorder->avg_price * $cnorder->deal_amount;
        $arborder->combtc_sell = $comorder->deal_amount;
        $arborder->com_sell = $comorder->avg_price;
        $arborder->usdsell_amount = $comorder->avg_price*$comorder->deal_amount;
        $arborder->sell_fee = 0;
        $arborder->exchange_rate_sell = $price->buy2_exchange_rate;
        $arborder->buysell_rate = ($arborder->rmbbuy_amount-$arborder->usdsell_amount*$arborder->exchange_rate_sell)/$arborder->rmbbuy_amount;
        $arborder->cn2com_txid = $txid;
        $arborder->cn2com_at = time();
        $arborder->status = 1;

        if(!$arborder->save()){
            echo "=== arborder save Failed!:".var_export($arborder->getErrors(),true) . PHP_EOL;
        }

    }

    public function cnsell2combuy($arb,$price){
        //查询是否有国际未返回国内的资金
        $order = ArbOrdersModel::where('arbid',$arb->id)
            ->where('status',1)
            ->orderBy('cn2com_at')
            ->first();
        if(empty( $order)){
            //没有状态为1的order
            return -1;
        }
        //检查国内站的BTC
        $cnclient = new OKCoin(new OKCoin_ApiKeyAuthentication($arb->cn_market->key, $arb->cn_market->secret));
        $params = array('api_key' => $arb->cn_market->key);
        $result = $cnclient->userinfoApi($params);
        if(!$result->result || floatval($result->info->funds->free->btc) < $arb->cn_btc)
            return -2;

        //检查国际站的USD
        $comclient = new OKCoin(new OKCoin_ApiKeyAuthentication($arb->com_market->key, $arb->com_market->secret),0);

        $params = array('symbol' => 'btc_usd');
        $ticker = $comclient -> tickerApi($params);

        $comsell = floatval($ticker->ticker->sell);

        $params = array('api_key' => $arb->com_market->key);
        $result = $comclient->userinfoApi($params);
        var_dump($result);
        echo "com sell:".$comsell.PHP_EOL;
        if(!$result->result || floatval($result->info->funds->free->usd) < $comsell * $arb->com_btc)
            return -3;
        //国际买入
        $params = array('symbol' => 'btc_usd', 'size' => 1);
        $depth = $comclient -> depthApi($params);
        var_dump($depth);
        $orderprice = $depth->asks[0][0] - ($depth->asks[0][0] - $depth->bids[0][0]) / 5;
        echo $orderprice;

        $comorder = $this->buyAndWait($comclient, $arb->com_market->key,'btc_usd', 'buy',$orderprice,$arb->com_btc);

        if(!$comorder){
            return -4;
        }

        //国内卖出
        $cnorder = NULL;
        while(true){
            $cnorder = $this->buyAndWait($cnclient, $arb->cn_market->key,'btc_cny', 'sell', ($price->buy2_exchange_rate * $orderprice - 50), $arb->com_btc);
            if(!$cnorder){
                continue;
            }
            break;
        }
        var_dump($cnorder);

        //国际转国内
        $params = array('api_key' => $arb->com_market->key,'symbol'=>'btc_usd','chargefee' => 0.0001,'trade_pwd'=>'df#5hwa','withdraw_address' =>$arb->com2cn_address,'withdraw_amount'=>$comorder->deal_amount);
        $result = $comclient->withdrawApi($params);
        var_dump($result);
        if(!$result->result){
            $txid = $result->error_code;
        }else{
            $txid = $result->withdraw_id;
        }

        $order->cnsell = $cnorder->avg_price;
        $order->cnbtc_sell = $cnorder->deal_amount;
        $order->rmbsell_amount = $cnorder->avg_price *  $cnorder->deal_amount;
        $order->com_buy = $comorder->avg_price;
        $order->combtc_buy = $comorder->deal_amount;
        $order->usdbuy_amount = $comorder->avg_price * $comorder->deal_amount;
        $order->buy_fee = 0;
        $order->exchange_rate_buy = $price->buy2_exchange_rate;
        $order->sellbuy_rate = ($order->rmbsell_amount-$order->usdbuy_amount*$order->exchange_rate_sell)/$order->rmbsell_amount;
        $order->profit = $order->rmbsell_amount - $order->rmbbuy_amount;
        $order->status = 2;
        $order->com2cn_at = time();
        $order->com2cn_txid = $txid;
        $order->save();
        //完成记录数据库
    }
    public function buyAndWait($okcoin, $key, $symbol, $type, $price, $amount){
        if(strcmp($type,'buy_market') == 0){
            $params = array('api_key' => $key, 'symbol' => $symbol, 'type' => $type, 'price' => $price);
        }elseif (strcmp($type,'sell_market') == 0){
            $params = array('api_key' => $key, 'symbol' => $symbol, 'type' => $type, 'amount' => $amount);
        }else{
            $params = array('api_key' => $key, 'symbol' => $symbol, 'type' => $type, 'price' => $price, 'amount' => $amount);
        }
        $result = $okcoin -> tradeApi($params);
        if($result->result){
            $orderid = $result->order_id;
            echo "orderid:$orderid".PHP_EOL;
            $count = 0;
            $status = 0;
            while(true){
                if(($count > 30 && $status == 0 ) || ($count > 60 && $status == 1 ) ){
                    $params = array('api_key' => $key, 'symbol' => $symbol, 'order_id' => $orderid);
                    $result = $okcoin -> cancelOrderApi($params);
                    if($result->result){
                        return false;
                    }
                }
                sleep(1);
                $params = array('api_key' => $key, 'symbol' => $symbol, 'order_id' => $orderid);
                $result = $okcoin -> orderInfoApi($params);
                var_dump($result);
                if(!$result->result){
                    return false;
                }else{
                    $order = NULL;
                    foreach ( $result->orders as $o){
                        if($o->status == -1) {
                            return false;
                        }else{
                            $status = $o->status;
                            $order = $o;
                        }
                    }
                    if($status == 1 || $status == 0){
                        //wait for one more second
                        echo "=== order status is $status, wait for one more second".PHP_EOL;
                        $count++;
                        continue;
                    }
                    //status is 2, order finished
                    echo "=== order finished!".PHP_EOL;
                    return $order;
                }
            }
        }else{
            return false;
        }
    }
}
