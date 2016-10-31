<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserMarketModel extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'user_market';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'uid',      // 用户id
        'mid',      // 市场
        'key',      // 密key
        'secret',   // 秘钥
        'desc',     // 描述
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
//    use SoftDeletes;
//    protected $dates = ['deleted_at'];
    
}
