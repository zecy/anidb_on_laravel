<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AnimeBasicData extends Model
{
    protected $table      = 'anime_basicdata';
    protected $primaryKey = 'anime_id';
    protected $fillable   = [
        'anime_series_id',
        'anime_abbr',
        'anime_attribute',
        'anime_kur',
        'anime_premiere_media',
        'anime_sequel',
        'anime_duration_format',
        'anime_end',
        'anime_descritption',
        'anime_counted'
    ];
}
