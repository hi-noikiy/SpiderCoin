<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class HuobiBtc1minKlineData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('huobi_btc_1min_kline_data', function (Blueprint $table) {
            $table->integer('date')->unique();            // 时间
            $table->double('open', 7 , 3);               // 开始价格
            $table->double('high' , 7 , 3);              // 最高价格
            $table->double('low', 7 , 3);                // 最低价格
            $table->double('last', 7 , 3);               // 最终价格
            $table->double('vol', 8 , 3);                // 交易量
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('huobi_btc_1min_kline_data');

    }
}
