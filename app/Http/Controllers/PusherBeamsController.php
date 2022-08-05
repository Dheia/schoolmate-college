<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Beam;

use Carbon\Carbon;

class PusherBeamsController extends Controller
{
    use \App\Http\Traits\PusherBeamsTrait;

    /*
    |--------------------------------------------------------------------------
    | GET ADMIN PUSHER DATA
    |--------------------------------------------------------------------------
    */
    public function getAdminPusherData()
    {
        $response = [
            'instance_id'   => env('BEAMS_INSTANCE_ID'),
            'user_beams_id' => 'employee-' . backpack_user()->employee->employee_id,
            'device_interests' => [
                'debug-employee', 
                'debug-global'
            ]
        ];

        return response()->json($response);
    }

    /*
    |--------------------------------------------------------------------------
    | GENERATE ADMIN TOKEN
    |--------------------------------------------------------------------------
    */
    public function generateAdminToken(Request $request)
    {
        $user           = backpack_user();
        // $beams_user_id  = env('SCHOOL_ID') . '.App.User.' . $user->id;
        $beams_user_id  = $request->user_id ?? env('SCHOOL_ID') . '.App.User.' . $user->id;

        // Get User Beam
        $beamsUser  = Beam::where('user_id', $beams_user_id)->first();

        /**
         * IF USER NOT FOUND
         */
        if(! $beamsUser) {

            // Get Beams Token
            $beamsToken = $this->getBeamsToken($beams_user_id);
            $token_id   = $beamsToken["token"];

            $beam = Beam::create([
                'user_id'  =>  $beams_user_id,
                'token_id' =>  $token_id
            ]);

            return response()->json($beamsToken);
        }
        
        /**
         * TOKEN NOT EXPIRED
         * TOKEN IS NOT MORE THAN 24 HRS
         */
        if( Carbon::parse($beamsUser->created_at)->addDays(1) > Carbon::now() ) {
            return response()->json(["token" => $beamsUser->token_id]);
        }

        /**
         * TOKEN EXPIRED
         * TOKEN IS MORE THAN 24 HRS
         */

        // Get Beams Token
        $beamsToken = $this->getBeamsToken($beams_user_id);
        $token_id   = $beamsToken["token"];

        $beamsUser->token_id = $token_id;
        $beamsUser->save();

        return response()->json($beamsToken);
    }

    /*
    |--------------------------------------------------------------------------
    | GENERATE STUDENT TOKEN
    |--------------------------------------------------------------------------
    */
    public function generateStudentToken(Request $request)
    {
        $user           = request()->user();
        $beams_user_id  = $request->user_id ?? env('SCHOOL_ID') . '.App.StudentCredential.' . $user->id;

        // Get User Beam
        $beamsUser  = Beam::where('user_id', $beams_user_id)->first();

        /**
         * IF USER NOT FOUND
         */
        if(! $beamsUser) {

            // Get Beams Token
            $beamsToken = $this->getBeamsToken($beams_user_id);
            $token_id   = $beamsToken["token"];

            $beam = Beam::create([
                'user_id'  =>  $beams_user_id,
                'token_id' =>  $token_id
            ]);

            return response()->json($beamsToken);
        }
        
        /**
         * TOKEN NOT EXPIRED
         * TOKEN IS NOT MORE THAN 24 HRS
         */
        if( Carbon::parse($beamsUser->created_at)->addDays(1) > Carbon::now() ) {
            return response()->json(["token" => $beamsUser->token_id]);
        }

        /**
         * TOKEN EXPIRED
         * TOKEN IS MORE THAN 24 HRS
         */

        // Get Beams Token
        $beamsToken = $this->getBeamsToken($beams_user_id);
        $token_id   = $beamsToken["token"];

        $beamsUser->token_id = $token_id;
        $beamsUser->save();

        return response()->json($beamsToken);
    }
}
