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

    public function update(Request $request, $id) {

        $casts = $request->all()['data'];

        $ID = $id;

        try {
            \DB::transaction(function () use ($casts) {
                foreach($casts as $cast) {
                    $castID = $cast['id'];
                    if ( $castID != 0 ) {
                        $theCast = AnimeCast::find($castID);

                        $theCast->charaNameOri    = $cast['charaNameOri'];
                        $theCast->cvNameOri       = $cast['cvNameOri'];
                        $theCast->cast_important  = $cast['isImportant'];
                        $theCast->cast_main       = true;
                        $theCast->order_index     = $cast['orderIndex'];

                        $theCast->save();
                    } else {
                        $theCast = AnimeCast::create(
                            [
                                'cast_anime_id'  => $cast['animeID'],
                                'charaNameOri'   => $cast['charaNameOri'],
                                'cvNameOri'      => $cast['cvNameOri'],
                                'cast_important' => $cast['isImportant'],
                                'order_index'    => $cast['orderIndex'],
                                'cast_main'      => true
                            ]
                        );
                    }
                }
            });

            \DB::commit();

            return \Response::json(['POS' => 'CAST', 'animeID' => $ID]);

        }
        catch (\Exception $e) {
            throw $e;
        }
    }
}
