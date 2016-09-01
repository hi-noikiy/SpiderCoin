<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HuoBiBtc1MinKlineDataModel extends Model
{
    protected $connection = 'mysql';
    protected $table = 'huobi_btc_1min_kline_data';
    protected $fillable = array('date','open','high' ,'low', 'last', 'vol');
    protected $dateFormat = 'U';
}
