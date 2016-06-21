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
                        'oa_description' => $onairData['description'],
                        'order_index'    => $onairData['orderIndex']
                    ]
                );
            }
        });

        \DB::commit();

        return \Response::json();
    }

    public function update (Request $request, $id)
    {

        $onair = $request->all()['data'];

        $ID = $id;

        try {
            \DB::transaction(function () use ($onair) {
                foreach ($onair as $oa) {
                    $oaID = $oa['id'];
                    if ($oaID != 0) {
                        $theOA = AnimeOnair::find($oaID);

                        $theOA->tv_name         = $oa['tvName'];
                        $theOA->oa_start_date   = date_create($oa['startDate']);
                        $theOA->oa_end_date     = $oa['endDate'] ? date_create($oa['endDate']) : null;
                        $theOA->oa_start_time   = $oa['startTime'];
                        $theOA->oa_end_time     = $oa['endTime'];
                        $theOA->oa_weekday      = $oa['weekday'];
                        $theOA->is_production   = $oa['isProduction'];
                        $theOA->oa_tv_column    = $oa['tvColumn'];
                        $theOA->oa_description  = $oa['description'];
                        $theOA->order_index     = $oa['orderIndex'];

                        $theOA->save();
                    } else {
                        $theOA = AnimeOnair::create(
                            [
                                'anime_id'       => $oa['animeID'],
                                'tv_name'        => $oa['tvName'],
                                'oa_start_date'  => date_create($oa['startDate']),
                                'oa_end_date'    => $oa['endDate'] ? date_create($oa['endDate']) : null,
                                'oa_start_time'  => $oa['startTime'],
                                'oa_end_time'    => $oa['endTime'],
                                'oa_weekday'     => $oa['weekday'],
                                'is_production'  => $oa['isProduction'],
                                'oa_tv_column'   => $oa['tvColumn'],
                                'oa_description' => $oa['description'],
                                'order_index'    => $oa['orderIndex']
                            ]
                        );
                    }
                }
            });

            \DB::commit();

            return \Response::json(['POS' => 'CAST', 'animeID' => $ID]);

        } catch (\Exception $e) {
            throw $e;
        }
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
