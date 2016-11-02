<?php

namespace App\Console\Commands;

use App\Jobs\BCoin\OKCoin;
use App\Jobs\BCoin\OKCoinRpc\OKCoin_ApiKeyAuthentication;
use App\Jobs\BCoin\OKCoinRpc\OKCoin_Exception;

use App\Models\AipModel;
use App\Models\AipOrdersModel;
use App\Models\UserAipModel;
use Illuminate\Console\Command;
use Log;
class DingTouInspectionCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dingtou:inspection';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'inspection ding tou to shell';

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
        $starttime = time();
        echo "==========DingtouCommand inspection".PHP_EOL;
        try{
            $aips = AipModel::where('status',1)->orWhere('status',-3)->get();
            foreach($aips as $aip){
                $this->inspection($aip);
            }


        }catch(OKCoin_Exception $e){
            echo "=== DingtouCommand inspection END with okcoin exception:".($e->getMessage()).PHP_EOL;
            Log::error("=== DingtouCommand inspection END with okcoin exception:".($e->getMessage()).PHP_EOL);
        }catch(\Exception $ex){
            echo "=== DingtouCommand inspection END with exception:".($ex->getMessage()).PHP_EOL;
            Log::error("=== DingtouCommand inspection END with exception:".($ex->getMessage()).PHP_EOL);
        }
        echo "=== DingtouCommand inspection END at:".time().PHP_EOL;
        Log::error("=== DingtouCommand inspection end at:".(time()-$starttime).PHP_EOL);

    }
    // 检测
    private function inspection($aip){
        $orderModel = AipOrdersModel::where('aip_id',$aip->id)->where('status',2);
        $total_btc = $orderModel->sum("deal_amount");
        $total_cny = $orderModel->sum("deal_cny_amount");
        if(empty($total_btc) ) return;
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
        echo "=+=Total BTC:".$total_btc.PHP_EOL;
        echo "=+=Total CNY:".$total_cny.PHP_EOL;
        echo "=+= current value:". $total_btc*$sell_price.PHP_EOL;
        if($aip->id == 2){
            echo "===================aip".PHP_EOL;
        }
        Log::error("=+=Total BTC:".$total_btc);
        Log::error("=+=Total CNY:".$total_cny);
        Log::error("=+= current value:". $total_btc*$sell_price);
        $aip->used_cny_amount = round($total_cny,2);
        $aip->total_btc = round($total_btc,4);
        $aip->profit = round($total_btc*$sell_price - $total_cny,2);
        if(!$aip->update()){
            Log::error("=+=aip update failed:".var_export($aip,true));
        }

        if(empty($total_cny) ) return;
        $profit_percentage = ($total_btc*$sell_price - $total_cny) / $total_cny *100;
        echo "===profit_percentage:$profit_percentage".PHP_EOL;
        Log::error("===profit_percentage:$profit_percentage");
        if( $profit_percentage > $aip->stop_profit_percentage){
            //reach the stop profit condition, sell all btc and close aip
            //start a new aip base on the old one
            if(intval($aip->drawdown) <=0){
                if($aip->id==2){
                    $this->sell($aip,7.4,$aip->used_cny_amount);
                }else{
                    $this->sell($aip,$aip->total_btc,$aip->used_cny_amount);
                }
                echo "^^ reach profit, sell it............".PHP_EOL;
                Log::error("=== selling aip:".$aip->id);
            }else{
                //波峰回撤开始
                Log::error("==== drawdown check!");
                if($aip->sellout == 0){
                    //not yet reach the top, check current price
                    if(($total_btc*($sell_price - $aip->drawdown) - $total_cny / $total_cny *100) > $aip->stop_profit_percentage){
                        // current price higher than the trigger price, record it, and wait for drawdown.
                        Log::error(" === aip:".$aip->id." change sellout from ".$aip->sellout." to ".$sell_price-$aip->drawdown);
                        $aip->sellout = $sell_price-$aip->drawdown;
                        $aip->update();
                    }

                }else{
                    //sellout price already set, check whether price reach new highest, if so, change the sell out to the new price
                    if($sell_price-$aip->drawdown > $aip->sellout){
                        Log::error(" === aip:".$aip->id." change sellout from ".$aip->sellout." to ".$sell_price-$aip->drawdown);
                        $aip->sellout = $sell_price-$aip->drawdown;
                        $aip->update();

                    }else if( $sell_price < ($aip->sellout+5)){
                        //if current price lower than the sellout price + 5, then reach the price draw down, please sell all.
                        Log::error(" === aip:".$aip->id." sellout at price: ".$sell_price);
                        $this->sell($aip,$aip->total_btc,$aip->used_cny_amount);
                    }
                }
            }
        }else{
            echo "--! not yet profit, sleep again............".PHP_EOL;
        }
    }
    // 出售
    private function sell($aip, $amount, $total_cny){
        Log::error("=====sell: id:".$aip->id);
        //sell btc
        $client = new OKCoin(new OKCoin_ApiKeyAuthentication($aip->key, $aip->secret));
        $params = array('api_key' => $aip->key, 'symbol' => $aip->currency, 'type' => 'sell_market', 'amount' => $amount);
        $result = $client -> tradeApi($params);
        Log::error("=====sell: trade result:".var_export($result,true));
        var_dump($result);
        if(!$result->result){
            //TODO:邮件通知没有卖成
            Log::error("=====sell: trade failed:");
            return;
        }
        //persist orders
        echo "---休息一下，等待交易成功".PHP_EOL;
        sleep(1);
        echo "---".PHP_EOL;
        $params = array('api_key' => $aip->key, 'symbol' => $aip->currency, 'order_id' => $result->order_id);
        $result = $client -> orderInfoApi($params);
        Log::error("=====sell: orderinfo result:".var_export($result,true));
        var_dump($result);
        if(!$result->result){
            //TODO:邮件通知没有卖成
            return;
        }
        $aip->status = 2;
        $current_cny_amount = 0;
        foreach($result->orders as $o){
            $current_cny_amount = $current_cny_amount + ($o->deal_amount*$o->avg_price);
        }

        $aip->profit = $current_cny_amount - $total_cny;
        $aip->end_at = time();
        if(!$aip->update()){
            //TODO:邮件通知 AIP没有更新成功
            Log::error("=====sell: aip update failed:");
        }

        //create a new aip same aip for it
        $newaip = new AipModel();
        $newaip->per_amount = $aip->per_amount;
        $newaip->start_at = time();
        $newaip->stop_profit_percentage = $aip->stop_profit_percentage;
        $newaip->create_at = time();
        $newaip->key = $aip->key;
        $newaip->secret = $aip->secret;
        $newaip->period = $aip->period;
        $newaip->status = 1;
        $newaip->fund = $aip->fund;
        $newaip->currency = $aip->currency;
        $newaip->ispublic = 0;
        $newaip->day = $aip->day;
        $newaip->hour = $aip->hour;
        $newaip->minute = $aip->minute;
        $newaip->create_by = $aip->create_by;
        $newaip->keyid = $aip->keyid;
        $newaip->ispublic = 0;
        $newaip->aip_type = $aip->aip_type;
        $newaip->save();

        // 查询用户定投数据
        $useraip = UserAipModel::where('aip_id',$aip->id)->first();
        Log::error("=====".var_export($useraip,true));
        $newuseraip = new UserAipModel();
        $newuseraip->uid = $useraip->uid;
        $newuseraip->aip_id = $newaip->id;
        $newuseraip->amount = round($useraip->amount+$aip->profit,0);
        $newuseraip->at = time();
        if(!$newuseraip->save()){
            Log::error("=====:".var_export($newuseraip,true));
        }
        try {
            // TODO 邮件通知到客户
            \Mail::send('frontend.mail.sellout', $aip, function($message)
            {
                $message->to('xuyang@sfards.com')->subject('定投自动卖出!');
            });

        } catch ( \Exception $ex ) {
            Log::error( "===" . __METHOD__ . ":" . var_export ( $ex, true ) );
        }
    }
}
