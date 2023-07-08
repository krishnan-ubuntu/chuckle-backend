<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use App\Models\Chuckles;
use Validator;

class ChucklesController extends Controller
{

    public function create(Request $request)
    {
        try {
            //$user_id = $request->get('user_id');
            $user_id = 1; //For now keeping it static. Will build this feature later

            $validated = validator($request->all(), [
                'new_chuckle' => 'bail|required'
            ]);
            if ($validated->fails()) {
                return response()->json(["success" => false, "errors" => $validated->errors()->first()], 400);
            }
            else {
                $new_chuckle = $request->new_chuckle;
            }

            $new_chuckle_data = array(
                'user_id' => $user_id,
                'chuckle' => $new_chuckle,
                'created_on' => Carbon::now()
            );

            $chuckle_created = DB::table('chuckles')->insert($new_chuckle_data);
            if ($chuckle_created) {
                return response()->json(["success" => true, 'message' => 'Chuckle created successfully'], 200);
            }
            else {
                return response()->json(["success" => false,  'message' => 'Chuckle was not created successfully'], 400);
            }
        }
        catch(\Throwable $th) {
            //Bring logging here via Beanstalkd
            $message = $th->getMessage();
            return response()->json(["success" => false,  'message' => $message], 400);
        }
    }
}