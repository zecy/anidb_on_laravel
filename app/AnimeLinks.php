<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AnimeLinks extends Model
{
    protected $table      = 'anime_links';
    protected $primaryKey = 'link_id';
    protected $fillable   = ['link_class',
                             'anime_id',
                             'link_comment',
                             'link_url',
                             //'link_descritption',
                             'link_is_official'
    ];
}
