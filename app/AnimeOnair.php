<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AnimeOnair extends Model
{
    protected $table      = 'anime_onair';
    protected $primaryKey = 'oa_id';
    protected $fillable   = [
        'anime_id',
        'tv_name',
        'oa_start_date',
        'oa_end_date',
        'oa_start_time',
        'oa_end_time',
        'oa_weekday',
        'oa_tv_column',
        'is_produxtion',
        'oa_descritption'];
}
