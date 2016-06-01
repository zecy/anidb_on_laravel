<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AnimeCast extends Model
{
    protected $table      = 'anime_cast';
    protected $primaryKey = 'cast_id';
    protected $fillable   = [
        'cast_anime_id',
        'charaNameOri',
        'cvNameOri',
        'cast_main',
        'cast_important',
    ];
}
