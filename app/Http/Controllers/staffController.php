<?php

namespace App\Http\Controllers;

use App\AnimeStaff;
use App\Http\Controllers\AnimeInput;
use App\StaffTranslate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Http\Requests;

class staffController extends Controller
{

    private function createStaff($arr, $pid)
    {
        $res = AnimeStaff::create(
            [
                'staff_anime_id'  => $arr['animeID'],
                'staff_important' => $arr['isImportant'],
                'staff_post_zh'   => $arr['staffPostZhCN'],
                'staff_post_ori'  => $arr['staffPostOri'],
                'staff_member'    => $arr['staffMemberName'],
                'staff_belong'    => $arr['staffBelongsToName'],
                'order_index'     => $arr['orderIndex'],
                'staff_main'      => true,
                'haschild'        => $arr['haschild'],
                'pid'             => $pid,
                'lv'              => $arr['lv'],
            ]
        );
        return $res;
    }

    private function updateStaff($staffs) {
        foreach($staffs as $staff) {
            $staffID = $staff['id'];
            if ( $staffID != 0 ) {
                $theStaff = AnimeStaff::find($staffID);

                $theStaff->staff_important = $staff['isImportant'];
                $theStaff->staff_post_zh   = $staff['staffPostZhCN'];
                $theStaff->staff_post_ori  = $staff['staffPostOri'];
                $theStaff->staff_member    = $staff['staffMemberName'];
                $theStaff->staff_belong    = $staff['staffBelongsToName'];
                $theStaff->order_index     = $staff['orderIndex'];
                $theStaff->lv              = $staff['lv'];
                $theStaff->pid             = $staff['pid'];
                $theStaff->haschild        = $staff['haschild'];

                $theStaff->save();

                if ( $staff['haschild'] && !empty($staff['child']) ) {
                    $this->updateStaff($staff['child']);
                }
            } else {
                $theStaff = $this->createStaff($staff, $staff['pid']);
                if ( $staff['haschild'] && !empty($staff['child']) ) {
                    $ID = $theStaff->staff_id;
                    foreach ($staff['child'] as $staffChild ) {
                        $this->createStaff($staffChild, $ID);
                    }
                }
            }
        }
    }

    public function staffTrans(Request $request) {
        $staffItems = $request->all()['data'];

        $res = [];

        foreach ($staffItems as $staffItem) {

            $trans = StaffTranslate::where('ori', $staffItem[0])
                ->first(array(
                    'trans',
                    'is_important'
                ));

            $hasTrans = empty($trans);

            $res[] = [
                'ori'         => $staffItem[0],
                'zhcn'        => $hasTrans ? '' : $trans['trans'],
                'name'        => $staffItem[1],
                'isImportant' => $hasTrans ? false : $trans['is_important']
            ];
        }

        return \Response::json($res);
    }

    public function getStaffDB ($animeID, $pid) {
        $staffs = AnimeStaff::where('staff_anime_id', $animeID)
            ->where('pid', $pid)
            ->orderBy('order_index', 'asc')
            ->get(array(
                'staff_id',
                'staff_important',
                'staff_post_zh',
                'staff_post_ori',
                'staff_belong',
                'staff_member',
                'order_index',
                'lv',
                'pid',
                'haschild'
            ))->toArray();

        return $staffs;
    }

    public function staffItem ($staff, $animeID)
    {
        $staffItem = [
            'animeID'            => $animeID,
            'id'                 => $staff['staff_id'],
            'staffPostOri'       => $staff['staff_post_ori'],
            'staffPostZhCN'      => $staff['staff_post_zh'],
            'staffMemberName'    => $staff['staff_member'],
            'staffBelongsToName' => $staff['staff_belong'],
            'isImportant'        => $staff['staff_important'],
            'orderIndex'         => $staff['order_index'],
            'haschild'           => $staff['haschild'],
            'pid'                => $staff['pid'],
            'lv'                 => $staff['lv'],
            'child'              => []
        ];

        return $staffItem;
    }

    public function store(Request $request)
    {

        $staffMembers = $request->all()['data'];

        \DB::transaction(function () use ($staffMembers) {
            foreach ($staffMembers as $staffMember) {

                $theStaff = $this->createStaff($staffMember, 0);

                $staffID = $theStaff->staff_id;

                if ($staffMember['haschild'] && !empty($staffMember['child'])) {
                    foreach ($staffMember['child'] as $staffChild ) {
                        $this->createStaff($staffChild, $staffID);
                    }
                }
            }
        });

        \DB::commit();

        return \Response::json(['status' => '200']);
    }

    public function update(Request $request, $id) {

        $staffs = $request->all()['data'];

        try {
            \DB::transaction(function () use ($staffs) {
                $this->updateStaff($staffs);
            });

            \DB::commit();

            return \Response::json(['POS' => 'STAFF', 'animeID' => $id]);

        }
        catch (\Exception $e) {
            throw $e;
        }
    }

    public function destroy($id)
    {
        $ID = $id;

        try {
            $staff = AnimeStaff::find($ID);
            $staff->delete();
            return \Response::json();
        }
        catch(\Exception $e) {
            throw $e;
        }
    }
}
