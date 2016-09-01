<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OkCoinBtc1MinKlineDataModel extends Model
{
    protected $connection = 'mysql';
    protected $table = 'okcoin_btc_1min_kline_data';
    protected $fillable = array('date','open','high' ,'low', 'last', 'vol' ,'created_at','updated_at');
    protected $dateFormat = 'U';
}
