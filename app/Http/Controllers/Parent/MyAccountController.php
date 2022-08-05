<?php

namespace App\Http\Controllers\Parent;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Http\Requests\ParentChangePasswordRequest;
use Illuminate\Support\Facades\Hash;
use App\Models\Requirement;
use App\Models\Student;
use App\Models\Announcement;

use Auth;
use Alert;
use Carbon\Carbon;

class MyAccountController extends Controller
{

    private $parent;
    private $user;

    /*
    |--------------------------------------------------------------------------
    | Show the student informations
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        $parent = auth()->user()->parent;
        $title = "Dashboard";
        return view('parentPortal.my_account',compact(['parent', 'title']));
    }

    /*
    |--------------------------------------------------------------------------
    | Show the student a form to change his login password.
    |--------------------------------------------------------------------------
    */
    public function getChangePasswordForm()
    {
        $this->data['title'] = 'My Account';
        $this->data['parent']  = auth()->user()->parent;

        return view('parentPortal.change_password', $this->data);
    }

    /*
    |--------------------------------------------------------------------------
    | Save the new password for a student.
    |--------------------------------------------------------------------------
    */
    public function postsChangePasswordForm(ParentChangePasswordRequest $request)
    {
        $user = auth()->user();
        $user->password = Hash::make($request->new_password);
        $user->is_first_time_login = 0;

        if ($user->save()) {
            Alert::success(trans('backpack::base.account_updated'))->flash();
            return redirect()->route('parent.dashboard');
        } else {
            Alert::error(trans('backpack::base.error_saving'))->flash();
        }

        return redirect()->back();
    }

    /*
    |--------------------------------------------------------------------------
    | Show Announcement
    |--------------------------------------------------------------------------
    */
    public function showAnnouncement($id)
    {
        $user            = auth()->user();
        $announcement    = Announcement::findOrFail($id);
        $notification_id = request()->notification_id;

        if($announcement->audience != 'global' && $announcement->audience != 'parent') {
            abort(403);
        }

        if($notification_id) {
            $notification = $user->unreadNotifications()->where('id', $notification_id)->first();
            if($notification) {
                $notification->read_at = now();
                $notification->save();
            }
        }
        return view('parentPortal.announcement.show', compact('announcement'));
    }

    /*
    |--------------------------------------------------------------------------
    | Get All Announcement
    |--------------------------------------------------------------------------
    */
    public function getAnnouncements()
    {
        $user           =   auth()->user();
        $announcements  =   Announcement::whereIn('audience', ['global', 'parent'])
                                ->whereDate('start', '<=', Carbon::today())
                                ->whereDate('end', '>=', Carbon::today())
                                ->orderBy('start', 'ASC')
                                ->paginate(10);

        return view('parentPortal.announcement.dashboard', compact('announcements'));
    }

    /*
    |--------------------------------------------------------------------------
    | Get Unread Notifications
    |--------------------------------------------------------------------------
    */
    public function unreadNotifications()
    {
        $user           = auth()->user();
        $notifications  = $user->unreadNotifications()->paginate(10);

        return response()->json($notifications);
    }

}
