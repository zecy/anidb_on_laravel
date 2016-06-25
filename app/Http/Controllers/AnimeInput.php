<?php

namespace App\Http\Controllers;

use App\AnimeBasicData;
use App\AnimeLinks;
use App\AnimeTitles;
use App\AnimeOriginalWork;
use App\AnimeOriginalWorkSupport;
use App\ClassSupport;
use App\AnimeStaff;
use App\AnimeCast;
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
        $transLangs = ClassSupport::where('class', '=', 'language')->get(array('content', 'comment'));
        $links = ClassSupport::where('class', '=', 'links')->get(array('content', 'comment'));
        $premiereMedia = ClassSupport::where('class', '=', 'premiere_media')->get(array('content', 'comment'));
        $animeDurationFormat = ClassSupport::where('class', '=', 'anime_duration_format')->get(array('content', 'comment'));
        $oriWorks = AnimeOriginalWorkSupport::all()->toJson();


        return view('input.input', compact('basicData', 'transLangs', 'links', 'premiereMedia', 'oriWorks', 'animeDurationFormat'));
    }

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

        \DB::transaction(function () use ($data) {
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
                $title = AnimeTitles::create(
                    [
                        'anime_id'    => $ID,
                        'title'       => $theTitle['value'],
                        'lang'        => $theTitle['lang'],
                        'description' => $theTitle['comment'],
                        'is_official' => $theTitle['isOfficial'],
                        'order_index' => $theTitle['orderIndex']
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

        return \Response::json();
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
        $animeBasicData = AnimeBasicData::where('anime_id', $id)->get()->toArray()[0];
        $animeLinks = AnimeLinks::where('anime_id', $id)->get()->toArray();
        $animeTitles = AnimeTitles::where('anime_id', $id)
            ->get(array(
                'id',
                'title',
                'anime_id',
                'lang',
                'is_official',
                'description',
                'order_index'
            ))->toArray();
        $animeOriWorks = AnimeOriginalWork::where('anime_id', $id)->get()->toArray();

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
                'id'         => $title['id'],
                'lang'       => $title['lang'],
                'isOfficial' => $title['is_official'],
                'value'      => $title['title'],
                'comment'    => $title['description'],
                'orderIndex' => $title['order_index']
            ];
        }

        foreach ($animeLinks as $link ) {
            $basicData['links'][] = [
                'id'         => $link['link_id'],
                'class'      => $link['link_class'],
                'isOfficial' => $link['link_is_official'],
                'value'      => $link['link_url'],
                'comment'    => $link['link_comment'],
                'orderIndex' => $title['order_index']
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

        //TODO: 置空处理

        $staffs = AnimeStaff::where('staff_anime_id', $id)
            ->orderBy('order_index', 'asc')
            ->get(array(
                'staff_id',
                'staff_important',
                'staff_post_zh',
                'staff_post_ori',
                'staff_belong',
                'staff_member',
                'order_index'
            ))->toArray();

        $staffMembers = [];

        foreach ( $staffs as $staff ) {
            $staffMembers[] = [
                'animeID'            => $id,
                'id'                 => $staff['staff_id'],
                'staffPostOri'       => $staff['staff_post_ori'],
                'staffPostZhCN'      => $staff['staff_post_zh'],
                'staffMemberName'    => $staff['staff_member'],
                'staffBelongsToName' => $staff['staff_belong'],
                'isImportant'        => $staff['staff_important'],
                'orderIndex'         => $staff['order_index']
            ];
        }

        $casts = AnimeCast::where('cast_anime_id', $id)
            ->orderBy('order_index', 'asc')
            ->get(array(
                'cast_id',
                'cast_anime_id',
                'charaNameOri',
                'cvNameOri',
                'cast_important',
                'order_index'
            ))->toArray();

        $castMembers = [];

        foreach ( $casts as $cast ) {
            $castMembers[] = [
                'animeID'      => $id,
                'id'           => $cast['cast_id'],
                'charaNameOri' => $cast['charaNameOri'],
                'cvNameOri'    => $cast['cvNameOri'],
                'isImportant'  => $cast['cast_important']
            ];
        }

        $onairData = \App\AnimeOnair::where('anime_id', $id)
            ->get(array(
                'oa_id',
                'anime_id',
                'oa_start_date',
                'oa_end_date',
                'oa_start_time',
                'oa_end_time',
                'oa_weekday',
                'oa_tv_column',
                'oa_description',
                'tv_name',
                'is_production'
            ))->toArray();

        $onairs = [];

        foreach ( $onairData as $onair ) {
            $onairs[] = [
                'id'           => $onair['oa_id'],
                'animeID'      => $onair['anime_id'],
                'tvName'       => $onair['tv_name'],
                'startDate'    => $onair['oa_start_date'],
                'endDate'      => $onair['oa_end_date'],
                'startTime'    => $onair['oa_start_time'],
                'endTime'      => $onair['oa_end_time'],
                'weekday'      => $onair['oa_weekday'],
                'tvColumn'     => $onair['oa_tv_column'],
                'description'  => $onair['oa_description'],
                'isProduction' => $onair['is_production']
            ];
        }

        return \Response::json([
            'multiple'     => 0,
            'basicData'    => $basicData,
            'staffMembers' => $staffMembers,
            'castMembers'  => $castMembers,
            'onairs'       => $onairs
        ]);

    }

   /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Basic Data
        $data = $request->all()['data'];

        try {
            \DB::transaction(function () use ($data, $id) {

                // BasicData
                $basicData = AnimeBasicData::find($id);

                $basicData->anime_series_id          = $data['seriesID']['value'];
                $basicData->anime_abbr               = $data['abbr']['value'];
                $basicData->anime_kur                = $data['kur']['value'];
                $basicData->anime_premiere_media     = $data['premiereMedia']['value'];
                $basicData->anime_sequel             = $data['isSequel']['value'];
                $basicData->anime_duration_format    = $data['duration']['value'];
                $basicData->anime_end                = $data['isEnd']['value'];
                $basicData->anime_description        = $data['description']['value'];
                $basicData->anime_counted            = $data['isCounted']['value'];

                $basicData->save();

                // Titles
                $Titles = $data['title'];

                foreach($Titles as $title) {
                    $titleID = $title['id'];
                    if ( $titleID != 0 ) {
                        $theTitle = AnimeTitles::find($titleID);

                        $theTitle->title         = $title['value'];
                        $theTitle->lang          = $title['lang'];
                        $theTitle->is_official   = $title['isOfficial'];
                        $theTitle->description   = $title['comment'];
                        $theTitle->order_index   = $title['orderIndex'];

                        $theTitle->save();
                    } else {
                        $theTitle = AnimeTitles::create(
                            [
                                'id'          => $id,
                                'title'       => $title['value'],
                                'lang'        => $title['lang'],
                                'description' => $title['comment'],
                                'is_official' => $title['isOfficial'],
                                'order_index' => $title['orderIndex']
                            ]
                        );
                    }
                }

                // Links
                $Links = $data['links'];
                foreach ( $Links as $link ) {
                    $linkID = $link['id'];

                    if ( $linkID != 0 ) {
                        $theLink = AnimeLinks::find($linkID);

                        $theLink->link_class        = $link['class'];
                        $theLink->link_comment      = $link['comment'];
                        $theLink->link_url          = $link['value'];
                        $theLink->link_is_official  = $link['isOfficial'];
                        $theLink->order_index       = $link['orderIndex'];

                        $theLink->save();
                    } else {
                        $theLink = AnimeLinks::create([
                            'link_class'       => $link['class'],
                            'anime_id'         => $id,
                            'link_comment'     => $link['comment'],
                            'link_url'         => $link['value'],
                            'link_is_official' => $link['isOfficial'],
                            'order_index'      => $link['orderIndex']
                        ]);
                    }
                }

                // Original Works

                // 因为不同的原作信息记录的条数不一样, 所以索性全部修改删除重新输入
                $deletOriWorks = AnimeOriginalWork::where('anime_id', $id)->delete();

                $i = 0;
                foreach ($data['oriWorks'] as $lv) {
                    foreach ( $lv as $origenres ) {
                        if($origenres['id'] != '' && $origenres['id'] != 0) {
                            $origenres = AnimeOriginalWork::create(
                                [
                                    'anime_id' => $id,
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

            });

            \DB::commit();

            return $this->show($id);
        }
        catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Search the Anime
     *
     */

    public function searchAnime(Request $request, $animeName)
    {
        $animeIDs = \App\AnimeTitles::where('title', 'like', '%'.$animeName.'%')
            ->get(array('anime_id'))
            ->toArray();

        $res = [];

        foreach ( $animeIDs as $animeID ) {
            $res[$animeID['anime_id']] = $animeID['anime_id'];
        }

        $animeIDs = $res;

        if ( count($animeIDs) == 1 ) {
            return $this->show(current($animeIDs));
        } else {
            $animes = [];

            foreach ( $animeIDs as $animeID ) {
                $anime_db = \App\AnimeTitles::where('is_official', true)
                    ->where('anime_id', $animeID )
                    ->get(array(
                        'anime_id',
                        'title',
                        'lang'
                    ))
                    ->toArray();

                $animes[] = $anime_db;
            }

           return \Response::json([
               'multiple' => 1,
               'animes'   => $animes
           ]);
        }
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
