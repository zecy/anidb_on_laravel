<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AnimeTitles extends Model
{
    protected $table      = 'anime_titles';
    protected $primaryKey = 'id';
    protected $fillable   = ['anime_id',
                             'title',
                             'lang',
                             'is_official',
                             'description',
                             'order_index'
    ];
}
