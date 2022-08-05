<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\ParentUserRequest as StoreRequest;
use App\Http\Requests\ParentUserRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;

use Illuminate\Support\Facades\Hash;

use Illuminate\Http\Request;
use App\Models\ParentUser;
use App\ParentCredential;

/**
 * Class ParentUserCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class ParentUserCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\ParentUser');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/parent-user');
        $this->crud->setEntityNameStrings('Parent User', 'Parent Users');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        // $this->crud->setFromDb();

        /*
        |--------------------------------------------------------------------------
        | FIELDS
        |--------------------------------------------------------------------------
        */
        $this->crud->addField([
            'name'  => 'firstname', //database column name
            'label' => 'Firstname', //Label
            'type'  => 'text',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],
        ]);

        $this->crud->addField([
            'name'  => 'middlename', //database column name
            'label' => 'Middlename', //Label
            'type'  => 'text',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],
        ]);

        $this->crud->addField([
            'name'  => 'lastname', //database column name
            'label' => 'Lastname', //Label
            'type'  => 'text',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],
        ]);

        $this->crud->addField([
            'name'  => 'gender', //database column name
            'label' => 'Gender', //Label
            'type'  => 'enum',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ]
        ]);

        $this->crud->addField([
            'name'  => 'mobile', //database column name
            'label' => 'Mobile', //Label
            'type'  => 'number',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ]
        ]);

        $this->crud->addField([
            'name' => 'telephone', //database column name
            'label' => 'Telephone No.', //Label
            'type' => 'text',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ]
        ]);

        $this->crud->addField([
            'name' => 'photo',
            'label' => 'Photo',
            'type' => 'image',
            'crop' => true,
            'aspect_ratio' => 1,
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6'
            ],
        ]);

        $this->crud->addField([
            'name'  => 'birthdate', //database column name
            'label' => 'Birth Date', //Label
            'type'  => 'date',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-12'
            ],
        ]);

        $this->crud->addField([
            'name'  => 'email', //database column name
            'label' => 'Email', //Label
            'type'  => 'email',
        ]);

        /*
        |--------------------------------------------------------------------------
        | COLUMNS
        |--------------------------------------------------------------------------
        */

        // $this->crud->addColumn([
        //     'name' => 'photo',
        //     'label' => 'Photo',
        //     'type' => 'image'
        // ]);

        $this->crud->addColumn([
            'name' => 'fullname',
            'label' => 'Fullname',
            'type' => 'text'
        ]);

        $this->crud->addColumn([
            'name' => 'mobile',
            'label' => 'Mobile No.',
            'type' => 'phone'
        ]);

        $this->crud->addColumn([
            'name' => 'telephone',
            'label' => 'Telephone No.',
            'type' => 'phone'
        ]);

        $this->crud->addColumn([
            'name' => 'email',
            'label' => 'Email',
            'type' => 'email'
        ]);

        $this->crud->addColumn([
            'name' => 'gender',
            'label' => 'Gender',
            'type' => 'text'
        ]);

        $this->crud->addColumn([
            'name'  => 'status',
            'label' => 'Status',
            'type' => "model_function",
            'function_name' => 'getStatusWithBadge',
        ]);

        // add asterisk for fields that are required in ParentUserRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');

        $this->crud->allowAccess('verify');
        $this->crud->addButtonFromView('line', 'verify', 'parent.verify', 'beginning');
        $this->crud->addButtonFromView('line', 'Revoke', 'parent.portal', 'beginning'); 
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

    public function destroy($id)
    {
        $this->crud->hasAccessOrFail('delete');

        $deletedCredential = ParentCredential::where('parent_user_id', $id)->delete();
        return $this->crud->delete($id);
    }

    public function verifyAccount($id)
    {
        $parent  = ParentUser::find($id);

        if(! $parent) {
            \Alert::warning("Parent Not Found")->flash();
            return redirect()->back();
        }

        $parent->verified = 1;
        $parent->update();

        $credential = ParentCredential::where('parent_user_id', $parent->id)->first();

        if($credential) {
            $credential->active = 1;
            $credential->update();
        } else {
            $username   =   strtolower(substr($parent->firstname, 0, 1) . $parent->lastname);
            $password   =   Hash::make(strtolower('parent@' . config('settings.schoolabbr')));
            $credential =   ParentCredential::create([
                                'parent_user_id' => $parent->id,
                                'fullname'       => $parent->fullname,
                                'username'       => $parent->email,
                                'email'          => $parent->email,
                                'password'       => $password,
                                'active'         => 1
                            ]);
        }

        \Alert::success("The account has been successfully verified.")->flash();
        return redirect()->back();
    }

    public function portalAuthorization ($id, $action, Request $request)
    {
        // ENABLE PARENT PORTAL
        if ($action === 'enable') {
            $parent  = $this->crud->model::find($id);

            if(! $parent) {
                \Alert::warning("Parent Not Found")->flash();
                return redirect()->back();
            }

            $credential = ParentCredential::where('parent_user_id', $parent->id)->first();
            $username   = strtolower(substr($parent->firstname, 0, 1) . $parent->lastname);
            $password   = Hash::make(strtolower('parent@' . config('settings.schoolabbr')));

            if($credential) {
                $credential->active   = 1;
                $credential->password = $password;
                $credential->is_first_time_login = 1;
                $credential->update();

                \Alert::success("Parent Portal has been sucessfully activated.")->flash();
                return redirect()->back();
            }

            $credential =   ParentCredential::create([
                                'parent_user_id' => $parent->id,
                                'fullname'       => $parent->fullname,
                                'username'       => $parent->email,
                                'email'          => $parent->email,
                                'password'       => $password,
                                'active'         => 1,
                                'is_first_time_login' => 1
                            ]);

            \Alert::success("Parent Portal has been sucessfully activated.")->flash();
            return redirect()->back();
        }

        // DISABLE PARENT PORTAL
        if ($action === 'disable') {
            $parent      = $this->crud->model::find($id);

            if(! $parent) {
                \Alert::warning("Parent Not Found.")->flash();
                return redirect()->back();
            }

            $credential = ParentCredential::where('parent_user_id', $parent->id)->first();

            if(! $credential) {
                \Alert::warning("Parent Credential Not Found.")->flash();
                return redirect()->back();
            }

            $credential->active = 0;

            $credential->update() 
                ? \Alert::success("Parent access to portal has been sucessfully revoked.")->flash() 
                : \Alert::warning('Error Disabling Portal')->flash();

            return redirect()->back();
        }

        \Alert::error("Error, Something went wrong.")->flash();
        return redirect()->back();
    }
}
