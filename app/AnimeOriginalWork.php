<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AnimeOriginalWork extends Model
{
    protected $table = 'anime_original_work';
    protected $primaryKey = 'id';
    protected $fillable = [
        'anime_id',
        'ori_id',
        'ori_pid',
        'lv',
        'haschild',
        'multiple_children',
        'multiple_selected'
    ];

    public function orisupport()
    {
        // 连接到 AnimeOriginalWorkSupport, 第一个 ori_id 是 AnimeOriginalWork 的 ori_id, 第二个 ori_id 是 AnimeOriginalWorkSupport 的主键.
        return $this->belongsTo('App\AnimeOriginalWorkSupport', 'ori_id', 'ori_id');
    }
}

