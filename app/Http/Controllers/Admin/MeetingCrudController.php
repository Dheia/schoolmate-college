<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\MeetingRequest as StoreRequest;
use App\Http\Requests\MeetingRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;

use App\Models\Meeting;
use App\Models\Student;
use App\Models\Employee;
use App\Models\UsersMeeting;
use App\Models\StudentsMeeting;

use App\Http\Controllers\BBB;
use BigBlueButton\BigBlueButton;
use BigBlueButton\Parameters\GetRecordingsParameters;
use Symfony\Component\HttpFoundation\Request;

// Zoom
use App\Http\Controllers\ZoomMeetingController;
use App\Models\ZoomMeeting;
use App\Models\ZoomUser;

use Carbon\Carbon;

/**
 * Class MeetingCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class MeetingCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Meeting');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/meeting');
        $this->crud->setEntityNameStrings('meeting', 'meetings');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */
        abort_if(!backpack_auth()->user()->employee, 403, 'Your User Account Is Not Yet Tag As Employee');

        // TODO: remove setFromDb() and manually define Fields and Columns
        // $this->crud->setFromDb();

        // add asterisk for fields that are required in MeetingRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');

        $meetings               = self::getMyMeetings();
        $this->data['meetings'] = count($meetings) > 0 ? $meetings->paginate(6) : $meetings;

        $this->crud->setListView('meeting.dashboard');

       
        $this->crud->addField([
            'label' => 'Name',
            'type'  => 'text',
            'name'  => 'name'
        ]);

        $this->crud->addField([
            'label' => 'Description',
            'type'  => 'textarea',
            'name'  => 'description'
        ]);

        $this->crud->addField([   // Date
            'name' => 'date',
            'label' => 'Date',
            'type' => 'date',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],
        ]);

        $this->crud->addField([   // Time
            'name' => 'start_time',
            'label' => 'Start Time',
            'type' => 'time',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],
        ]);

        $this->crud->addField([   // Time
            'name' => 'end_time',
            'label' => 'End Time',
            'type' => 'time',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],
        ]);

        $this->crud->addField([       // Select2Multiple = n-n relationship (with pivot table)
            'label' => "Employees",
            'type' => 'select2_multiple',
            'name' => 'users_meeting', // the method that defines the relationship in your Model
            'entity' => 'users_meeting', // the method that defines the relationship in your Model
            'attribute' => 'full_name', // foreign key attribute that is shown to user
            'model' => "App\Models\Employee", // foreign key model
            'options'   => (function ($query) {
                return $query->where('id', '!=', backpack_auth()->user()->employee->id)->get();
            }),
            'pivot' => true, // on create&update, do you need to add/delete pivot table entries?
            'select_all' => true // show Select All and Clear buttons?
        ]);


        $this->crud->addField([
            'label' => 'Select all teachers',
            'type'  => 'checkbox',
            'name'  => 'select_all_teachers',
            'wrapperAttributes' => [
                'id' => 'select_all_teachers',
                'class' => 'form-group col-md-12'
            ],
        ]);

        $this->crud->addField([       // Select2Multiple = n-n relationship (with pivot table)
            'label' => "Students",
            'type' => 'select2_multiple',
            'name' => 'students_meeting', // the method that defines the relationship in your Model
            'entity' => 'students_meeting', // the method that defines the relationship in your Model
            'attribute' => 'fullname', // foreign key attribute that is shown to user
            'model' => "App\Models\Student", // foreign key model
            'pivot' => true, // on create&update, do you need to add/delete pivot table entries?
            'select_all' => true, // show Select All and Clear buttons?
            'options'   => (function ($query) {
                return $query->orderBy('firstname', 'ASC')->get();
            }),
            "allows_multiple" => true
        ]);

        // $this->crud->addField([
        //     // n-n relationship
        //     'label' => "Students", // Table column heading
        //     'type' => "select2_from_ajax_multiple",
        //     'name' => 'students_meeting', // the column that contains the ID of that connected entity
        //     'entity' => 'students_meeting', // the method that defines the relationship in your Model
        //     'attribute' => "fullname", // foreign key attribute that is shown to user
        //     'model' => "App\Models\Student", // foreign key model
        //     'data_source' => url("admin/meeting/api/students"), // url to controller search function (with /{id} should return model)
        //     'placeholder' => "Select a student", // placeholder for the select
        //     'minimum_input_length' => 0,// minimum characters to type before querying results,
        //     'maximumSelectionLength' => 2000,
        //     'pivot' => true, // on create&update, do you need to add/delete pivot table entries?
        //     'select_all' => true
        // ]);

        $this->crud->addField([
            'label' => 'Select all students',
            'type'  => 'checkbox',
            'name'  => 'select_all_students',
            'wrapperAttributes' => [
                'id' => 'select_all_students',
                'class' => 'form-group col-md-12'
            ],
        ]);

        $this->crud->addField([   // color_picker
            'label' => 'Background Color',
            'name' => 'color',
            'type' => 'color_picker',
            'color_picker_options' => [
                'customClass' => 'custom-class'
            ],
            'wrapperAttributes' => [
                'class' => 'form-group col-md-3'
            ],
            'default' => '#3c8dbc'
        ]);
    }

    public function showArchiveMeetings()
    {
        $this->data['crud'] = $this->crud;

        $meetings               = self::getMyArchiveMeetings();
        $this->data['meetings'] = count($meetings) > 0 ? $meetings->paginate(6) : $meetings;

        return view('meeting.archive', $this->data);
    }

     /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $this->crud->hasAccessOrFail('update');
        $this->crud->setOperation('update');

        // get entry ID from Request (makes sure its the last ID for nested resources)
        $id = $this->crud->getCurrentEntryId() ?? $id;

        // get the info for that entry
        $this->data['entry'] = $this->crud->getEntry($id);

        abort_if($this->data['entry']->archive, 403);
        $this->data['crud'] = $this->crud;
        $this->data['saveAction'] = $this->getSaveAction();
        $this->data['fields'] = $this->crud->getUpdateFields($id);
        $this->data['title'] = $this->crud->getTitle() ?? trans('backpack::crud.edit').' '.$this->crud->entity_name;

        $this->data['id'] = $id;

        // load the view from /resources/views/vendor/backpack/crud/ if it exists, otherwise load the one in the package
        return view($this->crud->getEditView(), $this->data);
    }

    public function store(StoreRequest $request)
    {
        $start_at   =   $request->date . ' ' . $request->start_time;
        $end_at     =   $request->date . ' ' . $request->end_time;

        $code = substr(md5(uniqid(mt_rand(), true)) , 0, 7);
        $request->request->set('code', $code);
        $request->request->set('employee_id', backpack_auth()->user()->employee_id);
        $request->request->set('start_at', $start_at);
        $request->request->set('end_at', $end_at);
        // your additional operations before save here
        $redirect_location = parent::storeCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        if($request->select_all_students)
        {
            // Delete All Students From Meeting 
            StudentsMeeting::where('meeting_id', $this->data['entry']->id)->delete();

            // Insert Students To Meeting
            $students = Student::all();
            if(count($students)>0){
                foreach ($students as $key => $student) {
                    $studentsMeeting = new StudentsMeeting();
                    $studentsMeeting->meeting_id = $this->data['entry']->id;
                    $studentsMeeting->student_id = $student->id;
                    $studentsMeeting->save();
                }
            }
        }

        if($request->select_all_teachers)
        {
            // Delete All Teachers From Meeting 
            UsersMeeting::where('meeting_id', $this->data['entry']->id)->delete();

            // Insert Teachers To Meeting
            $employees = Employee::all();
            if(count($employees)>0){
                foreach ($employees as $key => $employee) {
                    $employeeMeeting = new UsersMeeting();
                    $employeeMeeting->meeting_id = $this->data['entry']->id;
                    $employeeMeeting->employee_id = $employee->id;
                    $employeeMeeting->save();
                }
            }
        }
        $usersMeeting   =   UsersMeeting::where('employee_id', backpack_auth()->user()->employee->id)
                                ->where('meeting_id', $this->data['entry']->id)
                                ->first();
        if(!$usersMeeting)
        {
            UsersMeeting::create([
                'employee_id'   =>  backpack_auth()->user()->employee->id,
                'meeting_id'    =>  $this->data['entry']->id
            ]);
        }
        return redirect()->back();
        // return $redirect_location;
    }

    public function update(UpdateRequest $request)
    {
        $start_at   =   $request->date . ' ' . $request->start_time;
        $end_at     =   $request->date . ' ' . $request->end_time;

        $request->request->set('start_at', $start_at);
        $request->request->set('end_at', $end_at);
        
        // your additional operations before save here
        $redirect_location = parent::updateCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        if($request->select_all_students)
        {
            // Delete All Students From Meeting 
            StudentsMeeting::where('meeting_id', $request->id)->delete();

            // Insert Students To Meeting
            $students = Student::all();
            if(count($students)>0){
                foreach ($students as $key => $student) {
                    $studentsMeeting = new StudentsMeeting();
                    $studentsMeeting->meeting_id = $request->id;
                    $studentsMeeting->student_id = $student->id;
                    $studentsMeeting->save();
                }
            }
        }

        if($request->select_all_teachers)
        {
            // Delete All Teachers From Meeting 
            UsersMeeting::where('meeting_id', $request->id)->delete();

            // Insert Teachers To Meeting
            $employees = Employee::all();
            if(count($employees)>0){
                foreach ($employees as $key => $employee) {
                    $employeeMeeting = new UsersMeeting();
                    $employeeMeeting->meeting_id = $request->id;
                    $employeeMeeting->employee_id = $employee->id;
                    $employeeMeeting->save();
                }
            }
        }

        $usersMeeting   =   UsersMeeting::where('employee_id', backpack_auth()->user()->employee->id)
                                ->where('meeting_id', $this->data['entry']->id)
                                ->first();
        if(!$usersMeeting)
        {
            UsersMeeting::create([
                'employee_id'   =>  backpack_auth()->user()->employee->id,
                'meeting_id'    =>  $this->data['entry']->id
            ]);
        }
        return $redirect_location;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return string
     */
    public function destroy($id)
    {
        $this->crud->allowAccess('delete');
        $this->crud->hasAccessOrFail('delete');
        $this->crud->setOperation('delete');

        // get entry ID from Request (makes sure its the last ID for nested resources)
        $id = $this->crud->getCurrentEntryId() ?? $id;

        $meeting = Meeting::findOrFail($id);
        if(!backpack_user()->hasRole('School Head'))
        {
            if($meeting->employee_id != backpack_auth()->user()->employee_id){
                \Alert::warning("Unauthorized Access.")->flash();
                return redirect()->back();
            }
        }
        $this->crud->delete($id);

        \Alert::success("Successfully Deleted.")->flash();
        return redirect()->back();    
    }

    public function setArchive(Request $request)
    {
        $code = $request->code;
        $meeting = Meeting::where('code', $code)->first();
        if(!$meeting){
            \Alert::warning("Meeting Not Found.")->flash();
            return redirect()->back();
        }

        if(!backpack_user()->hasRole('School Head'))
        {
            if($meeting->employee_id != backpack_auth()->user()->employee_id){
                \Alert::warning("Unauthorized Access.")->flash();
                return redirect()->back();
            }
        }

        $meeting->archive = 1;
        $meeting->update();
        \Alert::success("Successfully Updated.")->flash();
        return redirect()->back();
    }

    public function restoreArchive(Request $request)
    {
        $code = $request->code;
        $meeting = Meeting::where('code', $code)->first();
        if(!$meeting){
            \Alert::warning("Meeting Not Found.")->flash();
            return redirect()->back();
        }

        if(!backpack_user()->hasRole('School Head'))
        {
            if($meeting->employee_id != backpack_auth()->user()->employee_id){
                \Alert::warning("Unauthorized Access.")->flash();
                return redirect()->back();
            }
        }

        $meeting->archive = 0;
        $meeting->update();
        \Alert::success("Successfully Updated.")->flash();
        return redirect()->back();
    }

    public function getMeeting($code){
        $meeting = $this->crud->model::where('code', $code)->first();
        return $meeting;
    }

    public function getMyMeetings()
    {
        $meetings       =   [];
        $meeting_tag    =   UsersMeeting::where('employee_id', backpack_auth()->user()->employee->id)->get();

        if(backpack_auth()->user()->employee) {
            if(backpack_auth()->user()->hasRole('Administrator')){
                $meetings   =   Meeting::with(['employee'])
                                    // ->where('employee_id', backpack_auth()->user()->employee->id)
                                    // ->orWhereIn('id', $meeting_tag->pluck('meeting_id'))
                                    ->orderBy('date', 'DESC')
                                    ->notArchive()
                                    ->active()
                                    ->get();
            }
            else {
                $meetings   =   Meeting::with(['employee'])
                                    ->where('employee_id', backpack_auth()->user()->employee->id)
                                    ->orWhereIn('id', $meeting_tag->pluck('meeting_id'))
                                    ->orderBy('date', 'DESC')
                                    ->notArchive()
                                    ->active()
                                    ->get();
            }
        }
        return $meetings;
    }

    public function getMyArchiveMeetings()
    {
        $meetings       =   [];
        $meeting_tag    =   UsersMeeting::where('employee_id', backpack_auth()->user()->employee->id)->get();

        if(backpack_auth()->user()->hasRole('School Head')){
            $meetings   =   Meeting::with(['employee'])
                                ->where('employee_id', backpack_auth()->user()->employee->id)
                                ->orWhereIn('id', $meeting_tag->pluck('meeting_id'))
                                ->orderBy('name')
                                ->archive()
                                ->active()
                                ->get();
        }
        else if(backpack_user()->hasRole('Teacher')){
            $meetings   =   Meeting::with(['employee'])
                                ->where('employee_id', backpack_auth()->user()->employee->id)
                                ->orWhereIn('id', $meeting_tag->pluck('meeting_id'))
                                ->orderBy('name')
                                ->archive()
                                ->active()
                                ->get();
        }
        return $meetings;
    }

    /*
    |--------------------------------------------------------------------------
    | START VIDEO CONFERENCE
    |--------------------------------------------------------------------------
    */
    public function videoConference(Request $request)
    {
        $code      = $request->code;
        $meeting   = self::getMeeting($code);
        $employee  = backpack_auth()->user()->employee;
        
        if(! $meeting) {
            \Alert::warning("Meeting Not Found.")->flash();
            return redirect('admin/meeting');
        }

        $zoom_user = ZoomUser::where('active', '<', 2)->inRandomOrder()->first();

        if(! $zoom_user) {
            \Alert::warning("You have reached the maximum number of active host. Please contact your accounts manager.")->flash();
            return redirect('admin/meeting');
        }
        
        /************************
        ** Create Zoom Meeting **
        ************************/
        $meeting_data = [
            'code'      => $meeting->code,
            'topic'     => $meeting->name,
            'agenda'    => $meeting->description,
            'password'  => $meeting->code,
            'host_email' => $zoom_user ? $zoom_user->email : null,
            'firstname' => $employee->firstname,
            'lastname' => $employee->lastname
        ];

        $zoomController  = new ZoomMeetingController();
        $create_meeting = $zoomController->createMeeting($meeting_data);

        if(! $create_meeting['success']) {
            \Alert::warning("Unable to create meeting.")->flash();
            return redirect('admin/meeting');
        }

        /***********************
        ** Store Zoom Meeting **
        ***********************/
        $zoom_meeting = ZoomMeeting::create([
            'meetingable_id'    => $meeting->id,
            'meetingable_type'  => 'App\Models\Meeting',
            'zoom_user_id'      => $zoom_user->id,
            'employee_id'       => $employee->id,
            'zoom_uuid'         => $create_meeting['data']['uuid'],
            'zoom_id'           => $create_meeting['data']['id'],
            'zoom_host_id'      => $create_meeting['data']['host_id'],
            'data'   => json_encode($create_meeting['data']),
            'status' => $create_meeting['data']['status'],
            'active' => 1
        ]);

        /*******************
        ** UPDATE MEETING **
        *******************/
        $meeting   = $zoom_meeting->meetingable;
        $meeting->zoom_id = $create_meeting['data']['id'];
        $meeting->status  = $create_meeting['data']['status'];
        $meeting->save();

        /*************************
        ** Set Zoom User Active **
        *************************/
        // $zoom_user->update([
        //     'active' => 1
        // ]);

        return redirect()->away($create_meeting['data']['start_url']);
        // return BBB::createEmployeeMeeting($meetingId, $meeting);
    }

    /*
    |--------------------------------------------------------------------------
    | JOIN BIG BLUE BUTTON VIDEO CONFERENCE
    |--------------------------------------------------------------------------
    */
    // public function joinConference($code)
    // {
    //     $employee = backpack_auth()->user()->employee;
    //     $url = BBB::joinEmployeeVideoConference($code, $employee->full_name);
    //     return redirect()->to($url);
    // }
    
    /*
    |--------------------------------------------------------------------------
    | JOIN ZOOM VIDEO CONFERENCE
    |--------------------------------------------------------------------------
    */
    public function joinConference($code)
    {
        $my_meetings  = $this->getMyMeetings();
        $meeting = Meeting::where('code', $code)->first();

        if(! $meeting) {
            \Alert::warning("Meeting Not Found.")->flash();
            return redirect()->to('/admin/meeting');
        }
        if(! in_array($meeting->id, $my_meetings->pluck('id')->toArray())) {
            \Alert::warning("Invalid Meeting.")->flash();
            return redirect()->to('/admin/meeting');
        }
        if(! $meeting->conference_status) {
            \Alert::warning("Meeting is not yet Ongoing.")->flash();
            return redirect()->to('/admin/meeting');
        }
        if($meeting->join_url) {
            return redirect()->to($meeting->join_url);
        }

        \Alert::warning("Meeting Join URL Not Found.")->flash();
        return redirect()->to('/admin/meeting');
    }
    
    public function getVideoConferenceStatus()
    {
        $meetings = $this->getMyMeetings();
        $status = [];

        if($meetings){
           if(count($meetings) > 0){
                foreach ($meetings as $meeting) {
                    $meetingId = $meeting->code;
                    $password = "teacher-" . $meeting->code;
                    $video_conference_info = BBB::getConferenceStatus($meetingId, $password);
                    $status[] = [
                        'meetingId' => $meeting->code,
                        'data' => gettype($video_conference_info) == "object" ? $video_conference_info : null
                    ];
                }
            } 
        }

        return $status;
    }

    /*
    |--------------------------------------------------------------------------
    | BIG BLUE BUTTON RECORDINGS
    |--------------------------------------------------------------------------
    */
    // public function getRecordings ($class_code)
    // {
    //     $recordingParams = new GetRecordingsParameters($class_code);
    //     $recordingParams->setMeetingId($class_code);

    //     $bbb = new BigBlueButton();
    //     $response = $bbb->getRecordings($recordingParams);

    //     $this->data['recordings'] = $response->getRawXml()->recordings->recording; 
    //     $this->data['crud'] = $this->crud;
    //     $this->data['entry'] = $this->crud->model::where('code', $class_code)->first();

    //     if ($response->getReturnCode() == 'SUCCESS') {
    //         return view('meeting.recordings', $this->data);
    //     }
    // }

    /*
    |--------------------------------------------------------------------------
    | ZOOM RECORDINGS
    |--------------------------------------------------------------------------
    */
    public function getRecordings ($code)
    {
        $this->data['crud']  = $this->crud;
        $this->data['entry'] = $this->crud->model::where('code', $code)->first();
        
        if(! $this->data['entry']) {
            \Alert::warning("Meeting Not Found.")->flash();
            return redirect()->to('/admin/meeting');
        }
        $this->data['recordings'] = $this->data['entry']->zoomRecordings;
        return view('meeting.zoom_recordings', $this->data);
    }

    public function getStudents(Request $request)
    {
        $search_term = $request->input('q');
        $page = $request->input('page');

        if ($search_term)
        {
            $results = Student::where('firstname', 'LIKE', '%'.$search_term.'%')
                            ->orWhere('middlename', 'LIKE', '%'.$search_term.'%')
                            ->orWhere('lastname', 'LIKE', '%'.$search_term.'%')
                            ->paginate(10);
        }
        else
        {
            $results = Student::paginate(10);
        }

        return $results;
    }
}
