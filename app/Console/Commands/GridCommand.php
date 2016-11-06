<?php

namespace App\Console\Commands;

use App\Jobs\BCoin\OKCoin;
use App\Jobs\BCoin\OKCoinRpc\OKCoin_ApiKeyAuthentication;
use App\Jobs\BCoin\OKCoinRpc\OKCoin_Exception;

use App\Models\AipModel;
use App\Models\AipOrdersModel;
use App\Models\GridModel;
use App\Models\GridOrdersModel;
use App\Models\TickerModel;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Log;
use Exception;

class GridCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'grid:buy';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = ' grid buy';

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
        echo Carbon::now()  ." : grid Buy Command index".PHP_EOL;
        // TODO 每分钟获取一次,进行缓存,此处应从缓存去读。获取OKCoin行情（盘口数据）
        // 获取全部网格数据
        $grids = GridModel::all();
        $ticker = [];
        try{
            $client = new OKCoin(new OKCoin_ApiKeyAuthentication($grids[0]->key, $grids[0]->secret));
            $params = array('symbol' => 'btc_cny');
            $ticker = $client -> tickerApi($params);
            var_dump($ticker);
        }catch(OKCoin_Exception $e){
            echo "=== GridCommand END with exception:".($e->getMessage()).PHP_EOL;
            echo Carbon::now() ." : GridCommand END at:".time().PHP_EOL;
        }
        foreach ($grids as $grid){

            $this->process($grid,$ticker);
        }
        echo Carbon::now() ." : DingtouCommand END at:".time().PHP_EOL;
    }
    private function process($grid,$ticker)
    {
        //get all open grid orders, check whether I should close them.
        $orders = GridOrdersModel::where('gid',$grid->id)
            -> where('buy_status' , 2)
            -> where('status' , 0)
            -> orderBy('buy_at' , 'desc')
            -> get();
        //if no open grid order, then buy one
        if(empty( $orders)){
            // no order, buy one
            echo " no order, buy one";
            $this->buy($grid);
            return ;
        }
        //if the price is lower than the latest order + step, then buy one more.
        if($ticker->ticker->sell < ( $orders[0]->buy_price-$grid->step)){
            echo " price down, buy one";
            echo $grid->key;
            $this->buy($grid);
            return ;
        }
        foreach ( $orders as $order){
            if($ticker->ticker->buy > $order->buy_price + $grid->step){
                echo " price up, sell one";
                $this->sell($grid,$order);
            }
        }
    }
    private function sell($grid,$order)
    {
        echo Carbon::now() ." : Grid  sell start at:". $grid->id .time().PHP_EOL;

        try{
            $client = new OKCoin(new OKCoin_ApiKeyAuthentication($grid->key, $grid->secret));
            $params = array('api_key' => $grid->key, 'symbol' => 'btc_cny', 'type' => 'sell_market', 'amount' => $order->buy_coin_amount);
            $tradeResult = $client -> tradeApi($params);
            echo "===grid sell tradeApi :".var_export($tradeResult,true);
            if( $tradeResult['result'] !== true){
                throw new Exception( "===grid tradeapi return false:".var_export($tradeResult,true) );
            }

            sleep(1);
            $params = array('api_key' => $grid->key, 'symbol' => 'btc_cny', 'order_id' => $tradeResult['order_id']);
            $orderInfoResult = $client -> orderInfoApi($params);
            echo "===grid orderInfoApi :".var_export($orderInfoResult,true);
            if($orderInfoResult['result'] !== true){
                throw new Exception( "===grid orderInfoApi return false:".var_export($orderInfoResult,true));
            }
            $order->sell_orders_id = $orderInfoResult['orders'][0]['orders_id'];
            $order->sell_order_id = $orderInfoResult['orders'][0]['order_id'];
            $order->sell_at = time ();
            $order->sell_status = $orderInfoResult['orders'][0]['status'];

            foreach ( $orderInfoResult['orders'] as $o ) {
                if($o['status'] == 2){
                    $order->sell_coin_amount += $o['deal_amount'];
                    $order->sell_cny_amount += round ( $o['deal_amount'] * $o['avg_price'], 4 );
                }
            }
            $order->sell_price = round($order->sell_cny_amount/$order->sell_coin_amount,4);
            $order->status=1;
            $order->profit = round($order->sell_cny_amount- $order->buy_cny_amount,2);
            if (! $order->update ()) {
                throw new Exception( "===grid neworder update failed:" . var_export ( $order->getErrors (), true ) );
            }
        }catch(OKCoin_Exception $e){
            // TODO:邮件通知没买成
            echo "---".PHP_EOL;
            echo "=== GridCommand END with exception:".($e->getMessage()).PHP_EOL;
        }catch (Exception $e){
            // TODO:邮件通知没买成
            echo "---".PHP_EOL;
            echo "=== GridCommand END with exception:".($e->getMessage()).PHP_EOL;
        }
        echo Carbon::now() ." : Grid  sell end at:". $grid->id .time().PHP_EOL;


    }
    private function buy($grid)
    {
        echo Carbon::now() ." : Grid  sell start at:". $grid->id .time().PHP_EOL;

        try{
            $client = new OKCoin(new OKCoin_ApiKeyAuthentication($grid->key, $grid->secret));
            $params = array('api_key' => $grid->key, 'symbol' => 'btc_cny', 'type' => 'buy_market', 'price' => $grid->amount);
            $tradeResult = $client -> tradeApi($params);
            echo "===grid tradeApi :".var_export($tradeResult,true);
            if($tradeResult['result'] !== true){
                throw new Exception( "===grid tradeapi return false:".var_export($tradeResult,true) );
            }

            sleep(1);
            $params = array('api_key' => $grid->key, 'symbol' => 'btc_cny', 'order_id' => $tradeResult->order_id);
            $orderResult = $client -> orderInfoApi($params);
            echo "===grid orderInfoApi :".var_export($orderResult,true) ;
            if($orderResult['result'] !== true){
                throw new Exception( "===grid orderInfoApi return false:".var_export($orderResult,true));
            }

            foreach ( $orderResult['orders'] as $o ) {
                $neworder = new GridOrdersModel() ;
                $neworder->buy_orders_id = $o['orders_id'];
                $neworder->buy_order_id = $o['order_id'];
                $neworder->buy_price = $o['avg_price'];
                $neworder->buy_coin_amount = $o['deal_amount'];
                $neworder->buy_cny_amount = round ( $o['deal_amount'] * $o['avg_price'], 4 );
                $neworder->buy_at = time ();
                $neworder->buy_status = $o['status'];
                $neworder->gid = $grid->id;
                $neworder->status=0;
                $neworder->profit=0;

                $neworder->direction = 1;// 1. price lower than the init, buy the coin, 2. price higher than init, sell coin first
                if (! $neworder->save ()) {
                    throw new Exception(  "===grid neworder persist failed:" . var_export ( $neworder->getErrors (), true ));
                }
            }
        }catch(OKCoin_Exception $e){
            // TODO:邮件通知没买成
            echo "---".PHP_EOL;
            echo "=== GridCommand END with exception:".($e->getMessage()).PHP_EOL;
        }catch (Exception $e){
            // TODO:邮件通知没买成
            echo "---".PHP_EOL;
            echo "=== GridCommand END with exception:".($e->getMessage()).PHP_EOL;
        }
        echo Carbon::now() ." : Grid  sell start at:". $grid->id .time().PHP_EOL;


    }
}
