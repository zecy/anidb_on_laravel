<?php

namespace App\Http\Controllers;

use App\AnimeInfo;
use App\AnimeBasicData;
use App\AnimeLinks;
use App\AnimeTitles;
use Illuminate\Http\Request;

use App\Http\Requests;
use PhpParser\Node\Expr\Array_;

class AnimeManagerResource extends Controller
{
    public function infoColumn() {
        return [
           'abbr',
           'title_ori',
           'title_zh_cn',
           'hp',
           'oa_date',
           'oa_time',
           'oa_year',
           'oa_season',
           'oa_timeslot',
           'lifecycle',
           's_ori_works',
           's_url',
           's_eps',
           's_duration',
           's_time_format',
           's_media',
           's_date',
           's_time',
           'has_story',
           'has_description',
           's_staff',
           'has_thumb',
           'has_poster',
           's_op_themes',
           's_ed_themes',
           's_insert_songs',
           's_cv'
       ];
    }
    public function index() {

        $animes = AnimeInfo::orderBy('oa_date')
            ->orderBy('oa_time')
            ->paginate(20, $this->infoColumn());

        return \Response::json($animes);
    }

    public function filt(Request $request) {
        $filter = $request['data'];

        $startYear = $filter['startYear'];
        $startSeason = $filter['startSeason'];
        $endYear = $filter['endYear'];
        $endSeason = $filter['endSeason'];
        $lifecycle = $filter['lifecycle'];
        $timeslot = $filter['timeslot'];

        $animes = AnimeInfo::orderBy('oa_date')
            ->orderBy('oa_time')
            ->whereBetween('oa_year', [$startYear, $endYear])
            ->whereBetween('oa_season', [$startSeason, $endSeason])
            ->where('lifecycle', $lifecycle)
            ->where('oa_timeslot', $timeslot)
            ->paginate(20, $this->infoColumn())
            ->toArray();

        $animes['total_all'] = AnimeInfo::count();

        return \Response::json($animes);
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
