<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AnimeOriginalWork extends Model
{
    protected $table      = 'anime_original_work_support';
    protected $primaryKey = 'ori_id';
    protected $visible = array('ori_id', 'ori_pid', 'ori_catalog', 'haschild','ori_level','multiple');
}