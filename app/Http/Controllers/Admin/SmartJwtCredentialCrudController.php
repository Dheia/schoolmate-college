<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\SmartJwtCredentialRequest as StoreRequest;
use App\Http\Requests\SmartJwtCredentialRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;
use Illuminate\Http\Request;

use App\Http\Controllers\RestCurl as Rest;
use App\Models\SmartJwtCredential;
use Backpack\Settings\app\Models\Setting;
use Carbon\Carbon;
use App\Models\Student;
use App\Models\Employee;
use App\Models\StudentSmsTagging;
/**
 * Class SmartJwtCredentialCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class SmartJwtCredentialCrudController extends CrudController
{

    private $model;

    public function __construct ()
    {
        // ob_end_flush();
        $model = SmartJwtCredential::first();
        // $this->refreshToken($model->access_token, $model->refresh_token);
        // CHECK IF TOKEN IS NOT NULL



        if($model !== null) {

            // CHECK IF EXPIRED
            if(Carbon::parse($model->updated_at)->diffInSeconds() > $model->expires_in - 400)
            {
                
                $this->refreshToken($model->access_token, $model->refresh_token);
            }
        }
    }

    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\SmartJwtCredential');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/smartjwtcredential');
        $this->crud->setEntityNameStrings('smartjwtcredential', 'smart_jwt_credentials');

        $this->init();
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        $this->crud->setFromDb();

        // add asterisk for fields that are required in SmartJwtCredentialRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
    }

    public function store(StoreRequest $request)
    {
        // your additional operations before save here
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

    public function destroy ($id)
    {
        $model = SmartJwtCredential::findOrFail($id);
        $model->delete();
    }

    public function authorize ()
    {
        $model = SmartJwtCredential::first();

        if($model !== null) { 
            \Alert::warning("You Are Already Authorized")->flash();
            return redirect()->to('admin/sms'); 
        }

        $username = env('SMART_MESSAGING_USERNAME');
        $password = env('SMART_MESSAGING_PASSWORD');

        $url    = 'https://messagingsuite.smart.com.ph/rest/auth/login';
        $header =   array("Content-Type: application/json");
        $fields =   array(
                        'username' => env('SMART_MESSAGING_USERNAME'),
                        'password' => env('SMART_MESSAGING_PASSWORD'),
                    );
        $fields = json_encode($fields);

        $resp   = Rest::post($url, $header, $fields);
        $data   = $resp->data;

        if(key_exists('accessToken', $data)) {            

            $smartJWT = SmartJwtCredential::create([
                            'access_token'   => $data->accessToken,
                            'refresh_token'  => $data->refreshToken,
                            'expires_in'     => $data->expiresIn,
                            'token_type'     => $data->tokenType,
                        ]);

            if($smartJWT)
            {
                \Alert::success("Successfully Created")->flash();
                return redirect()->to('/admin/sms');
            }
            \Alert::warning("Error Creating")->flash();
            return redirect()->to('admin/sms');
        } else {
            abort($data->statusCode, $data->errorDescription);
        }

    }

    private function refreshToken ($access_token = null, $refresh_token = null)
    {

        SmartJwtCredential::truncate();
        $model = SmartJwtCredential::first();

        $username = env('SMART_MESSAGING_USERNAME');
        $password = env('SMART_MESSAGING_PASSWORD');

        $url    = 'https://messagingsuite.smart.com.ph/rest/auth/login';
        $header = array('Content-Type: application/json');
        $fields = array(
                            'username' => env('SMART_MESSAGING_USERNAME'),
                            'password' => env('SMART_MESSAGING_PASSWORD'),
                        );
        $fields = json_encode($fields);

        $resp   = new Rest;
        $resp   = $resp->post($url, $header, $fields);
        $data   = $resp->data;

        if($data) {            
            $smartJWT = SmartJwtCredential::create([
                            'access_token'  => $data->accessToken,
                            'refresh_token'  => $data->refreshToken,
                            'expires_in'    => $data->expiresIn,
                            'token_type'    => $data->tokenType,
                        ]);
        }

        if(!$smartJWT)
        {
            \Alert::warning("Error Creating")->flash();
        }
    }

    public function sendSms (Request $request)
    {

        $model  = SmartJwtCredential::first();

        if($model === null) {
            return false;
        }

        $endpoints = $request->get('subscriber_number');
        $endpoints[] =  [
            'type' => 1,
            'id' => $request->get('log_id')
        ];

        $url    = 'https://messagingsuite.smart.com.ph/rest/messages/sms';
        $header = array('Authorization: Bearer ' . $model->access_token, 'Content-Type: application/json');
        $fields =   [
                        "message"   => [ "text" => $request->get("message") ],
                        "endpoints" => $endpoints,
                        // "endpoints" => [ $request->get("subscriber_number"), ['type' => 1, 'id' => $request->get("log_id")] ],
                    ];
        $fields = json_encode($fields);
        // dd($fields);
        // Exec
        $resp   = Rest::post($url, $header, $fields);

        return $resp;
    }

    public function sendSmsBlast (Request $request)
    {
        $model  = SmartJwtCredential::first();

        if($model === null) { return ["error" => true, "message" => "Unable to send message, Please authorize your network provider."]; }
        
        $url    = 'https://messagingsuite.smart.com.ph/rest/messages/sms';
        $header = array('Authorization: Bearer ' . $model->access_token, 'Content-Type: application/json');
        $fields =   [
                        "message"   => [ "text" => config('settings.schoolabbr'). ": " .$request->get("message") ],
                        "endpoints" => $request->get("endpoints"),
                    ];
        $fields = json_encode($fields);
        // Exec
        $resp   = Rest::post($url, $header, $fields);

        if($resp->status > 202) {
            $this->refreshToken();
            $this->sendSmsBlast($request);
        }

        return $resp;
    }

    public function createGroups ()
    {
        $model = SmartJwtCredential::first();

        if(config('settings.smsgroup') !== null) {
            \Alert::warning("Group Has Already Been Created.")->flash();
            return redirect()->back();
        }


        $url    = 'https://messagingsuite.smart.com.ph/rest/groups/';
        $header = array('Authorization: Bearer ' . $model->access_token, 'Content-Type: application/json');
        $fields =   [
                        "name"                      => config('settings.schoolabbr'),//request()->name,
                        "description"               => config('settings.schoolname'),//request()->description,
                        "destinationPresentation"   => "\$first \$last",
                    ];
        $fields = json_encode($fields);

        // Exec
        $resp   = Rest::post($url, $header, $fields);
        $url= $resp->header->Location;
        $id = null;
        if(preg_match("/\/(\d+)$/",$url,$matches))
        {
          $id = $matches[1];
        }

        $setting = [
            'key'         => 'smsgroup',
            'name'        => 'Smart Messaging Group',
            'description' => 'Smart Messaging Group For ' . config('settings.schoolname'),
            'value'       => $id,
            'field'       => '{ "name":"value","label":"Value", "type":"text"}',
            'active'      => 1,
        ];

        Setting::insert($setting);
        \Alert::success("Group Succesfully Created")->flash();
        return redirect()->back();
    }

    public function listGroups ()
    {
        $model = SmartJwtCredential::first();

        $url    = 'https://messagingsuite.smart.com.ph/rest/groups/';
        $header = array('Authorization: Bearer ' . $model->access_token, 'Content-Type: application/json');
        $fields =   [
                        "name"                      => "Westfields",//request()->name,
                        "description"               => "Westfields International School",//request()->description,
                        "destinationPresentation"   => "\$first \$last",
                    ];
        // $fields = json_encode($fields);

        // Exec
        $resp   = Rest::get($url, $header, $fields);
        $data   = $resp->data;

        return response()->json($data);
    }

    public function readGroup () 
    {

        $model = SmartJwtCredential::first();

        if($model == null) {
            \Alert::warning('Unauthorized To Network')->flash();
            return false;
        }

        $url    = 'https://messagingsuite.smart.com.ph/rest/groups/' . config('settings.smsgroup');
        $header = ['Authorization: Bearer ' . $model->access_token, 'Content-Type: application/json'];
        $fields =   [];

        // Exec
        $resp   = Rest::get($url, $header, $fields);
        $data   = $resp->data;

        return response()->json($data);
    }

    public function addSubscriber ($request)
    {
        $group = $this->readGroup();

        if(isset($group->original->statusCode)) {
            return ['error' => true, 'data' => $group];
        }   

        $model = SmartJwtCredential::first();
        $student = null;
        if($request->user_type === 'student') {
            $student = Student::where('studentnumber', $request->studentnumber)->first();
        } else {
            $student = Employee::where('employee_id', $request->studentnumber)->first();
        }
        $url    = 'https://messagingsuite.smart.com.ph/rest/contacts/';
        $header = array('Authorization: Bearer ' . $model->access_token, 'Content-Type: application/json');
        $fields =   [
                        "firstName" => $student->firstname,
                        "lastName" => $student->lastname,
                        "mobile" => $request->subscriber_number,
                    ];
        $fields = json_encode($fields);

        // Exec
        $resp   = Rest::post($url, $header, $fields);

        if($resp->status > 202) {
            // \Alert::warning($resp->data->errorType . ' :' . $resp->data->errorDescription)->flash();
            return ['error' => true, 'data' => $resp];
        }

        $url            = $resp->header->Location;
        $subscriber_id  = null;
        if(preg_match("/\/(\d+)$/",$url,$matches))
        {
          $subscriber_id = $matches[1];
        }

        // ASSIGN CONTACT TO GROUP
        // api endpoint: https://messagingsuite.smart.com.ph/rest/groups/{id}/contacts

        $url    = 'https://messagingsuite.smart.com.ph/rest/groups/' . config('settings.smsgroup') . '/contacts';
        $header = array('Authorization: Bearer ' . $model->access_token, 'Content-Type: application/json');
        $fields =   [
                        "contactId" => $subscriber_id,
                        "groupId" => config('settings.smsgroup')
                    ];

        $fields = json_encode($fields);

        // Exec
        $resp   = Rest::post($url, $header, $fields);
        return $subscriber_id;
    }

    public function deleteSubscriber ($id)
    {
        $model = SmartJwtCredential::first();
        
        $url    = 'https://messagingsuite.smart.com.ph/rest/contacts/' . $id;
        $header = array('Authorization: Bearer ' . $model->access_token, 'Content-Type: application/json');

        // Exec
        return Rest::delete($url, $header);
    }

}
