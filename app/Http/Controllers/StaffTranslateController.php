<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class StaffTranslateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all()['data'];

        $res = array();

        foreach($data as $item) {

            // 岗位名称(原)
            $staffNameOri = $item[0];

            // 岗位ID
            $staffNameID = \App\AnimeTrans::where('trans_class', 'work_post')->where('trans_name', $staffNameOri)->first()['trans_name_id'];

            // 岗位名称(中)
            $staffNameZhCN = \App\AnimeTrans::where('trans_name_id', $staffNameID)->where('trans_language', 'zh-cn')->first()['trans_name'];

            // 人员名称
            $staffMemberName = $item[1];

            // 人员 ID
            $staffMemberID = \App\AnimeTrans
                ::where('trans_class', 'people')
                ->orWhere('trans_class', 'company')
                ->where('trans_name', $staffMemberName)
                ->first()['trans_name_id'];

            // TODO: 所属公司 ID

            // TODO: 所属公司名称

            $staff = [
                'staffNameID'     => $staffNameID,       // 岗位 ID
                'staffMemberID'   => $staffMemberID,     // 人员 ID
                'staffNameOri'    => $staffNameOri,      // 岗位名称(原)
                'staffNameZhCN'   => $staffNameZhCN,     // 岗位名称(中)
                'staffMemberName' => $staffMemberName,   // 人员名称
                'isImportant'     => true
            ];
            $res[] = $staff;
        }

        return \Response::json($res);
    }
}
