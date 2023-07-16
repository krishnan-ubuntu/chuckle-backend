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

    /**
     * Api to create chuckle
     * 
     * @author krishnan <krishnan.ubuntu@gmail.com>
     * 
     * @param string new_chuckle
     * 
     * @return json
     * 
     */
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


    /**
     * Api to get all recent chuckles
     * 
     * @author krishnan <krishnan.ubuntu@gmail.com>
     * 
     * @return json
     * 
     */
    public function get_chuckles(Request $request) 
    {
        try {
            $sql_query = "SELECT chuckles.id AS chuckle_id, chuckles.chuckle, 
            users.fname user_fname, users.lname user_lname
            FROM chuckles 
            JOIN users ON chuckles.user_id = users.id 
            ORDER BY chuckles.id DESC LIMIT 100";
            $recent_chuckles = DB::select($sql_query);
            if($recent_chuckles) {
                return response()->json(["success" => true, "data" => $recent_chuckles], 200);
            }
            else {
                return response()->json(["success" => false,  'message' => 'No chuckles available'], 400);
            }

        } catch (\Throwable $th) {
            $message = $th->getMessage();
            return response()->json(["success" => false,  'message' => $message], 400);

        }
    }



    /**
     * Api to get recent user chuckles
     * 
     * @author krishnan <krishnan.ubuntu@gmail.com>
     * 
     * @return json
     * 
     */
    public function get_user_chuckles(Request $request) 
    {
        try {
            //$user_id = $request->get('user_id');
            $user_id = 1; //For now keeping it static. Will build this feature later
            $sql_query = "SELECT chuckles.id AS chuckle_id, chuckles.chuckle, 
            users.fname user_fname, users.lname user_lname
            FROM chuckles 
            JOIN users ON chuckles.user_id = users.id 
            WHERE chuckles.user_id = ".$user_id."
            ORDER BY chuckles.id DESC LIMIT 100";
            $recent_chuckles = DB::select($sql_query);
            if($recent_chuckles) {
                return response()->json(["success" => true, "data" => $recent_chuckles], 200);
            }
            else {
                return response()->json(["success" => false,  'message' => 'No chuckles available'], 400);
            }

        } catch (\Throwable $th) {
            $message = $th->getMessage();
            return response()->json(["success" => false,  'message' => $message], 400);

        }
    }

}
