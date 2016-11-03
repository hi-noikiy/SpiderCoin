<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
/**
 * This is the model class for table "arb_orders".
 *
 * The followings are the available columns in table 'arb_orders':
 * @property integer $id
 * @property string $arbid
 * @property integer $status
 * @property double $cnbuy
 * @property double $cnbtc_buy
 * @property double $rmbbuy_amount
 * @property double $combtc_sell
 * @property double $com_sell
 * @property double $usdsell_amount
 * @property double $sell_fee
 * @property double $exchange_rate_sell
 * @property double $buysell_rate
 * @property string $cn2com_txid
 * @property integer $cn2com_at
 * @property double $cnsell
 * @property double $cnbtc_sell
 * @property double $rmbsell_amount
 * @property double $combtc_buy
 * @property double $com_buy
 * @property double $usdbuy_amount
 * @property double $buy_fee
 * @property double $exchange_rate_buy
 * @property double $sellbuy_rate
 * @property string $com2cn_txid
 * @property integer $com2cn_at
 * @property double $profit
 */

class ArbOrdersModel extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'arb_orders';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id' ,          // ID,
        'arbid',        // Arbid,
        'status',       // Status,
        'cnbuy',        // cn买,
        'cnbtc_buy',    // CN BTC,
        'rmbbuy_amount',// CN金额,
        'combtc_sell',  // COM BTC卖,
        'com_sell',     // Com 卖价,
        'usdsell_amount',  // USD金额,
        'sell_fee',     // Sell Fee,
        'exchange_rate_sell',  //参考汇率,
        'buysell_rate', // 搬砖率,
        'cn2com_txid',  // Cn2com Txid,
        'cn2com_at',    // Cn2com At,
        'cnsell',       // Cnsell,
        'cnbtc_sell',   // Cnbtc Sell,
        'rmbsell_amount',  //Rmbsell Amount,
        'combtc_buy',   // Combtc Buy,
        'com_buy',      // Com Buy,
        'usdbuy_amount',  //Usdbuy Amount,
        'buy_fee',      // Buy Fee,
        'exchange_rate_buy',  //Exchange Rate Buy,
        'sellbuy_rate', // Sellbuy Rate,
        'com2cn_txid',  // Com2cn Txid,
        'com2cn_at',    // Com2cn At,
        'profit',       // Profit,
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    public $timestamps = false;

}
