<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AnimeStaff extends Model
{
    protected $table      = 'anime_staff';
    protected $primaryKey = 'staff_id';
    protected $fillable   = [
        'staff_anime_id',
        'staff_post_zh',
        'staff_post_ori',
        'staff_member',
        'staff_belong',
        'staff_main',
        'staff_important',
        ];
}
