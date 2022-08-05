<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\UserRequest as StoreRequest;
use App\Http\Requests\UpdateUserRequest as UpdateRequest;
use Illuminate\Http\Request;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
/**
 * Class UserCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class UserCrudController extends CrudController
{
    public function setup()
    {
        $this->crud->setDefaultPageLength(10);
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel(config('backpack.base.user_model_fqn'));
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/user');
        $this->crud->setEntityNameStrings('user', 'users');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        $this->crud->setFromDb();

        // add asterisk for fields that are required in UserRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');

         // Columns.
        $this->crud->setColumns([
            [
                'name'  => 'name',
                'label' => trans('backpack::permissionmanager.name'),
                'type'  => 'text',
            ],
            [
                'name'  => 'email',
                'label' => trans('backpack::permissionmanager.email'),
                'type'  => 'email',
            ],
            [ // n-n relationship (with pivot table)
               'label'     => trans('backpack::permissionmanager.roles'), // Table column heading
               'type'      => 'select_multiple',
               'name'      => 'roles', // the method that defines the relationship in your Model
               'entity'    => 'roles', // the method that defines the relationship in your Model
               'attribute' => 'name', // foreign key attribute that is shown to user
               'model'     => config('permission.models.role'), // foreign key model
            ],
            [ // n-n relationship (with pivot table)
               'label'     => trans('backpack::permissionmanager.extra_permissions'), // Table column heading
               'type'      => 'select_multiple',
               'name'      => 'permissions', // the method that defines the relationship in your Model
               'entity'    => 'permissions', // the method that defines the relationship in your Model
               'attribute' => 'name', // foreign key attribute that is shown to user
               'model'     => config('permission.models.permission'), // foreign key model
            ],
        ]);

        // Fields
        $this->crud->addFields([
            [
                'name'  => 'name',
                'label' => trans('backpack::permissionmanager.name'),
                'type'  => 'text',
            ],
            [
                'name'  => 'email',
                'label' => trans('backpack::permissionmanager.email'),
                'type'  => 'email',
            ],
            [
                'name'  => 'password',
                'label' => trans('backpack::permissionmanager.password'),
                'type'  => 'password',
            ],
            [
                'name'  => 'password_confirmation',
                'label' => trans('backpack::permissionmanager.password_confirmation'),
                'type'  => 'password',
            ],
            [
            // two interconnected entities
            'label'             => trans('backpack::permissionmanager.user_role_permission'),
            'field_unique_name' => 'user_role_permission',
            'type'              => 'checklist_dependency_custom',
            'name'              => 'roles_and_permissions', // the methods that defines the relationship in your Model
            'subfields'         => [
                    'primary' => [
                        'label'            => trans('backpack::permissionmanager.roles'),
                        'name'             => 'roles', // the method that defines the relationship in your Model
                        'entity'           => 'roles', // the method that defines the relationship in your Model
                        'entity_secondary' => 'permissions', // the method that defines the relationship in your Model
                        'attribute'        => 'name', // foreign key attribute that is shown to user
                        'model'            => config('permission.models.role'), // foreign key model
                        'pivot'            => true, // on create&update, do you need to add/delete pivot table entries?]
                        'number_columns'   => 3, //can be 1,2,3,4,6
                    ],
                    'secondary' => [
                        'label'          => ucfirst(trans('backpack::permissionmanager.permission_singular')),
                        'name'           => 'permissions', // the method that defines the relationship in your Model
                        'entity'         => 'permissions', // the method that defines the relationship in your Model
                        'entity_primary' => 'roles', // the method that defines the relationship in your Model
                        'attribute'      => 'name', // foreign key attribute that is shown to user
                        'model'          => config('permission.models.permission'), // foreign key model
                        'pivot'          => true, // on create&update, do you need to add/delete pivot table entries?]
                        'number_columns' => 4, //can be 1,2,3,4,6
                    ],
                ],
            ],
        ]);

        $this->crud->addField([
            'name' => 'employee_id',
            'type' => 'select',
            'label' => 'Tag Employee',
            'attribute' => 'fullname',
            'entity' => 'employee',
            'model' => 'App\Models\Employee'
        ]);

        $this->crud->addColumn([
            'label' => 'Assigned to',
            'type' => 'text',
            'name' => 'full_name',
        ]);

        if($this->crud->getActionMethod() === "edit") {
            $this->crud->addField([
                'label' => 'email',
                'type' => 'email',
                'name'  => 'email',
                'attributes' => [
                    'readonly' => true
                ]   
            ]);
            $this->crud->removeField(['password', 'password_confirmation']);
        }

        $this->crud->removeColumn(['extra_permissions']);


        if(env('APP_DEBUG') == false) {
            $pathRoute = str_replace("admin/", "", $this->crud->route);
            $user = backpack_auth()->user();

            $permissions = collect($user->getAllPermissions());
            $allowed_method_access = $user->getAllPermissions()->pluck('name')->toArray();

            foreach ($permissions as $permission) {
                if($permission->page_name == $pathRoute) {
                    $methodName = strtolower( explode(' ', $permission->name)[0] );
                    array_push($allowed_method_access, $methodName);
                }
            }

            $this->crud->denyAccess(['list', 'create', 'update', 'delete', 'clone', 'show']);
            $this->crud->allowAccess($allowed_method_access);
        }

        $this->crud->allowAccess('reset-password');
        $this->crud->addButtonFromView('line', 'reset password', 'user.reset_password', 'beginning');
    }

    /**
     * Store a newly created resource in the database.
     *
     * @param StoreRequest $request - type injection used for validation using Requests
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreRequest $request)
    {
        $this->handlePasswordInput($request);

        return parent::storeCrud($request);
    }

    /**
     * Update the specified resource in the database.
     *
     * @param UpdateRequest $request - type injection used for validation using Requests
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateRequest $request)
    {
        $this->handlePasswordInput($request);

        return parent::updateCrud($request);
    }

    public function destroy ($id)
    {
        $model = $this->crud->model::findOrFail($id);
        $model->delete();
    }

    /**
     * Handle password input fields.
     *
     * @param Request $request
     */
    protected function handlePasswordInput(Request $request)
    {
        // dd($request);
        // Remove fields not present on the user.
        $request->request->remove('password_confirmation');

        // Encrypt password if specified.
        if ($request->input('password')) {
            $request->request->set('password', bcrypt($request->input('password')));
        } else {
            $request->request->remove('password');
        }
    }

    public function resetPassword($id)
    {
        $user       =   User::findOrFail($id);
        $employee   =   $user->employee;

        if(!$employee) {
            \Alert::warning("Employee Does Not Exists")->flash();
            abort(404, 'User Account Is Not Yet Tag As Employee');
        }

        // $password   =   Hash::make(strtolower(date("FjY", strtotime($employee->date_of_birth))));
        $password   =   Hash::make(strtolower(config('settings.schoolabbr') . '@password'));
        $user->password          =   $password;
        $user->first_time_login  =   0;

        $user->update();

        \Alert::success("Password Reset <br> The password has been reset successfully.")->flash();
        return redirect()->back();
    }

    public function unreadNotifications()
    {
        $user = \App\User::where('id', backpack_user()->id)->first();
        $notifications = $user->unreadNotifications()->paginate(10);

        return response()->json($notifications);
    }
}
