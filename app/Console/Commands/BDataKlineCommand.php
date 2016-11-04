<?php

namespace App\Console\Commands;
use App\Jobs\BCoin\BTCC;
use App\Jobs\BCoin\BtcTrade;
use App\Jobs\BCoin\HuoBi;
use App\Jobs\BCoin\OKCoin;
use App\Jobs\BCoin\OKCoinRpc\OKCoin_ApiKeyAuthentication;
use App\Jobs\BCoin\Poloniex;
use App\Models\HuoBiBtc1MinKlineDataModel;
use App\Models\OkCoinBtc1MinKlineDataModel;
use Carbon\Carbon;
use Illuminate\Console\Command;

class BDataKlineCommand extends Command
{
    /**
     * The name and signature of the console command.
     *  type 参数 默认分钟线
     * 1min : 1分钟
     * 3min : 3分钟
     * 5min : 5分钟
     * 15min : 15分钟
     * 30min : 30分钟
     * 1day : 1日
     * 3day : 3日
     * 1week : 1周
     * 1hour : 1小时
     * 2hour : 2小时
     * 4hour : 4小时
     * 6hour : 6小时
     * 12hour : 12小时
     * @var string
     */
    protected $signature = 'BData:kline 
                            {platformName : 平台的名称} 
                            {symbol : 获取哪种B} 
                            {--type=1min : kline类型} 
                            ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'In order to obtain the kline data';

    /**
     * Create a new command instance.
     *
     * @return void
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
        $startTime =  Carbon::now();
        try {
            // 获取参数
            $platformName = $this->argument('platformName');
            $symbol = $this->argument('symbol');
            $type = $this->option('type');
            $result = [];
            echo $startTime. ' : ' . $platformName . " $symbol " . ' begin ' . PHP_EOL;
            // 根据平台名称调用不同的接口
            switch ($platformName) {
                // 获取OKCoin Kline行情
                case 'OkCoin':
                    // 每天十二点获取分钟线存储
                    $client = new OKCoin(new OKCoin_ApiKeyAuthentication());
                    $result = $client->klineDataApi($symbol, $type);
                    foreach ($result as $value) {
                        $inArr[] = [
                            'date' => $value[0] / 1000,
                            'open' => $value[1],
                            'high' => $value[2],
                            'low' => $value[3],
                            'last' => $value[4],
                            'vol' => $value[5],
                        ];
                    }
                    if (empty($inArr)) {
                        echo '数据获取失败!';
                        break;
                    }
                    $resCount = OkCoinBtc1MinKlineDataModel::insert($inArr);
                    echo date('Y-m-d H:i:s', $inArr[0]['date']) . ' to ' . date('Y-m-d H:i:s', end($inArr)['date']) . ' results ' . $resCount . "\r\n";
                    break;
                // 获取 HuoBi Kline行情
                case 'HuoBi':
                    $result = HuoBi::klineDataApi($symbol, $type);
                    foreach ($result as $value) {
                        $inArr[] = [
                            'date' => strtotime($value[0] / 100000),
                            'open' => $value[1],
                            'high' => $value[2],
                            'low' => $value[3],
                            'last' => $value[4],
                            'vol' => $value[5],
                        ];
                    }
                    if (empty($inArr)) {
                        echo '数据获取失败!';
                        break;
                    }
                    $resCount = HuoBiBtc1MinKlineDataModel::insert($inArr);
                    echo date('Y-m-d H:i:s', $inArr[0]['date']) . ' to ' . date('Y-m-d H:i:s', end($inArr)['date']) . ' results ' . $resCount . "\r\n";
                    break;
                // 获取 比特币交易网 Kline行情
                case 'BtcTrade':
                    echo 'not ok';
                    break;
                    $result = BtcTrade::klineDataApi();
                    foreach ($result as $value) {
                        $inArr[] = [
                            'date' => strtotime($value[0] / 100000),
                            'open' => $value[1],
                            'high' => $value[2],
                            'low' => $value[3],
                            'last' => $value[4],
                            'vol' => $value[5],
                        ];
                    }
                    if (empty($inArr)) {
                        echo '数据获取失败!';
                        break;
                    }
                    $resCount = HuoBiBtc1MinKlineDataModel::insert($inArr);
                    echo date('Y-m-d H:i:s', $inArr[0]['date']) . ' to ' . date('Y-m-d H:i:s', end($inArr)['date']) . ' results ' . $resCount . "\r\n";
                    break;
                // 获取 BTCC Kline行情
                case 'BTCC':
                    echo 'not ok';
                    break;
                    $result = BTCC::klineDataApi();
                    break;
                // 获取 Poloniex Kline行情
                case 'Poloniex':
                    echo 'not ok';
                    break;
                    $poloniex = new Poloniex('', '');
                    $result = $poloniex->get_trade_history('BTC_NXT');
                    break;
                default:
                    echo '还未开发的网站!';
                    break;
            }
        }catch (\Exception $e ){
            \Log::error($startTime . $e->getMessage());
        }
    }
}
