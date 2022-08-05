<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\KioskSettingRequest as StoreRequest;
use App\Http\Requests\KioskSettingRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;
use Illuminate\Http\Request;

/**
 * Class KioskSettingCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class KioskSettingCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\KioskSetting');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/kiosk-setting');
        $this->crud->setEntityNameStrings('kiosk setting', 'kiosk Settings');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        $this->crud->setFromDb();

        // add asterisk for fields that are required in KioskSettingRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');

        $this->crud->denyAccess(['update', 'create', 'delete']);

        $this->crud->setListView('kiosk/settings/dashboard');
        $this->crud->setCreateView('kiosk/settings/dashboard');
        $this->crud->setEditView('kiosk/settings/dashboard');

        $this->data['announcement']       = $this->crud->model::where('key', 'announcement')->first();
        $this->data['additionalPage']     = $this->crud->model::where('key', 'additional_page')->first();
        $this->data['termsConditions']    = $this->crud->model::where('key', 'terms_conditions')->first();

        $this->data['settings']    = $this->crud->model::all();


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

    public function updateKioskSettings()
    {
        $response = [
                        'error' => false,
                        'message' => null,
                        'data' => null
                    ];
        $setting = $this->crud->model::where('key', request()->key)->first();
        if(request()->checked == 'true')
        {
            $setting->active = 1;
            if($setting->update())
            {
                $response['title']   = 'Kiosk Updated';
                $response['message'] = $setting->name . ' Successfully Set to Active.';
                return $response;
            }
            else{
                $response['error'] = true;
                $response['title']   = 'Error';
                $response['message'] = 'Error Updating, Something Went Wrong, Please Try To Reload The Page.';
                return $response;
            }
        }
        else if(request()->checked == 'false'){
            $setting->active = 0;
            if($setting->update())
            {
                $response['title']   = 'Kiosk Updated';
                $response['message'] = $setting->name . ' Successfully Set to Inactive.';
                return $response;
            }
            else{
                $response['error'] = true;
                $response['title']   = 'Error';
                $response['message'] = 'Error Updating, Something Went Wrong, Please Try To Reload The Page.';
                return $response;
            }
        }
    }

    public function updateAnnouncement()
    {
        $announcement = $this->crud->model::where('key', 'announcement')->first();
        $announcement->description = request('announcement-input');
        if($announcement->update())
        {
            \Alert::success("Announcement Successfully Updated")->flash();
            return redirect()->back();
        }
    }

    public function updateAdditionalPage()
    {
        $page = $this->crud->model::where('key', 'additional_page')->first();
        $page->description = request('additionalPage-input');
        if($page->update())
        {
            \Alert::success("Additional Page Successfully Updated")->flash();
            return redirect()->back();
        }
    }

    public function updateTermsConditions()
    {
        $page = $this->crud->model::where('key', 'terms_conditions')->first();
        $page->description = request('termsConditions-input');
        if($page->update())
        {
            \Alert::success("Terms, Conditions and Data Privacy Successfully Updated")->flash();
            return redirect()->back();
        }
    }

}
