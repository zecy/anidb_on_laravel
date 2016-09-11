<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AnimeDataState extends Model
{
    protected $table = 'anime_data_state';
    protected $primaryKey = 'id';
    protected $fillable = [
        'anime_id',
        'series',
        'title',
        'ori_works',
        'url',
        'eps',
        'duration',
        'time_format',
        'media',
        'date',
        'time',
        'story',
        'description',
        'staff',
        'cv',
        'thumb',
        'poster',
        'op_themes',
        'ed_themes',
        'insert_songs',
    ];
}
