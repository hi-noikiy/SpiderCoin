<?php

namespace App\Console\Commands;

use App\Jobs\BCoin\OKCoin;
use App\Jobs\BCoin\OKCoinRpc\OKCoin_ApiKeyAuthentication;
use App\Jobs\BCoin\OKCoinRpc\OKCoin_Exception;

use App\Models\AipModel;
use App\Models\AipOrdersModel;
use App\Models\TickerModel;
use Illuminate\Console\Command;
use Log;
class DingTouCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'DingTou:start';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'start ding tou';

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

        echo "==========DingtouCommand index".PHP_EOL;
        $starttime = time();
//		sleep(5);
        try{
            $time = getdate();
            // 查询在使用状态的定投策略
            $aipData = AipModel::where('status',1)
                ->where('minute',$time['minutes'])
                ->get();
            foreach($aipData as $aip){
                Log::error("===aip:".$aip->id);
                if($aip->period == 2){
                    $hours = explode(",", $aip->hour);
                    foreach ($hours as $h){
                        if($h == $time['hours']){
                            $this->buy($aip);
                        }
                    }
                }else{
                    //by some days in the month
                    //check current day
                    $days = explode(",", $aip->day);
                    foreach ( $days as $d){
                        if($d == $time['mday'] && intval($aip->hour) == $time['hours'] ){
                            $this->buy($aip);

                        }
                    }
                }

            }
        }catch(OKCoin_Exception $e){
            echo "=== DingtouCommand END with exception:".($e->getMessage()).PHP_EOL;
            Log::error("=== DingtouCommand END with exception:".($e->getMessage()).PHP_EOL);
        }catch(\Exception $e){
            echo "=== DingtouCommand END with exception:".($e->getMessage()).PHP_EOL;
            Log::error("=== DingtouCommand END with exception:".($e->getMessage()).PHP_EOL);
        }
        echo "=== DingtouCommand END at:".time().PHP_EOL;
        Log::error("=== DingtouCommand end at:".(time()-$starttime).PHP_EOL);
    }

    // 购买操作
    private function buy($aip){
        $amount = $aip->per_amount;
        Log::error("===aip_type:".$aip->aip_type);
        $count = $aip->order_count +1;
        $aip->order_count= $count."";

        // 购买次数加一
        $aipData = AipModel::find($aip->id);
        $aipData -> order_count = $count;
        $aipData -> save();
        if($aip->aip_type == 2){
            //价值定投
            $amount = $this->estimat_value_aip($aip);
            if($amount < 0) return;
            $amount = round($amount,2);
        }
        Log::error("===== amount:".$amount);

        $client = new OKCoin(new OKCoin_ApiKeyAuthentication($aip->key, $aip->secret));
        $params = array('api_key' => $aip->key, 'symbol' => $aip->currency, 'type' => 'buy_market', 'price' => $amount);
        Log::error("=== order:".var_export($params,true));

        $result = $client -> tradeApi($params);
        Log::error("=== dingtou tradeApi :".var_export($result,true));

        if($result->result == true){
            // 创建订单
            $order = new AipOrdersModel();
            $order->orders_id = $result->order_id;
            $order->order_id = $result->order_id;
            $order->type = "buy_market";
            $order->price = $aip->per_amount;
            $order->create_at = time();
            $order->status = 0;
            $order->symbol=$aip->currency;
            $order->aip_id = $aip->id;
            if(!$order->save()){
                //TODO:邮件通知没买成
                Log::error("===dingtou order persist failed:".var_export($order->getErrors(),true));
                echo "===dingtou persist failed:".var_export($order->getErrors(),true).PHP_EOL;
                return;
            }
            sleep(1);
            $params = array('api_key' => $aip->key, 'symbol' => $aip->currency, 'order_id' => $result->order_id);
            $result = $client -> orderInfoApi($params);
            Log::error("===dingtou orderInfoApi :".var_export($result,true));
            if($result->result ==true){
                foreach($result->orders as $o){
                    if($o->order_id == $o->orders_id){
                        //it could have more than one orders to fill the orig order at different price and different amount
                        if($o->status == -1){
                            //buy failed in market, buy again!
                            $client = new OKCoin(new OKCoin_ApiKeyAuthentication($aip->key, $aip->secret));
                            $params = array('api_key' => $aip->key, 'symbol' => $aip->currency, 'type' => 'buy_market', 'price' => $amount);
                            $result = $client -> tradeApi($params);
                            if($result->result){
                                $order->orders_id = $result->order_id;
                                $order->order_id = $result->order_id;
                                $order->update();
                            }
                        }else{
                            $aiporder = AipOrdersModel::where('orders_id',$o->orders_id)->first();
                            $aiporder->avg_price=$o->avg_price;
                            $aiporder->deal_amount = $o->deal_amount;
                            $aiporder->deal_cny_amount = round($o->deal_amount * $o->avg_price,4);
                            $aiporder->status = $o->status;

                            if(!$aiporder->update()){
                                Log::error("===dingtou aiporder update failed:".var_export($aiporder->getErrors(),true));
                                echo "===dingtou aiporder update failed:".var_export($aiporder->getErrors(),true).PHP_EOL;
                                return;
                            }
                        }
                    }else{
                        Log::error("===  dingtou multi orders!");

                        $neworder = new AipOrdersModel();
                        $neworder->orders_id = $order->orders_id;
                        $neworder->order_id = $o->order_id;
                        $neworder->type = "buy_market";
                        $neworder->price = $aip->per_amount;
                        $neworder->create_at = time();
                        $neworder->status = $o->status;
                        $neworder->symbol=$aip->currency;
                        $neworder->avg_price=$o->avg_price;
                        $neworder->deal_amount = $o->deal_amount;
                        $neworder->deal_cny_amount = round($o->deal_amount * $o->avg_price,4);
                        $neworder->aip = $aip->id;

                        if(!$neworder->save()){
                            //TODO:邮件通知没买成
                            Log::error("=== dingtou neworder persist failed:".var_export($neworder->getErrors(),true));
                            echo "===dingtou neworder persist failed:".var_export($neworder->getErrors(),true).PHP_EOL;
                            return;
                        }
                    }
                }
            }

        }else{
            //TODO:邮件通知没有买成功
            echo "---".PHP_EOL;
            Log::error("=== dingtou tradeapi return false:".var_export($result,true));
        }
    }

    // 价值定投
    private function estimat_value_aip($aip){
        $orderModel = AipOrdersModel::where('aip_id',$aip->id)->where('status',2);
        $total_btc = $orderModel->sum("deal_amount");
        $total_cny = $orderModel->sum("deal_cny_amount");
        $total_per_amount = $orderModel->sum("price");
        $ticker = TickerModel::where('mid',1)->where('symbol',$aip->currency)->orderBy("date",'desc')->first();
        Log::error("===".var_export($order,true));
        if(!$order || $total_per_amount == 0){
            return $aip->per_amount;
        }else{
            $real_cny = round($ticker->buy * $total_btc,2);
            Log::error("===real_cny:".$real_cny);
            Log::error("=== total_per_amount:". $total_per_amount);
            $amount = $aip->per_amount * ($aip->order_count+1) - $real_cny;
            if($amount > $aip->per_amount * 5){
                $amount = $aip->per_amount * 5;
            }
            return $amount;
        }
    }
}
