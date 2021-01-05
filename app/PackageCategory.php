<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PackageCategory extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['package_id','category_id'];

    protected $table = 'packages_categories';

    /**
    * @return \Illuminate\Database\Eloquent\Relations\belongsToMany
    */
    public function category()
    {
        return $this->belongsTo('App\Category');
    }
}
