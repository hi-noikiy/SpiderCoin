<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MarketModel extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'market';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id'  , //'ID',
        'name', //'Name',
        'desc', //'Desc',
        'url',  //'Url',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [ ];
    protected function getDateFormat()
    {
        return 'U';
    }
    public $timestamps = false;
//    use SoftDeletes;
//    protected $dates = ['deleted_at'];
    public static function getMarketName( $id = 1,$type = 'ALL'){
        $marketData = [
            1 => 'okcoin.cn',
            2 => 'okcoin.com'
        ];
        if ($type == 'ALL'){
            return $marketData;
        }
        return !empty( $marketData[$id]) ?  $marketData[$id] : '未命名';
    }
}
