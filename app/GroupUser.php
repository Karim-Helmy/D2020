<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GroupUser extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['student_group_id','user_id'];

    /**
    * @return \Illuminate\Database\Eloquent\Relations\belongsTo
    */
    public function user()
    {
        return $this->belongsTo('App\User','user_id');
    }

}
