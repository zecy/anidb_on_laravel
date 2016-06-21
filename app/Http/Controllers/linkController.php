<?php

namespace App\Http\Controllers;

use App\AnimeLinks;
use Illuminate\Http\Request;

use App\Http\Requests;

class linkController extends Controller
{
    public function destroy($id)
    {
        $ID = $id;

        try {
            $staff = AnimeLinks::find($ID);
            $staff->delete();
            return \Response::json();
        }
        catch(\Exception $e) {
            throw $e;
        }
    }
}
