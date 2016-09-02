<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\AnimeDataState;
use Illuminate\Support\Facades\DB;

class AnimeDataStateController extends Controller
{
    public function store(Request $request)
    {
        $arr = $request->all()['data'];

        \DB::transaction(function () use ($arr) {

            $res = AnimeDataState::create(
                [
                    'anime_id'     => $arr['id'],
                    'series'       => $arr['s_series'],
                    'title'        => $arr['s_title'],
                    'ori_works'    => $arr['s_ori_works'],
                    'url'          => $arr['s_url'],
                    'eps'          => $arr['s_eps'],
                    'duration'     => $arr['s_duration'],
                    'time_format'  => $arr['s_time_format'],
                    'media'        => $arr['s_media'],
                    'date'         => $arr['s_date'],
                    'time'         => $arr['s_time'],
                    'story'        => FALSE,
                    'description'  => $arr['s_description'],
                    'staff'        => $arr['s_staff'],
                    'cv'           => $arr['s_cv'],
                    'thumb'        => $arr['has_thumb'],
                    'poster'       => $arr['has_poster'],
                    'op_themes'    => $arr['s_op_themes'],
                    'ed_themes'    => $arr['s_ed_themes'],
                    'insert_songs' => $arr['s_insert_songs'],
                ]
            );

        });

        \DB::commit();

        return \Response::json();
    }

    public function update(Request $request, $id) {

        $states = $request->all()['data'];

        try {
            \DB::transaction(function () use ($states) {
                $theInfo = AnimeDataState::where('anime_id', $states['anime_id'])
                    ->update([
                        'anime_id'     => $states['anime_id'],
                        'series'       => $states['s_series'],
                        'title'        => $states['s_title'],
                        'ori_works'    => $states['s_ori_works'],
                        'url'          => $states['s_url'],
                        'eps'          => $states['s_eps'],
                        'duration'     => $states['s_duration'],
                        'time_format'  => $states['s_time_format'],
                        'media'        => $states['s_media'],
                        'date'         => $states['s_date'],
                        'time'         => $states['s_time'],
                        'description'  => $states['has_description'],
                        'staff'        => $states['s_staff'],
                        'cv'           => $states['s_cv'],
                        'thumb'        => $states['has_thumb'],
                        'poster'       => $states['has_poster'],
                        'op_themes'    => $states['s_op_themes'],
                        'ed_themes'    => $states['s_ed_themes'],
                        'insert_songs' => $states['s_insert_songs']
                    ]);
            });

            \DB::commit();

            return \Response::json(['POS' => 'STATES', 'animeID' => $id]);

        }
        catch (\Exception $e) {
            throw $e;
        }
    }

    public function destroy($id) {}
}
