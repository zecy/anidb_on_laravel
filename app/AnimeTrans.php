<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AnimeTrans extends Model
{
    protected $table      = 'anime_translation';
    protected $primaryKey = 'trans_id';
    protected $fillable   = ['trans_class',
                             'trans_name_id',
                             'trans_name',
                             'trans_language',
                             'trans_description',
                             'trans_default'];
}
