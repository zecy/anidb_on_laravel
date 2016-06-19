<?php

namespace App\Http\Controllers;

use App\AnimeStaff;
use App\Http\Controllers\AnimeInput;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Http\Requests;

class staffController extends Controller
{
    public function store(Request $request)
    {

        $staffMembers = $request->all()['data'];

        \DB::transaction(function () use ($staffMembers) {
            foreach ($staffMembers as $staffMember) {
                $staff = AnimeStaff::create(
                    [
                        'staff_anime_id'  => $staffMember['animeID'],
                        'staff_important' => $staffMember['isImportant'],
                        'staff_post_zh'   => $staffMember['staffPostZhCN'],
                        'staff_post_ori'   => $staffMember['staffPostOri'],
                        'staff_member'    => $staffMember['staffMemberName'],
                        'staff_belong'    => $staffMember['staffBelongsToName'],
                        'staff_main'      => true
                    ]
                );
            }
        });

        \DB::commit();

        return \Response::json(['status' => '200']);
    }

    public function update(Request $request, $id) {

        $staffs = $request->all()['data'];

        $ID = $id;

        try {
            \DB::transaction(function () use ($staffs) {
                foreach($staffs as $staff) {
                    $staffID = $staff['id'];
                    if ( $staffID != 0 ) {
                        $theStaff = AnimeStaff::find($staffID);

                        $theStaff->staff_important = $staff['isImportant'];
                        $theStaff->staff_post_zh   = $staff['staffPostZhCN'];
                        $theStaff->staff_post_ori  = $staff['staffPostOri'];
                        $theStaff->staff_member    = $staff['staffMemberName'];
                        $theStaff->staff_belong    = $staff['staffBelongsToName'];

                        $theStaff->save();
                    } else {
                        $theStaff = AnimeStaff::create(
                            [
                                'staff_anime_id'  => $staff['animeID'],
                                'staff_important' => $staff['isImportant'],
                                'staff_post_zh'   => $staff['staffPostZhCN'],
                                'staff_post_ori'  => $staff['staffPostOri'],
                                'staff_member'    => $staff['staffMemberName'],
                                'staff_belong'    => $staff['staffBelongsToName'],
                                'staff_main'      => true
                            ]
                        );
                    }
                }
            });

            \DB::commit();

            return \Response::json(['POS' => 'STAFF', 'animeID' => $ID]);

        }
        catch (\Exception $e) {
            throw $e;
        }
    }
}
