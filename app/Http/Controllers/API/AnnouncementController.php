<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

use App\Models\TextBlast;
use App\Models\Announcement;

use Carbon\Carbon;

class AnnouncementController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | ALL ANNOUNCEMENTS
    |--------------------------------------------------------------------------
    */
    public function announcements ()
    {
        $announcements  =   Announcement::whereIn('audience', ['global', 'student'])
                                ->whereDate('start', '<=', Carbon::today())
                                ->whereDate('end', '>=', Carbon::today())
                                ->orderBy('start', 'ASC')
                                ->paginate(5);
        
        return response()->json($announcements); 
    	// return response()->json(AnnouncementController::getAnnouncement(5));
	}

    /*
    |--------------------------------------------------------------------------
    | SINGLE ANNOUNCEMENT
    |--------------------------------------------------------------------------
    */
    public function announcement($announcement_id)
    {
        $response   = [
            'status'    => null,
            'data'      => null,
            'message'   => null
        ];
        
        $user               =  request()->user();
        $notification_id    =  request()->notification_id;
        $announcement       =  Announcement::where('id', $announcement_id)->whereIn('audience', ['global', 'student'])->first();

        if(! $announcement) {
            $response['status']  = 'error';
            $response['message'] = 'Announcement Not Found';
            return response()->json($response);
        }

        $response['status']   = 'success';
        $response['message']  = 'Announcement has been fetched successfully.';
        $response['data']     = $announcement;

        /* Mark User Notification As Read */
        if($notification_id) {

            $notification = $user->unreadNotifications()->where('id', $notification_id)->first();

            if($notification) {
                $notification->read_at = now();
                $notification->save();
            }
        }

        return response()->json($response);
    }
    	
    public static function getAnnouncement($perpage)
    {
        $res = TextBlast::select('title','message','created_at')
        ->where('blast_type', 'like', '%student%')
        ->orderBy('id', 'desc')->simplePaginate($perpage);        

        return ['announcements' => $res, 'message' => 'none'];
    }
}
