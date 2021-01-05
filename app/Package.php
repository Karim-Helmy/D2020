<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Package extends Model
{
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name','price'];

    /**
    * @return \Illuminate\Database\Eloquent\Relations\belongsToMany
    */
    public function option()
    {
        return $this->belongsToMany('App\Option', 'packages_options','package_id', 'option_id')->withPivot('value');
    }
    public function category()
    {
        return $this->belongsToMany('App\Category', 'packages_categories','package_id', 'category_id');
    }
}
