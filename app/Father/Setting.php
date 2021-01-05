<?php

namespace App\Father;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
  protected $fillable = ["key","type","value"];
}
