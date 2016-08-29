<?php

namespace App\Console\Commands;

use App\Jobs\BCoin\BTCC;
use App\Jobs\BCoin\BtcTrade;
use App\Jobs\BCoin\HuoBi;
use App\Jobs\BCoin\OKCoin;
use App\Jobs\BCoin\OKCoinRpc\OKCoin_ApiKeyAuthentication;
use App\Jobs\BCoin\Poloniex;
use Illuminate\Console\Command;

class BDataNowCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'BData:now 
                            {platformName : 平台的名称} 
                            {symbol : B的代号} 
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
//        $type       = $this->argument('type');
        $type          = '1min';
        $result = [];
        // 根据平台名称调用不同的接口
        switch ( $platformName ){
            // 获取 OKCoin 行情
            case 'OkCoin':
                $client = new OKCoin(new OKCoin_ApiKeyAuthentication());
                $result = $client -> tickerApi( $symbol );
                break;
            // 获取 OKCoin 行情
            case 'HuoBi':
                $result = HuoBi::tickerApi( $symbol );
                break;
            // 获取 比特币交易网 行情
            case 'BtcTrade':
                $result = BtcTrade::tickerApi( $symbol );
                break;
            // 获取 BTCC 行情
            case 'BTCC':
                $result = BTCC::tickerApi( $symbol );
                break;
            // 获取 P网 行情
            case 'Poloniex':
                $poloniex =  new Poloniex();
                $result = $poloniex->get_ticker();
                break;
        }
        print_r($result);
    }
}
