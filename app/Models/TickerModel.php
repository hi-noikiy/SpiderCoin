<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
/**
 * This is the model class for table "ticker".
 *
 * The followings are the available columns in table 'ticker':
 * @property string $id
 * @property string $date
 * @property string $buy
 * @property string $sell
 * @property string $last
 * @property string $high
 * @property string $low
 * @property string $vol
 * @property string $mid
 * @property string $symbol
 */
class TickerModel extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'ticker';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id' ,          // ID
        'date'  ,       // 日期
        'buy' ,         // Buy',
        'sell',         // Sell',
        'last',         // Last',
        'high',         // High',
        'low' ,         // Low',
        'vol' ,         // Vol',
        'mid',          // Mid',
        'symbol',       // Symbol',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    public $timestamps = false;

}
