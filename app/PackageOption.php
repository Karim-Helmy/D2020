<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PackageOption extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['package_id','option_id','value'];

    protected $table = 'packages_options';

    /**
    * @return \Illuminate\Database\Eloquent\Relations\belongsToMany
    */
    public function option()
    {
        return $this->belongsToMany('App\Option', 'packages_options','package_id', 'option_id');
    }
}
