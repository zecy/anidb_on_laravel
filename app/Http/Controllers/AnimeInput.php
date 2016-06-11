<?php

namespace App\Http\Controllers;

use App\AnimeBasicData;
use App\AnimeLinks;
use App\AnimeTrans;
use App\AnimeOriginalWork;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Expr\Cast\Array_;

class AnimeInput extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $transLangs = \App\ClassSupport::where('class', '=', 'language')->get(array('content', 'comment'));
        $links = \App\ClassSupport::where('class', '=', 'links')->get(array('content', 'comment'));
        $premiereMedia = \App\ClassSupport::where('class', '=', 'premiere_media')->get(array('content', 'comment'));
        $animeDurationFormat = \App\ClassSupport::where('class', '=', 'anime_duration_format')->get(array('content', 'comment'));
        $oriWorks = \App\AnimeOriginalWorkSupport::all()->toJson();

        return view('input.input', compact('basicData', 'transLangs', 'links', 'premiereMedia', 'oriWorks', 'animeDurationFormat'));
    }

    /**
     *
     * @param pos $array
     *
     * 返回传递过来的名词返回唯一的 ID
     *
     */

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all()['data'];

        $res = array();

        \DB::transaction(function () use ($data, &$res) {
            //基本信息
            $basicData = AnimeBasicData::create([
                'anime_series_id'       => $data['seriesID']['value'],
                'anime_abbr'            => $data['abbr']['value'],
                'anime_kur'             => $data['kur']['value'],
                'anime_premiere_media'  => $data['premiereMedia']['value'],
                'anime_sequel'          => $data['isSequel']['value'],
                'anime_duration_format' => $data['duration']['value'],
                'anime_end'             => $data['isEnd']['value'],
                //'anime_description'     => $data['description']['value'],
                //TODO: 查清 cereate 无法插入 description 的原因
                'anime_counted'         => $data['isCounted']['value']
            ]);

            $res['basicData'] = $basicData;

            $basicData->anime_description = $data['description']['value'];

            $basicData->save();
            //生成 ID
            $ID = $basicData->anime_id;

            // Title
            foreach ($data['title'] as $theTitle) {
                $title = AnimeTrans::create(
                    [
                        'trans_class'       => 'anime_title',
                        'trans_name_id'     => $ID,
                        'trans_name'        => $theTitle['value'],
                        'trans_language'    => $theTitle['lang'],
                        'trans_description' => $theTitle['comment'],
                        'trans_default'     => $theTitle['isOfficial']
                    ]
                );
            }

            // Original Works
            $i = 0;
            foreach ($data['oriWorks'] as $lv) {
                foreach ( $lv as $origenres ) {
                    if($origenres['id'] != '' && $origenres['id'] != 0) {
                        $origenres = AnimeOriginalWork::create(
                            [
                                'anime_id' => $ID,
                                'ori_id'   => $origenres['id'],
                                'ori_pid'  => $origenres['pid'],
                                'lv'       => $i,
                                'haschild' => $origenres['haschild'],
                                'multiple' => $origenres['multiple']
                            ]
                        );
                    }
                }
                ++$i; // the level
            }

            // Links
            foreach ($data['links'] as $theLink) {
                $link = AnimeLinks::create([
                    'link_class'       => $theLink['class'],
                    'anime_id'         => $ID,
                    'link_comment'     => $theLink['comment'],
                    'link_url'         => $theLink['value'],
                    'link_is_official' => $theLink['isOfficial']
                ]);
            }

        });

        \DB::commit();

        return \Response::json($res);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $animeBasicData = \App\AnimeBasicData::where('anime_id', $id)->get()->toArray()[0];
        $animeLinks = \App\AnimeLinks::where('anime_id', $id)->get()->toArray();
        $animeTitles = \App\AnimeTrans::where('trans_class', 'anime_title')
            ->where('trans_name_id', $id)
            ->get(array(
                'trans_name',
                'trans_name_id',
                'trans_language',
                'trans_default',
                'trans_description'
            ))->toArray();
        $animeOriWorks = \App\AnimeOriginalWork::where('anime_id', $id)->get()->toArray();

        $basicData = [
            'id'            => ['label' => '动画ID', 'value' => $animeBasicData['anime_id']],
            'seriesID'      => ['label' => '系列ID', 'value' => $animeBasicData['anime_series_id']],
            'seriesTitle'   => ['label' => '系列ID', 'value' => ''],
            'abbr'          => ['label' => '简称', 'value' => $animeBasicData['anime_abbr']],
            'kur'           => ['label' => '长度', 'value' => $animeBasicData['anime_kur']],
            'duration'      => ['label' => '时间规格', 'value' => $animeBasicData['anime_duration_format']],
            'premiereMedia' => ['label' => '首播媒体', 'value' => $animeBasicData['anime_premiere_media']],
            'isSequel'      => ['label' => '是否续作', 'value' => $animeBasicData['anime_sequel']],
            'sequelComment' => ['label' => '备注', 'value' => $animeBasicData['anime_sequel_comment']],
            'isEnd'         => ['label' => '是否完结', 'value' => $animeBasicData['anime_end']],
            'isCounted'     => ['label' => '是否纳入统计', 'value' => $animeBasicData['anime_counted']],
            'story'         => ['label' => '故事', 'value' => ''],
            'description'   => ['label' => '介绍', 'value' => $animeBasicData['anime_description']],
            'title'         => [],
            'links'         => [],
            'oriWorks'      => []
        ];

        foreach ($animeTitles as $title) {
            $basicData['title'][] = [
                'lang'       => $title['trans_language'],
                'isOfficial' => $title['trans_default'],
                'value'      => $title['trans_name'],
                'comment'    => $title['trans_description']
            ];
        }

        foreach ($animeLinks as $link ) {
            $basicData['links'][] = [
                'class' => $link['link_class'],
                'isOfficial' => $link['link_is_official'],
                'value' => $link['link_url'],
                'comment' => $link['link_comment']
            ];
        }

        foreach ( $animeOriWorks as $work ) {
            $basicData['oriWorks'][$work['lv']][] = [
                'id'       => $work['ori_id'],
                'haschild' => $work['haschild'],
                'multiple' => $work['multiple'],
                'pid'      => $work['ori_pid']
            ];
        }

        return \Response::json($basicData);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // Basic Data
        $basicData = \App\AnimeBasicData::where('anime_id', $id)->get()->toArray()[0];

        $res = [
            'id'            => ['label' => '动画ID', 'value' => $basicData['anime_id']],
            'seriesID'      => ['label' => '系列ID', 'value' => $basicData['anime_series_id']],
            'seriesTitle'   => ['label' => '系列ID', 'value' => ''],
            'abbr'          => ['label' => '简称', 'value' => $basicData['anime_abbr']],
            'kur'           => ['label' => '长度', 'value' => $basicData['anime_kur']],
            'duration'      => ['label' => '时间规格', 'value' => $basicData['anime_duration_format']],
            'premiereMedia' => ['label' => '首播媒体', 'value' => $basicData['anime_premiere_media']],
            'isSequel'      => ['label' => '是否续作', 'value' => $basicData['anime_sequel']],
            'sequelComment' => ['label' => '备注', 'value' => $basicData['anime_sequel_comment']],
            'isEnd'         => ['label' => '是否完结', 'value' => $basicData['anime_end']],
            'isCounted'     => ['label' => '是否纳入统计', 'value' => $basicData['anime_counted']],
            'story'         => ['label' => '故事', 'value' => ''],
            'description'   => ['label' => '介绍', 'value' => $basicData['anime_description']]
        ];

        // Staff
        // Cast
        // Onair

        $transLangs = \App\ClassSupport::where('class', '=', 'language')->get(array('content', 'comment'));
        $links = \App\ClassSupport::where('class', '=', 'links')->get(array('content', 'comment'));
        $premiereMedia = \App\ClassSupport::where('class', '=', 'premiere_media')->get(array('content', 'comment'));
        $animeDurationFormat = \App\ClassSupport::where('class', '=', 'anime_duration_format')->get(array('content', 'comment'));
        $oriWorks = \App\AnimeOriginalWorkSupport::all()->toJson();


        return view('input.input', compact('res','transLangs', 'links', 'premiereMedia', 'oriWorks', 'animeDurationFormat'));
    }

    /**
     * Search the Anime
     *
     */

    public function searchAnime(Request $request, $animeName)
    {
        $animeIDs = \App\AnimeTrans::where('trans_class', 'anime_title')
            ->where('trans_name', 'like', '%'.$animeName.'%')
            ->get(array('trans_name_id'))
            ->toArray();

        $res = [];

        foreach ( $animeIDs as $animeID ) {
            $res[$animeID['trans_name_id']] = $animeID['trans_name_id'];
        }

        $animeIDs = $res;

        if ( count($animeIDs) == 1 ) {
            return $this->show(current($animeIDs));
        } else {
            $animes = [];

            foreach ( $animeIDs as $animeID ) {
                $anime_db = \App\AnimeTrans::where('trans_class', 'anime_title')
                    ->where('trans_default', true)
                    ->where('trans_name_id', $animeID )
                    ->get(array(
                        'trans_name_id',
                        'trans_name',
                        'trans_language'
                    ))
                    ->toArray();

                $animes[] = $anime_db;
            }

           return \Response::json($animes);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int                      $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
