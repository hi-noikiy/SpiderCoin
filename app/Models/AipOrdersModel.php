<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
/**
 * This is the model class for table "aip_orders".
 *
 * The followings are the available columns in table 'aip_orders':
 * @property string $id
 * @property string $aip_id
 * @property string $order_id
 * @property string $type
 * @property string $price
 * @property string $avg_price
 * @property string $create_at
 * @property string $deal_amount
 * @property double $deal_cny_amount
 * @property integer $status
 * @property string $symbol
 * @property integer $orders_id
 */
class AipOrdersModel extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'aip_orders';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id' ,              // ID
        'aip_id' ,          // Aip,
        'order_id' ,        // Order,
        'type',             // Type,
        'price' ,           // 购买金额,
        'avg_price',        // 平均价格,
        'create_at' ,       // 购买时间,
        'deal_amount' ,     // 成交数量,
        'deal_cny_amount' , // 成交金额,
        'status',           // 状态,
        'symbol',           // Symbol,
        'orders_id' ,       // Orders,
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    public $timestamps = false;

}
