<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AnimeInfo extends Model
{
/**
 * id
 * series_id
 * title_ori
 * title_zh_cn
 * kur
 * premiere_media
 * is_sequel
 * duration_format
 * story
 * description
 * is_counted
 * oa_year
 * oa_season
 * oa_timeslot
 * oa_date
 * oa_time
 * eps_oa
 * eps_soft
 * lifecycle
 * has_thumb
 * has_poster
 * hp
 * */

    protected $table      = 'v_anime_info';
    protected $primaryKey = 'id';
}
