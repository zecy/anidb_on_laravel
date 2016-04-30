<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClassSupport extends Model
{
    protected $table      = 'anime_class_support';
    protected $primaryKey = 'id';
    public $timestamps = false;
}
