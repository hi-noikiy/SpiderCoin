<?php

namespace App\Console\Commands;

use App\Jobs\BCoin\OKCoin;
use App\Jobs\BCoin\OKCoinRpc\OKCoin_ApiKeyAuthentication;
use App\Jobs\BCoin\OKCoinRpc\OKCoin_Exception;

use App\Models\AipModel;
use App\Models\UserAipModel;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Exception;

class DingTouSellCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dingtou:sell';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'ding tou sell';

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
        echo Carbon::now() . "==========DingtouCommand inspection".PHP_EOL;
        // 查询定投订单
        $aipData = AipModel::where('status',1)
            ->orWhere('status', -3)
            ->get();
        foreach($aipData as $aip){
            $this->inspection($aip);
        }
        echo Carbon::now() ." : DingtouCommand inspection END at:".time().PHP_EOL;
    }
    // 检测
    private function inspection($aip){
        try{
            // 开始检测
            $total_btc = $aip->total_btc;
            $total_cny = $aip->used_cny_amount;
            if(empty($total_btc) ) return;
            // 获取现在的价格
            $client = new OKCoin(new OKCoin_ApiKeyAuthentication($aip->key, $aip->secret));
            $result = $client -> depthApi( $aip->currency , 10);
            $count_btc = 0;
            $sell_price = 0;
            foreach($result['bids'] as $bid){
                //see how deep price will go down, if I sell all btc in this AIP
                $count_btc = $count_btc + $bid[1];
                $sell_price = $bid[0];
                if($count_btc > $total_btc){
                    break;
                }
            }
            $aip->profit = round($total_btc * $sell_price - $total_cny,2);

            echo "=+=Total BTC:". $aip->total_btc . PHP_EOL;
            echo "=+=Total CNY:".$aip->used_cny_amount . PHP_EOL;
            echo "=+= current value:". $total_btc * $sell_price.PHP_EOL;
            if(!$aip->update()){
                echo "=+=aip update failed:".var_export($aip,true);
            }
            // 利润率
            $profit_percentage = $aip->profit / $total_cny *100;
            echo "===profit_percentage:$profit_percentage".PHP_EOL;
            // 利润率未到设定好的止盈率,返回
            if( $profit_percentage < $aip->stop_profit_percentage){
                return ;
            }
            //reach the stop profit condition, sell all btc and close aip
            //start a new aip base on the old one
            if(intval($aip->drawdown) <= 0){
                $this->sell($aip,$aip->total_btc,$aip->used_cny_amount);
                echo "=== selling aip:".$aip->id. "^^ reach profit, sell it............".PHP_EOL;
            }else{
                //波峰回撤开始
                echo "==== drawdown check!";
                if($aip->sellout == 0){
                    //not yet reach the top, check current price
                    if(($total_btc*($sell_price - $aip->drawdown) - $total_cny / $total_cny *100) > $aip->stop_profit_percentage){
                        // current price higher than the trigger price, record it, and wait for drawdown.
                        echo " === aip:".$aip->id." change sellout from ".$aip->sellout." to ".$sell_price-$aip->drawdown;
                        $aip->sellout = $sell_price-$aip->drawdown;
                        $aip->update();
                    }

                }else{
                    //sellout price already set, check whether price reach new highest, if so, change the sell out to the new price
                    if($sell_price - $aip->drawdown > $aip->sellout){
                        echo " === aip:" . $aip->id . " change sellout from " . $aip->sellout . " to " . $sell_price-$aip->drawdown;
                        $aip->sellout = $sell_price-$aip->drawdown;
                        $aip->update();

                    }else if( $sell_price < ($aip->sellout+5)){
                        //if current price lower than the sellout price + 5, then reach the price draw down, please sell all.
                        echo " === aip:".$aip->id." sellout at price: ".$sell_price;
                        $this->sell($aip,$aip->total_btc,$aip->used_cny_amount);
                    }
                }
            }
        }catch(OKCoin_Exception $e){
            echo "=== DingtouCommand inspection END with okcoin exception:".($e->getMessage()).PHP_EOL;
            \Mail::send('frontend.mail.sellout', $aip, function($message) {
                $message->to('xuyang@sfards.com')->subject('卖出订单失败!');
            });
        }catch(\Exception $ex){
            echo "=== DingtouCommand inspection END with exception:".($ex->getMessage()).PHP_EOL;
            \Mail::send('frontend.mail.sellout', $aip, function($message) {
                $message->to('xuyang@sfards.com')->subject('卖出订单失败!');
            });
        }
        echo Carbon::now() ." === DingtouCommand inspection END at:".time().PHP_EOL;
    }
    // 出售
    private function sell($aip, $amount, $total_cny){
        echo "=====sell: id:".$aip->id;
        //sell btc
        $client = new OKCoin(new OKCoin_ApiKeyAuthentication($aip->key, $aip->secret));
        $params = array('api_key' => $aip->key, 'symbol' => $aip->currency, 'type' => 'sell_market', 'amount' => $amount);
        $tradeResult = $client -> tradeApi($params);
        echo "=====sell: trade result:".var_export($tradeResult,true);
        if(!$tradeResult['result']){
            throw new Exception('查询交易数据' .var_export($tradeResult,true));
        }
        //persist orders
        echo "---休息一下，等待交易成功".PHP_EOL;
        sleep(1);
        $params = array('api_key' => $aip->key, 'symbol' => $aip->currency, 'order_id' => $tradeResult['order_id']);
        $orderInfoResult = $client -> orderInfoApi($params);
        echo "=====sell: orderinfo result:".var_export($orderInfoResult,true);
        if(!$orderInfoResult['result']){
            throw new Exception('orderinfo fail' .var_export($orderInfoResult,true));
        }
        $aip->status = 2;
        $current_cny_amount = 0;
        foreach($orderInfoResult['orders'] as $o){
            $current_cny_amount = $current_cny_amount + ($o->deal_amount * $o->avg_price);
        }

        $aip->profit = $current_cny_amount - $total_cny;
        $aip->end_at = time();
        if(!$aip->update()){
            throw new Exception('=====sell: aip update failed:'.$aip->id );
        }

        //create a new aip same aip for it
        $newAip = new AipModel();
        $newAip->per_amount = $aip->per_amount;
        $newAip->start_at = time();
        $newAip->stop_profit_percentage = $aip->stop_profit_percentage;
        $newAip->create_at = time();
        $newAip->key = $aip->key;
        $newAip->secret = $aip->secret;
        $newAip->period = $aip->period;
        $newAip->status = 1;
        $newAip->fund = $aip->fund;
        $newAip->currency = $aip->currency;
        $newAip->ispublic = 0;
        $newAip->day = $aip->day;
        $newAip->hour = $aip->hour;
        $newAip->minute = $aip->minute;
        $newAip->create_by = $aip->create_by;
        $newAip->keyid = $aip->keyid;
        $newAip->ispublic = 0;
        $newAip->aip_type = $aip->aip_type;
        $newAip->save();

        // 查询用户定投数据
        $newUserAip = new UserAipModel();
        $newUserAip->uid = $aip->create_by;
        $newUserAip->aip_id = $newAip->id;
        $newUserAip->amount = round($aip->fund + $aip->profit,0);
        $newUserAip->at = time();
        if(!$newUserAip->save()){
            echo "=====: create newUserAip fail ".var_export($newUserAip,true);
        }
        \Mail::send('frontend.mail.sellout', $aip, function($message) {
            $message->to('xuyang@sfards.com')->subject('定投自动卖出!');
        });
    }
}
