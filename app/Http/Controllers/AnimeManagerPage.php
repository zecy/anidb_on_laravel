<?php

namespace App\Http\Controllers;

use App\AnimeBasicData;
use Illuminate\Http\Request;

use App\Http\Requests;

class AnimeManagerPage extends Controller
{
    public function index() {
        return view('manager.index');
    }
}
