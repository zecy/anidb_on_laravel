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

    public function basicData() {
        // 连接到 AnimeBasicData, 第一个 anime_id 是 Title 的 anime_id, 第二个 anime_id 是 AnimeBasicData 的主键.
        return $this->belongsTo('App\AnimeBasicData', 'anime_id', 'anime_id');
    }
}
