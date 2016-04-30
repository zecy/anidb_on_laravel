<?php

namespace App\Http\Controllers;

use App\AnimeBasicData;
use App\AnimeLinks;
use App\AnimeTrans;
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
        //取得数据库内容
        $transLangs = \App\ClassSupport::where('class', '=', 'language')->get(array('content', 'comment'));
        $links = \App\ClassSupport::where('class', '=', 'links')->get(array('content', 'comment'));
        $premiereMedia = \App\ClassSupport::where('class', '=', 'premiere_media')->get(array('content', 'comment'));
        $animeDurationFormat = \App\ClassSupport::where('class', '=', 'anime_duration_format')->get(array('content', 'comment'));
        //$oriGrenres = new TreeData(\App\AnimeOriginalWork::all()->toArray(),'ori_id','ori_pid');
        //$oriWorks = json_encode($oriGrenres->result());
        $oriWorks = \App\AnimeOriginalWork::all()->toJson();
        //dd($oriWorks);
        //数据库内容赋值到 view
        return view('input.input', compact('transLangs', 'links', 'premiereMedia', 'oriWorks', 'animeDurationFormat'));
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

        $res = array();

        \DB::transaction(function () use ($data, &$res) {
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
        //
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
        //
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
