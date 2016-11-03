<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


/**
 * This is the model class for table "arbitrage_price".
 *
 * The followings are the available columns in table 'arbitrage_price':
 * @property string $id
 * @property integer $type
 * @property string $market1
 * @property string $market2
 * @property string $buy1
 * @property string $buy2
 * @property string $buy2_exchange_rate
 * @property string $sell1
 * @property string $sell2
 * @property string $sell2_exchange_rate
 * @property string $ratio
 * @property string $at
 */

class ArbitragePriceModel extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'arbitrage_price';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',           // ID,
        'type',         // Type,
        'market1',      // Market1,
        'market2',      // Market2,
        'buy1',         // Buy1,
        'buy2',         // Buy2,
        'buy2_exchange_rate',    // Buy2 Exchange Rate,
        'sell1',        // Sell1,
        'sell2',        // Sell2,
        'sell2_exchange_rate',    // Sell2 Exchange Rate,
        'ratio',        // Ratio,
        'at',           // At,
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    public $timestamps = false;

}
