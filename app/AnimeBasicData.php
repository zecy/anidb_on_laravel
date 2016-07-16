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
    protected $visible = array(
        'anime_id',
        'anime_series_id',
        'anime_abbr',
        'anime_kur',
        'anime_premiere_media',
        'anime_sequel',
        'anime_sequel_comment',
        'anime_duration_format',
        'anime_end',
        'anime_story',
        'anime_description',
        'anime_counted',
        'anime_attribute'
    );

    public function onair()
    {
        // 连接到 AnimeOnair, 第一个 anime_id 是 AnimeOnair 的 anime_id, 第二个 anime_id 是 AnimeBasicData 的主键.
        return $this->hasMany('App\AnimeOnair', 'anime_id', 'anime_id');
    }
}
