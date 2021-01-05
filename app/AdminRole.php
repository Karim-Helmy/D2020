<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AdminRole extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['admin_id','role_id'];

    /**
    * @return \Illuminate\Database\Eloquent\Relations\belongsTo
    */
    public function admin()
    {
        return $this->belongsTo('App\Admin','admin_id');
    }
    public function role()
    {
        return $this->belongsTo('App\Role','role_id');
    }
}
