<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\TextBlastRequest as StoreRequest;
use App\Http\Requests\TextBlastRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;
use App\Http\Controllers\SmartMessaging;
use Carbon\Carbon;
use App\Models\StudentSmsTagging as SMS;

/**
 * Class TextBlastCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class TextBlastCrudController extends CrudController
{
    use SmartMessaging;

    public function setup()
    {

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\TextBlast');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/text-blast');
        $this->crud->setEntityNameStrings('blast', 'text blasts');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        $this->crud->setFromDb();

        // add asterisk for fields that are required in TextBlastRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');

        $this->crud->denyAccess(['delete', 'update']);

        $this->crud->removeColumns(['subscribers','response','is_now']);

        $this->crud->orderBy('created_at', 'desc');

        $this->crud->addColumn([
            'label' => 'Recipients',
            'type'  => 'array',
            'name'  => 'blast_type'
        ]);

        $this->crud->addColumn([
            'label' => 'Send Date/Time',
            'type'  => 'datetime',
            'name'  => 'send_date_time',
            'format' => 'MMMM D, YYYY h:mm A',
        ]);

        $this->crud->addColumn([
            'label' => 'Created At',
            'type'  => 'text',
            'name'  => 'created_at'
        ]);

        $this->crud->addColumn([
            'label' => 'Total Subscriber',
            'type'  => 'text',
            'name'  => 'total'
        ]);

        $this->crud->addColumn([
            'label' => 'Successful',
            'type'  => 'check',
            'name'  => 'success'
        ]);

        $this->crud->addColumn([
            'label' => 'Time Period',
            'type'  => 'text',
            'name'  => 'timeAgo'
        ]);

        $this->crud->addColumn([
            'label' => 'Message',
            'type'  => 'text',
            'name'  => 'message',
            'limit' => 20
        ]);

        $this->crud->addColumn([
            'label' => 'Total Text Messages',
            'type'  => 'text',
            'name'  => 'total_text_messages',
        ]);

        $this->crud->addColumn([
            'label' => 'Total Sent Messages',
            'type'  => 'text',
            'name'  => 'total_sent_messages',
        ]);

        $this->crud->addField([
            'name'  => 'is_now',
            'type'  => 'checkbox',
            'label' => 'Send Now'
        ]);

        $this->crud->addField([
            'name'  => 'blast_type',
            'type'  => 'textBlast.blast_type',
            'label' => 'Recipient(s):'
        ]);

        $this->crud->addField([
            'label' => 'Title',
            'type' => 'text',
            'name' => 'title'
        ]);

        $this->crud->addField([
            'label' => 'Message',
            'type' => 'textBlast.text_with_count_characters',
            'name' => 'message',
            'max'   => 160 - ((int)strlen(config('settings.schoolabbr')) + 2)
        ]);

        $this->crud->addField([
            'label' => 'Send Date/Time',
            'type' => 'datetime',
            'name' => 'send_date_time',
            'default' => now()
        ]);

        $this->crud->addField([
            'name'  => 'subscribers',
            'type'  => 'hidden'
        ]);

        $this->crud->addField([
            'name'  => 'response',
            'type'  => 'hidden'
        ]);

        $this->crud->addField([
            'name'  => 'success',
            'type'  => 'hidden'
        ]);

        $this->crud->addField([
            'name'  => 'total',
            'type'  => 'hidden'
        ]);

        $this->crud->enableDetailsRow();
        $this->crud->allowAccess('details_row');
    }

    public function store(StoreRequest $request)
    {
        $group_id = config('settings.smsgroup');

        if($group_id == null || $group_id == "") {
            \Alert::warning("Group ID not yet set. Click Set Contact Group in Student Sms Taggings")->flash();
            return redirect()->to('admin/sms');
        }

        // STUDENT
        $subscribers = [];

        if(collect($request->blast_type)->contains("student")) {
            $subscribers[] = SMS::distinct()->where('user_type', 'student')->get(['subscriber_number'])->pluck('subscriber_number');
        }  

        // TEACHER
        if(collect($request->blast_type)->contains("teacher")) {
            $subscribers[] = SMS::distinct()->where('position_type', 'Teaching Personnel')
                                ->orWhere('position_type', 'Non-Teaching/Teaching')
                                ->get(['subscriber_number'])
                                ->pluck('subscriber_number');
        }

        // EMPLOYEE
        if(collect($request->blast_type)->contains("employee")) {         
            $subscribers[] = SMS::distinct()->where('user_type', 'employee')->get(['subscriber_number'])->pluck('subscriber_number');
        }


        $subscribers = array_flatten($subscribers);
        
        // Remove Duplicated Number
        $subscribers = collect($subscribers)->unique()->all();

        if($subscribers == null) {
            \Alert::warning("No Recipients Found")->flash();
            return redirect()->back();
        }
        
        $request->request->set('subscribers', $subscribers);

        // your additional operations before save here
        if($request->is_now){

            $resp = $this->sendSmsBlast($request);
            if(array_key_exists('error', $resp)) {
                \Alert::warning($resp['message'])->flash();
                return redirect()->back()->withInput();
            }
            $resp = json_encode($resp);
            $request->request->set('send_date_time',Carbon::now());
            $request->request->set('response',$resp);
            $request->request->set('success',True);

        } else {
            $request->request->set('success',False);
            $request->request->set('response',"pending");
        }

        $request->request->set('blast_type', json_encode($request->blast_type));
        $request->request->set('subscribers', json_encode($subscribers));
        $request->request->set('total',count($subscribers));
        // dd($request);
        $redirect_location = parent::storeCrud($request);
       
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
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

    public function showDetailsRow($id)
    {
        $this->crud->hasAccessOrFail('details_row');
        $this->crud->setOperation('list');

        // get entry ID from Request (makes sure its the last ID for nested resources)
        $id = $this->crud->getCurrentEntryId() ?? $id;

        $this->data['entry'] = $this->crud->getEntry($id);
        $this->data['crud'] = $this->crud;

        // load the view from /resources/views/vendor/backpack/crud/ if it exists, otherwise load the one in the package
        return view('smsBlast.mailbox', $this->data);
    }
}
