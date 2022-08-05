<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;

use App\Http\Controllers\OnlinePaymentController as PaymentController;

use Illuminate\Http\Request;
use App\Http\Requests\StudentChangePasswordRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\Requirement;
use App\Models\Student;
use App\Models\Enrollment;
use App\Models\PaymentMethod;
use App\Models\Goal;

use App\Models\Announcement;

use Auth;
use Alert;
use Carbon\Carbon;

class MyAccountController extends Controller
{

    private $student;
    private $user;
    private $student_classes;

    /**
     * Show the student informations
     */
    public function index()
    {
        $this->student       =  $student    =   auth()->user()->student;
        $studentRequirements =  Requirement::where('student_id',  auth()->user()->student->id)->first();
        if(!$studentRequirements)
        {
            $requirement = new Requirement;
            $requirement->student_id = auth()->user()->student->id;
            $requirement->save();
        }
        $studentRequirements = Requirement::where('student_id',  auth()->user()->student->id)->first();
        $title = "Dashboard";

        $enrollments            =   config('settings.viewstudentaccount') 
                                        ? Enrollment::where('studentnumber', $this->student->studentnumber)
                                            ->with('schoolyear')
                                            ->with('department')
                                            ->with('level')
                                            ->with('track')
                                            ->with('tuition')
                                            ->with('commitmentPayment')
                                            ->with(['studentSectionAssignment' => function ($query) {
                                                $query->where('students', 'like', '%' . $this->student->studentnumber . '%');
                                                $query->with(['section' => function ($q) {
                                                    $q->with('level');
                                                }]);
                                            }])
                                            ->where('is_applicant', 0)
                                            ->orderBy('created_at', 'ASC')
                                            ->get()

                                        : collect([]);

        $paymentController = new PaymentController();
        
        $data = [
            'enrollments'    => $enrollments,
            'fee'            => $paymentController->getFee(),
            'fixedAmount'    => $paymentController->getFixedAmount(),
            'paymentMethods' => PaymentMethod::orderBy('name', 'ASC')->where('code', '!=', null)->get()
        ];

        // return view('studentPortal.my_account',compact(['student', 'title', 'studentRequirements']));
        return view('studentPortal.my_account_new',compact(['student', 'title', 'studentRequirements']))->with($data);
    }

    /**
     * Show the student a form to change his login password.
     */
    public function getChangePasswordForm()
    {
        $this->data['title'] = 'My Account';
        $this->data['student']  = auth()->user()->student;

        return view('studentPortal.change_password', $this->data);
    }

    /**
     * Save the new password for a student.
     */
    public function postsChangePasswordForm(StudentChangePasswordRequest $request)
    {
        $user = auth()->user();
        $user->password = Hash::make($request->new_password);
        $user->is_first_time_login = 0;

        if ($user->save()) {
            Alert::success(trans('backpack::base.account_updated'))->flash();
        } else {
            Alert::error(trans('backpack::base.error_saving'))->flash();
        }

        return redirect()->route('student.dashboard');
    }

    /**
     * Store Student Goal
     */
    public function storeGoal(Request $request)
    {
        $this->student  = $student = auth()->user()->student;
        $response = [
            'status'  => 'warning',
            'title'   => 'Oops...',
            'message' => null,
            'data'    => null
        ];

        $validator = Validator::make($request->all(), [
            'message' => 'required',
        ]);

        if ($validator->fails()) {
            $response['message'] = $validator->errors()->first('message');
            return $response;
        }

        try {
            $studentGoal =  Goal::create([
                                'user_id'   => $student->id,
                                'user_type' => 'App\Models\Student',
                                'content'   =>  $request->message
                            ]);

            $response   =   [
                'status'  => 'success',
                'title'   => 'Success',
                'message' => 'Your goal has been set.',
                'data'    => $studentGoal
            ];
        } catch (Exception $e) {
            $response['status']  = 'error';
            $response['message'] = 'Something went wrong.';
        }

        return $response;
    }

    /**
     * Delete Student Goal
     */
    public function deleteGoal(Request $request)
    {
        $this->student  = $student = auth()->user()->student;

        try {
            $studentGoals   =   Goal::where('user_type', 'App\Models\Student')
                                    ->where('user_id', $student->id)
                                    ->whereIn('id', $request->ids)
                                    ->get();

            $deleteGoals    =   Goal::where('user_type', 'App\Models\Student')
                                    ->where('user_id', $student->id)
                                    ->whereIn('id', $request->ids)
                                    ->delete();

            $response   =   [
                'status'  => 'success',
                'title'   => 'Goal Deleted',
                'message' => 'The goal has been deleted successfully.',
                'data'    => $studentGoals->pluck('id')
            ];
        } catch (Exception $e) {
            $response = [
                'status'  => 'error',
                'title'   => 'Oops...',
                'message' => 'Something went wrong',
                'data'    => []
            ];
        }

        return $response;
    }

    /**
     * Set Student Goal
     * To Done
     */
    public function doneGoal(Request $request)
    {
        $this->student  = $student = auth()->user()->student;

        try {
            $studentGoals   =   Goal::where('user_type', 'App\Models\Student')
                                    ->where('user_id', $student->id)
                                    ->whereIn('id', $request->ids)
                                    ->update(['done'   => 1]);

            $studentGoals   =   Goal::where('user_type', 'App\Models\Student')
                                    ->where('user_id', $student->id)
                                    ->whereIn('id', $request->ids)
                                    ->get();

            $response   =   [
                'status'  => 'success',
                'title'   => 'Success',
                'message' => 'Your goals has been updated.',
                'data'    => $studentGoals
            ];
        } catch (Exception $e) {
             $response       = [
                'status'  => 'error',
                'title'   => 'Oops...',
                'message' => 'Something went wrong',
                'data'    => []
            ];
        }

        return $response;
    }

    /**
     * Show Announcement
     */
    public function showAnnouncement($id)
    {
        $user            = auth()->user();
        $announcement    = Announcement::findOrFail($id);
        $notification_id = request()->notification_id;

        if($announcement->audience != 'global' && $announcement->audience != 'student') {
            abort(403);
        }

        if($notification_id) {
            $notification = $user->unreadNotifications()->where('id', $notification_id)->first();

            if($notification) {
                $notification->read_at = now();
                $notification->save();
            }
        }
        return view('studentPortal.announcement.show', compact('announcement'));
    }

    /**
     * Get All Announcement
     */
    public function getAnnouncements()
    {
        $user           =   auth()->user();
        $announcements  =   Announcement::whereIn('audience', ['global', 'student'])
                                ->whereDate('start', '<=', Carbon::today())
                                ->whereDate('end', '>=', Carbon::today())
                                ->orderBy('start', 'ASC')
                                ->get();

        return view('studentPortal.announcement.dashboard', compact('announcements'));
    }

}
