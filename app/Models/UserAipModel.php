<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * This is the model class for table "user_aip".
 *
 * The followings are the available columns in table 'user_aip':
 * @property string $id
 * @property string $uid
 * @property string $aip_id
 * @property integer $amount
 * @property string $add_at
 */
class UserAipModel extends Model{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'user_aip';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['uid', 'aip_id', 'amount','at'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];
    protected function getDateFormat()
    {
        return 'U';
    }
    public $timestamps = false;

}
