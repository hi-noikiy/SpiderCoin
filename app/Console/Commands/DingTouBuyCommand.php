<?php

namespace App\Console\Commands;

use App\Jobs\BCoin\OKCoin;
use App\Jobs\BCoin\OKCoinRpc\OKCoin_ApiKeyAuthentication;
use App\Jobs\BCoin\OKCoinRpc\OKCoin_Exception;

use App\Models\AipModel;
use App\Models\AipOrdersModel;
use App\Models\TickerModel;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Log;
use Exception;

class DingTouBuyCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dingtou:buy';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'ding tou buy';

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
        echo Carbon::now()  ." : Dingtou Buy Command index".PHP_EOL;
        $time = getdate();
        // 查询在使用状态的定投策略
        $aipData = AipModel::where('status',1)
                ->where('minute',$time['minutes'])
//        $aipData = AipModel::where('create_by',32)
            ->get();
//        $this->buy($aipData[0]);
//die;
        foreach($aipData as $aip){
            echo "===aip:".$aip->id;
            // 判断用户设定是按照月还是日进行购买
            if($aip->period == 2){
                $hours = explode(",", $aip->hour);
                foreach ($hours as $h){
                    if($h == $time['hours']){
                        $this->buy($aip);
                        break;
                    }
                }
            }else if ($aip->period == 1){
                //by some days in the month
                //check current day
                $days = explode(",", $aip->day);
                foreach ( $days as $d){
                    if($d == $time['mday'] && intval($aip->hour) == $time['hours'] ){
                        $this->buy($aip);
                        break;
                    }
                }
            }
        }
        echo Carbon::now() ." : DingtouCommand END at:".time().PHP_EOL;
    }

    // 购买操作
    private function buy($aip){
        try{
            echo "=== aip_id:".$aip->id ." aip_type: " . $aip->aip_type .PHP_EOL;
            // 根据定投类型计算本次购买金额
            $amount = $aip->aip_type == 2 ? $this->estimat_value_aip($aip) : $aip->per_amount;
            echo "===== amount:".$amount .PHP_EOL;
            // 在okCoin下订单
            $client = new OKCoin(new OKCoin_ApiKeyAuthentication($aip->key, $aip->secret));
            $params = array('api_key' => $aip->key, 'symbol' => $aip->currency, 'type' => 'buy_market', 'price' => 5);
            echo "=== order:".var_export($params,true).PHP_EOL;
            $tradeResult = $client -> tradeApi($params);
            echo "=== dingtou tradeApi :".var_export($tradeResult,true).PHP_EOL;
            // 如果返回失败,通知失败
            if($tradeResult['result'] == false){
                echo "---".PHP_EOL;
                throw new Exception("=== 本次下单失败",422);
            }
            // 下单成功后 , 创建订单
            $order = new AipOrdersModel();
            $order->orders_id = $tradeResult['order_id'];
            $order->order_id = $tradeResult['order_id'];
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
            }
            $orderInfoCount = 0;
            $orderInfoResult = [];
            // 等待交易成功.三次重试机会
            while ( $orderInfoCount < 3){
                $orderInfoCount += 1;
                sleep(1);
                $params = array('api_key' => $aip->key, 'symbol' => $aip->currency, 'order_id' => $tradeResult['order_id']);
                $orderInfoResult = $client -> orderInfoApi($params);
                echo "===dingtou orderInfoApi :".var_export($orderInfoResult,true) . PHP_EOL;
                // 如果成功,结束循环。
                if ($orderInfoResult['result'] == true && $orderInfoResult['orders'][0]['status'] == 2){
                    break;
                }else if($orderInfoResult['result'] == false && $orderInfoCount=2){
                    // 如果循环三次均未成功状态置为失败,通知用户
                    throw  new Exception("===dingtou orderInfoApi :".var_export($orderInfoResult,true));
                    break;
                }
            }
            $newCoin = 0;
            $newCny = 0;
            // 进行订单记录, 只有一条信息的话进行更新,多条信息,进行插入
            foreach($orderInfoResult['orders'] as $order){
                if($order['order_id'] == $order['orders_id']){
                    //it could have more than one orders to fill the orig order at different price and different amount
                    $aipOrdersModel = AipOrdersModel::where('orders_id',$order['orders_id'])->first();
                    $aipOrdersModel->avg_price= $order['avg_price'];
                    $aipOrdersModel->deal_amount = $order['deal_amount'];
                    $aipOrdersModel->deal_cny_amount = round($order['deal_amount'] * $order['avg_price'],4);
                    $aipOrdersModel->status = $order['status'];
                    if(!$aipOrdersModel->update()){
                        echo "===dingtou aiporder update failed:".var_export($aipOrdersModel->getErrors(),true).PHP_EOL;
                    }
                }else{
                    Log::error("===  dingtou multi orders!");
                    $newOrderModel = new AipOrdersModel();
                    $newOrderModel->orders_id = $order['orders_id'];
                    $newOrderModel->order_id = $order['order_id'];
                    $newOrderModel->type = "buy_market";
                    $newOrderModel->price = $aip->per_amount;
                    $newOrderModel->create_at = time();
                    $newOrderModel->status = $order['status'];
                    $newOrderModel->symbol = $aip->currency;
                    $newOrderModel->avg_price = $order['avg_price'];
                    $newOrderModel->deal_amount = $order['deal_amount'];
                    $newOrderModel->deal_cny_amount = round($order['deal_amount'] *$order['avg_price'],4);
                    $newOrderModel->aip_id = $aip->id;
                    if(!$newOrderModel->save()){
                        echo "===dingtou neworder persist failed:".var_export($newOrderModel->getErrors(),true).PHP_EOL;
                    }
                }
                $newCoin +=  $order['deal_amount'];
                $newCny +=  round($order['deal_amount'] * $order['avg_price'],4);
            }
            // 购买成功,购买次数加一
            $aip->order_count += 1 ;
            // coins 购买成功,购买币数总数相加
            $aip->total_btc += $newCoin ;
            // cny 购买成功,购买人民币总数相加
            $aip->used_cny_amount += $newCny ;
            $aip->save();
        }catch(OKCoin_Exception $e){
            echo "=== DingtouCommand END with exception:". $e->getMessage() .PHP_EOL;
            \Mail::send('frontend.mail.dingtouBuyError', $aip->toArray(), function($message) {
                $message->to('yaozihao@yaozihao.cn')->subject('定投下订单出错了!');
            });die;
        }catch(\Exception $e){
            echo "=== DingtouCommand END with exception:".  $e->getMessage() .PHP_EOL;
            \Mail::send('frontend.mail.dingtouBuyError', $aip->toArray(), function($message) {
                $message->to('yaozihao@yaozihao.cn')->subject('定投下订单出错了!');
            });die;
        }
        echo "=== aip_end : " . $aip->id;
        return true ;
    }

    // 价值定投
    private function estimat_value_aip($aip){
        // 如果是第一次购买,直接返回定投金额
        if(empty($aip->total_btc)){
            return $aip->per_amount;
        }
        // 获取现在的币价
        $ticker = TickerModel::where('mid',1)
            ->where('symbol',$aip->currency)
            ->orderBy("date",'desc')
            ->first();
        // 现在币价 * 数量 = 现有价值金额
        $real_cny = round($ticker->buy * $aip->total_btc,2);
        // 定投金额 * 购买次数 = 应有价值
        $should_cny = $aip->per_amount * ($aip->order_count+1) ;
        // 应有价值 - 现有价值金额 = 本次购买金额
        $amount = $should_cny - $real_cny;
        echo "===".' 现有价值金额 : '.$real_cny . ', 应有价值 : '. $should_cny .' ,应有价值: '.$amount;
        if($amount < 0 ){
            throw new Exception('价值定投 : 本次所需购买金额为 0',422);
        }
        // 如果本次金额大于定投金额的五倍设置为5倍
        if($amount > $aip->per_amount * 5){
            $amount = $aip->per_amount * 5;
        }
        return round($amount,2);
    }
}
