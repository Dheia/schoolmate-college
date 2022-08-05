<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\AnnouncementRequest as StoreRequest;
use App\Http\Requests\AnnouncementRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;
use Carbon\Carbon;
use App\Models\SchoolYear;
use App\User;
use App\ParentCredential;
use App\StudentCredential;
use App\Notifications\GlobalMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

use App\Models\Announcement;

use Illuminate\Support\Facades\DB;

use App\Jobs\PublishAnnouncementJob;

use App\Events\ReloadEmployeeNotification;

/**
 * Class AnnouncementCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class AnnouncementCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Announcement');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/announcement');
        $this->crud->setEntityNameStrings('announcement', 'announcements');

        $this->crud->setShowView('announcement.show');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        // $this->crud->setFromDb();

        // add asterisk for fields that are required in AnnouncementRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');

        $this->crud->allowAccess('show');

        $schoolyear = SchoolYear::where('isActive', 1)->first();

        /*
        |--------------------------------------------------------------------------
        | FIELDS
        |--------------------------------------------------------------------------
        */

        $this->crud->addField([
            'label' => 'Message',
            'name' => 'message',
            'type' => 'textarea',
        ]);

        $this->crud->addField([ // image
            'label' => "Image",
            'name' => "image",
            'type' => 'image',
            'upload' => true,
            'crop' => false, // set to true to allow cropping, false to disable
            // 'aspect_ratio' => 1, // omit or set to 0 to allow any aspect ratio
            // 'disk' => 's3_bucket', // in case you need to show images from a different disk
            // 'prefix' => 'uploads/images/profile_pictures/' // in case your db value is only the file name (no path), you can use this to prepend your path to the image src (in HTML), before it's shown to the user;
        ]);

        $this->crud->addField([   // Upload
            'name' => 'files',
            'label' => 'Files',
            'type' => 'browse_multiple',
            // 'upload' => true,
            // 'disk' => 'uploads' // if you store files in the /public folder, please omit this; if you store them in /storage or S3, please specify it;
        ]);

        $this->crud->addField([ // select_from_array
            'name' => 'audience',
            'label' => "Audience",
            'type' => 'select_from_array',
            'options' => [
                'global'    => 'Global', 
                'employee'  => 'Employee',
                'student'   => 'Student',
                'parent'    => 'Parent',
            ],
            'allows_null' => false,
            'default' => 'global',
            // 'allows_multiple' => true, // OPTIONAL; needs you to cast this to array in your model;
        ]);
        
        $this->crud->addField([
            'name' => 'event_date_range', // a unique name for this field
            'start_name' => 'start', // the db column that holds the start_date
            'end_name' => 'end', // the db column that holds the end_date
            'label' => 'Announcement Period',
            'type' => 'date_range',
            // OPTIONALS
            'start_default' => Carbon::now(), // default value for start_date
            'end_default' => $schoolyear->end_date ?? Carbon::now(), // default value for end_date
            'date_range_options' => [ // options sent to daterangepicker.js
                'timePicker' => true,
                'locale' => ['format' => 'DD/MM/YYYY HH:mm']
            ]
        ]);

        // $this->crud->addField([
        //     'name'  =>  'global',
        //     'type'  =>  'checkbox',
        //     'label' =>  'Global'
        // ]);

        $this->crud->addField([
            'name'  =>  'user_id',
            'type'  =>  'hidden',
            'label' =>  'User ID',
            'value' => backpack_auth()->user()->id
        ]);

        /*
        |--------------------------------------------------------------------------
        | COLUMNS
        |--------------------------------------------------------------------------
        */

        $this->crud->addColumn([
            'name'  =>  'user.fullname',
            'type'  =>  'text',
            'label' =>  'Posted by'

        ]);

        $this->crud->addColumn([
            'name'  =>  'image',
            'type'  =>  'image',
            'label' =>  'Image',
            'height' => '30px',
            'width' => '30px',
        ]);

        $this->crud->addColumn([
            'name'  =>  'message',
            'type'  =>  'textarea',
            'label' =>  'Message'

        ]);

        $this->crud->addColumn([
            // run a function on the CRUD model and show its return value
            'name' => "files_with_link",
            'label' => "Files", // Table column heading
            'type' => "markdown",
            // 'function_name' => 'getFilesWithLink', // the method in your Model
            // 'function_parameters' => [$one, $two], // pass one/more parameters to that method
            // 'limit' => 100, // Limit the number of characters shown
         ]);

        // $this->crud->addColumn([
        //     'name'  =>  'global',
        //     'type'  =>  'check',
        //     'label' =>  'Global Message'

        // ]);

        $this->crud->removeColumn('user_id');
        
        if(backpack_user()->email != 'dev@schoolmate-online.net') {
            if(! backpack_user()->hasRole('Administrator')) {
                $this->crud->denyAccess(['create','update','delete']);

            }
        }

        $this->crud->orderBy('created_at', 'DESC');

    }

    public function store(StoreRequest $request)
    {
        // your additional operations before save here
        $request->request->set('user_id', backpack_auth()->user()->id);
        
        $redirect_location = parent::storeCrud($request);
        
        // if($request->input('global') || $request->input('audience')) {
        //     $users = User::all();

        //     foreach ($users as $user) {
        //         $user->notify(new GlobalMessage($this->data['entry']->id,$this->data['entry']->message));
        //     }
        // }

        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        $announcement   = $this->data['entry'];
        $audience       = $request->input('audience');

        PublishAnnouncementJob::dispatch($announcement);
        // ReloadEmployeeNotification::dispatch();

        return $redirect_location;
    }

    public function update(UpdateRequest $request)
    {
        // your additional operations before save here
        $redirect_location = parent::updateCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }

    public function show($id){
        $content = parent::show($id);

        $notification_id = request()->notification_id;
        $user = \App\User::where('id', backpack_user()->id)->first();
        
        if($notification_id) {
            
            $notification = $user->unreadNotifications()->where('id', $notification_id)->first();

            if($notification) {

                $notification->read_at = now();
    
                $notification->save();
            }
   
        }

        return $content;
    }

    public function destroy($id)
    {
        $this->crud->hasAccessOrFail('delete');

        $announcement = Announcement::findOrFail($id);

        $notification_id = (int)$id;

        $notifications = DB::table('notifications')->whereJsonContains('data', ['announcement_id' => $notification_id])->delete();
        // dd($notification_id, ['notification_id' => $id], $notifications);
        return $this->crud->delete($id);
    }

    public function postAnnouncement(Request $request)
    {
        $period     = $request->period;
        $audience   = $request->audience;

        $start_date = $request->date_from;
        $end_date   = $request->date_to;
        switch ($period) {
            case 'today':

                $start_date         =   Carbon::today();
                $end_date           =   Carbon::today();
                break;

            case 'this_week':

                $start_date         =   Carbon::now()->startOfWeek();
                $end_date           =   Carbon::now()->endOfWeek();
                break;

            case 'this_month':

                $start_date         =   Carbon::now()->startOfMonth();
                $end_date           =   Carbon::now()->endOfMonth();
                break;

            case 'custom':

                if( $start_date == null && $end_date == null ) {
                    return  ["status" => "error", "message" => "No Selected Date"];
                }

                if( $this->validateDate($start_date) == false && $this->validateDate($end_date) == false) {
                    return  ["status" => "error", "message" => "Invalid Date Format"];
                }

                $start_date         =   Carbon::parse($start_date);
                $end_date           =   Carbon::parse($end_date);
                break;
            
            default: 
                $start_date         =   Carbon::today();
                $end_date           =   Carbon::today();
                break;
        }

        $announcement = Announcement::create([
            'message'   => $request->message,
            'start'     => $start_date,
            'end'       => $end_date,
            'user_id'   => backpack_auth()->user()->id,
            'audience'  => $request->audience,
            'global'    => $request->audience == 'global' ? 1 : 0,
        ]);

        PublishAnnouncementJob::dispatch($announcement);
        // ReloadEmployeeNotification::dispatch();

        return  ["status" => "success", "message" => "Announcement has been successfully created.", "data" => $announcement];
    }

    public function validateDate($date, $format = 'Y-m-d')
    {
        $d = new \DateTime();
        $d = $d->createFromFormat($format, $date);
        return $d && $d->format($format) === $date;
    }
}