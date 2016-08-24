<?php

namespace App\Http\Controllers;

use App\AnimeBasicData;
use App\AnimeOnair;
use Illuminate\Http\Request;

use App\Http\Requests;

class AnimeManagerResource extends Controller
{
    public function index() {

        $animes = AnimeBasicData::orderBy('anime_oa_year')
            ->orderBy('anime_oa_season')
            ->get();

        $animeInfo = [];

        foreach($animes as $anime) {

            $animeBaiscData = $anime->toArray();

            $id = $animeBaiscData['anime_id'];

            $abbr = $animeBaiscData['anime_abbr'];

            $animeTitle_Ori = $anime->titles()
                ->where('is_official', true)
                ->where('lang', 'jp')
                ->orderBy('order_index')
                ->take(1)
                ->get(array('title'))
                ->toArray();

            $animeTitle_zh_cn = $anime->titles()
                ->where('lang', 'zh-cn')
                ->orderBy('order_index')
                ->take(1)
                ->get(array('title'))
                ->toArray();

            $animeOA = $anime->onair()
                ->orderBy('oa_start_date')
                ->take(1)
                ->get(array('oa_start_date as date'))
                ->toArray();

            $animeOA = $animeOA != [] ? $animeOA[0]['date'] : '';

            $singleAnimeInfo = [
                'id'          => $id,
                'abbr'        => $abbr,
                'title_ori'   => $animeTitle_Ori[0]['title'],
                'title_zh_cn' => $animeTitle_zh_cn[0]['title'],
                'date'        => $animeOA
            ];

            $animeInfo[] = $singleAnimeInfo;

        }

        usort($animeInfo, function($a, $b){
            if ($a['date'] === '') {
                return 1;
            } elseif ($b['date'] === '') {
                return -1;
            } else {
                $aDate = strtotime($a['date']);
                $bDate = strtotime($b['date']);

                if ($aDate === $bDate) return 0;

                return ($aDate > $bDate) ? 1 : -1;
            }
        });

        return \Response::json($animeInfo);
    }

    public function filt(Request $request) {
        dd($request);
    }

    public function store(Request $request) {

        $data = $request->all()['data'];



        return \Response::json($data);
    }
}
