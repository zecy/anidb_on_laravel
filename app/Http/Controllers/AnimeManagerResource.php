<?php

namespace App\Http\Controllers;

use App\AnimeBasicData;
use App\AnimeLinks;
use App\AnimeTitles;
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

        $datas = $request->all()['data'];
        $c = 0;

        foreach ($datas as $data) {

//            TODO: 已导入动画验证, 避免重复导入

            \DB::transaction(function () use ($data) {
                //基本信息
                $basicData = AnimeBasicData::create([
                    'anime_series_id'       => 0,
                    'anime_abbr'            => $data['abbr'],
                    'anime_attribute'       => '',
                    'anime_kur'             => 1,
                    'anime_premiere_media'  => 'tv',
                    'anime_sequel'          => false,
                    'anime_duration_format' => 'general',
                    'anime_oa_year'         => $data['oa_year'],
                    'anime_oa_season'       => $data['oa_season'],
                    'anime_oa_time'         => 'midnight',
                    'anime_lifecycle'       => $data['lifecycle'],
                    'anime_eps_oa'          => 0,
                    'anime_eps_soft'        => 0,
                    'anime_counted'         => true
                ]);

                $basicData->anime_description = '';
                $basicData->anime_story = '';
                $basicData->anime_sequel_comment = '';

                $basicData->save();

                //生成 ID
                $ID = $basicData->anime_id;

                // Title
                $titleOri = AnimeTitles::create(
                    [
                        'anime_id'    => $ID,
                        'title'       => $data['title_ori'],
                        'lang'        => 'jp',
                        'description' => '',
                        'is_official' => true,
                        'order_index' => 0
                    ]
                );

                $titleZhcn = AnimeTitles::create(
                    [
                        'anime_id'    => $ID,
                        'title'       => $data['title_zhcn'],
                        'lang'        => 'zh-cn',
                        'description' => '',
                        'is_official' => false,
                        'order_index' => 1
                    ]
                );

                // Links
                $link = AnimeLinks::create([
                    'link_class'       => 'hp',
                    'anime_id'         => $ID,
                    'link_comment'     => '',
                    'link_url'         => $data['hp'],
                    'link_is_official' => true
                ]);

            });

            \DB::commit();

            ++$c;

        }
        return \Response::json($c);
    }
}
