<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
/**
 * This is the model class for table "aip".
 *
 * The followings are the available columns in table 'aip':
 * @property string $id
 * @property integer $per_amount
 * @property integer $aip_type
 * @property integer $amount_limit
 * @property string $start_at
 * @property string $end_at
 * @property integer $stop_profit_percentage
 * @property string $create_by
 * @property string $create_at
 * @property integer $period
 * @property integer $status
 * @property integer $fund
 * @property string $currency
 * @property string $key
 * @property string $secret
 * @property integer $ispublic
 * @property string $used_cny_amount
 * @property string $profit
 * @property string $total_btc
 * @property string $day
 * @property string $hour
 * @property integer $minute
 * @property integer $mature
 * @property integer $drawdown_type
 * @property integer $drawdown
 * @property integer $sellout
 * @property string $keyid
 * @property integer $order_count
 */
class AipModel extends Model
{
    const STATUS_YES = '1';
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'aip';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'per_amount',               // 定投金额
        'start_at',                 // 开始时间
        'end_at',                   // 结束时间
        'stop_profit_percentage',   // 止盈百分比
        'create_by',                // 创建者
        'period',                   // 购买周期
        'status',                   // 状态
        'fund',                     // 资金
        'currency',                 // 货币
        'key',                      // Key
        'secret',                   // secret
        'ispublic',                 // ispublic
        'used_cny_amount',          // 本金
        'profit',                   // 利润
        'total_btc',                // 已购买BTC
        'hour',                     // 小时
        'day',                      // 日
        'drawdown',                 // 回撤金额
        'keyid',                    // 市场授权Key
        'aip_type',                 // 定投类型
        'amount_limit'              // 最大购买倍数
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    public $timestamps = false;

}
