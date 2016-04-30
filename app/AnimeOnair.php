<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AnimeOnair extends Model
{
    protected $table      = 'anime_onair';
    protected $primaryKey = 'oa_id';
    protected $fillable   = ['oa_id',
        'anime_id',
        'tvstation_id',
        'oa_start_date',
        'oa_end_date',
        'oa_start_time',
        'oa_end_time',
        'oa_weekday',
        'oa_tv_column',
        'oa_descritption'];
}
