<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
/**
 * This is the model class for table "grid".
 *
 * The followings are the available columns in table 'grid':
 * @property string $id
 * @property integer $fund
 * @property integer $step
 * @property string $create_by
 * @property string $create_at
 * @property integer $coins
 * @property string $profit
 * @property string $end_at
 * @property integer $amount
 * @property string $user_market
 */

class GridModel extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'grid';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',           // ID,
        'fund',         // 资金,
        'step',         // 间隔(元),
        'create_by',    // 发起者,
        'create_at',    // 发起时间,
        'coins',        // 起始币数,
        'profit',       // 利润,
        'end_at',       // 终止时间,
        'amount',       // 购买量,
        'user_market',  // 市场
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    public $timestamps = false;

}
