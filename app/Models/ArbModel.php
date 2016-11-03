<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * This is the model class for table "arb".
 *
 * The followings are the available columns in table 'arb':
 * @property string $id
 * @property string $cnmarket
 * @property string $commarket
 * @property double $cn2com
 * @property double $com2cn
 * @property double $cn_capital
 * @property double $cn_btc
 * @property double $com_capital
 * @property double $com_btc
 * @property string $cn2com_address
 * @property string $com2cn_address
 * @property integer $create_at
 * @property string $create_by
 * @property integer $status
 */

class ArbModel extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'arb';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',               // ID,
        'cnmarket',         // 国内市场,
        'commarket',        // 国际市场,
        'cn2com',           // 国内转国,
        'com2cn',           // 国际转国内,
        'cn_capital',       // 国内资本,
        'cn_btc',           // 国内Btc,
        'com_capital',      // 国际资本,
        'com_btc',          // 国际Btc,
        'create_at',        // 创建于,
        'create_by',        // 创建者,
        'status',           // 状态,
        'cn2com_address',   // 转国际 Address,
        'com2cn_address',   // 转国内 Address,
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    public $timestamps = false;

}
