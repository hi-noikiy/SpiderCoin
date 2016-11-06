<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * This is the model class for table "grid_orders".
 *
 * The followings are the available columns in table 'grid_orders':
 * @property string $id
 * @property string $gid
 * @property integer $direction
 * @property string $buy_order_id
 * @property string $buy_orders_id
 * @property string $buy_price
 * @property string $buy_cny_amount
 * @property string $buy_coin_amount
 * @property string $buy_at
 * @property integer $buy_status
 * @property string $sell_order_id
 * @property string $sell_orders_id
 * @property string $sell_price
 * @property string $sell_cny_amount
 * @property string $sell_coin_amount
 * @property string $sell_at
 * @property integer $sell_status
 * @property integer $status
 * @property string $profit
 *
 * The followings are the available model relations:
 * @property Grid $g
 */

class GridOrdersModel extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'grid_orders';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',               // ID,
        'gid',              // Gid,
        'direction',        // Direction,
        'buy_order_id',     // Buy Order,
        'buy_orders_id',    // Buy Orders,
        'buy_price',        // 买价,
        'buy_cny_amount',   // 购买金额,
        'buy_coin_amount',  // 购买币数,
        'buy_at',           // 购买时间,
        'buy_status',       // 购买状态,
        'sell_order_id',    // Sell Order,
        'sell_orders_id',   // Sell Orders,
        'sell_price',       // 卖价,
        'sell_cny_amount',  // 卖出金额,
        'sell_coin_amount', // 卖出币数,
        'sell_at',          // 卖出时间,
        'sell_status',      // 卖出状态,
        'status',           // Status,
        'profit',           // 利润,
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    public $timestamps = false;

}
