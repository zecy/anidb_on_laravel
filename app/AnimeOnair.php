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
        'is_production',
        'oa_description'];

    public function basicData() {
        // 连接到 AnimeBasicData, 第一个 anime_id 是 AnimeOnair 的 anime_id, 第二个 anime_id 是 AnimeBasicData 的主键.
        return $this->belongsTo('App\AnimeBasicData', 'anime_id', 'anime_id');
    }
}
