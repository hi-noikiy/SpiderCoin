<?php

namespace App\Console\Commands;
use App\Jobs\BCoin\BTCC;
use App\Jobs\BCoin\BtcTrade;
use App\Jobs\BCoin\HuoBi;
use App\Jobs\BCoin\OKCoin;
use App\Jobs\BCoin\OKCoinRpc\OKCoin_ApiKeyAuthentication;
use App\Jobs\BCoin\Poloniex;
use Illuminate\Console\Command;

class BDataKlineCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'BData:kline 
                            {platformName : 平台的名称} 
                            {symbol : 获取哪种B} 
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
        
        // 获取参数
        $platformName  = $this->argument('platformName');
        $symbol        = $this->argument('symbol');
        $type          = $this->argument('type');
        $result = [];
        // 根据平台名称调用不同的接口
        switch ( $platformName ){
            // 获取OKCoin Kline行情
            case 'OkCoin':
                $client = new OKCoin( new OKCoin_ApiKeyAuthentication() );
                $type   = '1min';
                $result = $client -> klineDataApi( $symbol ,  $type );
                break;
            // 获取 HuoBi Kline行情
            case 'HuoBi':
                $type   = '001';
                $result = HuoBi::klineDataApi( $symbol , $type );
                break;
            // 获取 比特币交易网 Kline行情
            case 'BtcTrade':
                $result = BtcTrade::klineDataApi();
                break;
            // 获取 BTCC Kline行情
            case 'BTCC':
                $result = BTCC::klineDataApi();
                break;
            // 获取 BTCC Kline行情
            case 'Poloniex':
                $poloniex =  new Poloniex('','');
                $result = $poloniex->get_trade_history('BTC_NXT');
                break;
            default:
                echo '还未开发的网站!';
                break;
        }
        // 存储到redis
        print_r($result);

    }
}
