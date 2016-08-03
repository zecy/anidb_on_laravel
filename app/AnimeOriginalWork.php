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
}
