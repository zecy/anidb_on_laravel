<?php

namespace App\Http\Controllers;

use App\AnimeOnair;
use Illuminate\Http\Request;

use App\Http\Requests;

class onairController extends Controller
{
    public function store(Request $request)
    {
        $pnairDatas = $request->all()['data'];

        \DB::transaction(function () use ($pnairDatas) {
            foreach ($pnairDatas as $onairData ) {
                $onair = AnimeOnair::create(
                    [
                        'anime_id'       => $onairData['animeID'],
                        'tv_name'        => $onairData['tvName'],
                        'oa_start_date'  => date_create($onairData['startDate']),
                        'oa_end_date'    => $onairData['endDate'] ? date_create($onairData['endDate']) : null,
                        'oa_start_time'  => $onairData['startTime'],
                        'oa_end_time'    => $onairData['endTime'],
                        'oa_weekday'     => $onairData['weekday'],
                        'is_production'  => $onairData['isProduction'],
                        'oa_tv_column'   => $onairData['tvColumn'],
                        'oa_description' => $onairData['description']
                    ]
                );
            }
        });

        \DB::commit();

        return \Response::json();
    }

    public function destroy($id)
    {
        $ID = $id;

        try {
            $staff = AnimeOnair::find($ID);
            $staff->delete();
            return \Response::json();
        }
        catch(\Exception $e) {
            throw $e;
        }
    }
}
