<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AnimeOriginalWorkSupport extends Model
{
    protected $table      = 'anime_original_work_support';
    protected $primaryKey = 'ori_id';
    protected $visible = array('ori_id', 'ori_pid', 'ori_catalog', 'haschild','ori_level','multiple_children', 'multiple_selected');
}