<?php

namespace App\Http\Controllers;

use App\AnimeStaff;
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
}
