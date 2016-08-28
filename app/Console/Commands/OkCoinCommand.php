<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class OkCoinCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'OkCoin:getData 
                            {apiName : api的名称} 
                            {symbol : 获取数据类型 btc,ltc}
                            ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'In order to obtain the data okCoin';

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
        // 获取一个用户的apiKey
        $secretKey 	= '3DFC88442E5AE46D5A69527C35417986';
        $apiKey 	= 'd97085b4-f727-480b-b0fc-2fe7cd5a252f';

        // 获取 apiName -> symbol 参数
        $apiName = $this->argument('apiName');
        $symbol  = $this->argument('symbol');
        // 调用相应的接口获取数据
        $client = new \OKCoin(new \Coin_ApiKeyAuthentication( $apiKey , $secretKey));
        // 根据方法名称调用不同的接口
        switch ( $apiName ){
            case 'tickerApi':
                // 获取OKCoin行情（盘口数据）
                $params = array('symbol' => $symbol);
                $result = $client -> tickerApi( $params );
                break;
            case 'depthApi':
                // 获取OKCoin市场深度
                $params = array('symbol' => $symbol);
                $result = $client -> depthApi( $params );
                break;
            case 'tradesApi':
                // 获取OKCoin历史交易信息
                $params = array('symbol' => $symbol);
                $result = $client -> tradesApi( $params );
                break;
            case 'klineDataApi':
                // 获取比特币或莱特币的K线数据
                $params = array(
                    'symbol' => $symbol,
                    'type'   => $symbol,
                    'size'   => $symbol,
                );
                $result = $client -> klineDataApi( $params );
                break;
            default:
                $params = array('symbol' => $symbol);
                $result = $client -> tickerApi( $params );
                break;
        }
        // 返回结果存储到redis中
        print_r($result);

    }
}
