<?php

namespace App\Http\Controllers;

use App\AnimeCast;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests;

class castController extends Controller
{
    public function store(Request $request)
    {
        $castMembers = $request->all()['data'];

        \DB::transaction(function () use ($castMembers ) {
            foreach ($castMembers as $castMember ) {
                $cast = AnimeCast::create(
                    [
                        'cast_anime_id'  => $castMember['animeID'],
                        'charaNameOri'   => $castMember['charaNameOri'],
                        'cvNameOri'      => $castMember['cvNameOri'],
                        'cast_important' => $castMember['isImportant'],
                        'cast_main'      => true
                    ]
                );
            }
        });

        \DB::commit();

        return \Response::json(['status' => '200']);
    }
}
