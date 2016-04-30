<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AnimeStaff extends Model
{
    protected $table      = 'anime_staff';
    protected $primaryKey = 'staff_id';
    protected $fillable   = ['staff_anime_id',
        'staff_post_id',
        'staff_member_id',
        'staff_belong_id',
        'staff_comment',
        'staff_main',
        'staff_important',
        ];
}
