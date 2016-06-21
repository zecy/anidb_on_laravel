<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class titleController extends Controller
{
    public function destroy($id)
    {
        $ID = $id;

        try {
            $staff = AnimeTrans::find($ID);
            $staff->delete();
            return \Response::json();
        }
        catch(\Exception $e) {
            throw $e;
        }
    }
}
