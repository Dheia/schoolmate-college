<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\QuipperStudentAccountRequest as StoreRequest;
use App\Http\Requests\QuipperStudentAccountRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;
use Illuminate\Http\Request;

use App\Models\QuipperStudentAccount;
use App\Models\Student;

/**
 * Class QuipperStudentAccountCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class QuipperStudentAccountCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\QuipperStudentAccount');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/quipper-student-account');
        $this->crud->setEntityNameStrings('Quipper Account', 'Quipper Accounts');

        $this->data['quipperAccounts'] = QuipperStudentAccount::get()->pluck('student_id');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        $this->crud->setFromDb();

        /*
        |--------------------------------------------------------------------------
        | Fields
        |--------------------------------------------------------------------------
        */
        // $this->crud->addField([  // Select
        //    'label' => "Student",
        //    'type' => 'select2',
        //    'name' => 'student_id', // the db column for the foreign key
        //    'entity' => 'student', // the method that defines the relationship in your Model
        //    'attribute' => 'fullname', // foreign key attribute that is shown to user
        //    'model' => "App\Models\Student", // foreign key model
        //    'options'   => (function ($query) {
        //         return $query->whereNotIn('id', QuipperStudentAccount::get()->pluck('student_id'))->get();
        //     }), 
        // ])->beforeField('membership_number');
        $this->crud->addField([
            'label'         => 'Student ID',
            'name'          => 'student_id',
            'type'          => 'hidden',
            'attributes'    => [ 'id' => 'student_id' ]
        ]);

        $this->crud->addField([
            'label'         => 'Student Number',
            'name'          => 'studentnumber',
            'type'          => 'hidden',
            'attributes'    => [ 'id' => 'studentNumber' ]
        ]);

        $this->crud->addField([
            'name' => 'searchStudent',
            'type' => 'quipperAccount.searchStudent',
            'label' => '',
            'attributes' => [
                'id' => 'searchInput',
                'placeholder' => 'Search For Name or ID Number (ex. 1100224)',
            ],
        ])->beforeField('membership_number');

        $this->crud->addField([
            'label'         => 'Password',
            'name'          => 'password',
            'type'          => 'text'        
        ]);

        $this->crud->addColumn([  // Select
           'label' => "Fullname",
           'type' => 'select',
           'name' => 'student_id', // the db column for the foreign key
           'entity' => 'student', // the method that defines the relationship in your Model
           'attribute' => 'fullname', // foreign key attribute that is shown to user
           'model' => "App\Models\Student", // foreign key model
           'searchLogic' => function ($query, $column, $searchTerm) {
                $query->orWhereHas('student', function ($q) use ($column, $searchTerm) {
                    $q->where('firstname', 'like', '%'.$searchTerm.'%')
                        ->orWhere('lastname', 'like', '%'.$searchTerm.'%');
                });
            },
        ])->beforeColumn('membership_number');

        $this->crud->addColumn([  // Select
           'label' => "Student Number",
           'type' => 'select',
           'name' => 'studentnumber', // the db column for the foreign key
           'entity' => 'student', // the method that defines the relationship in your Model
           'attribute' => 'studentnumber', // foreign key attribute that is shown to user
           'model' => "App\Models\Student", // foreign key model
           'searchLogic' => function ($query, $column, $searchTerm) {
                $query->orWhereHas('student', function ($q) use ($column, $searchTerm) {
                    $q->where('studentnumber', 'like', '%'.$searchTerm.'%');
                });
            },
        ])->beforeColumn('student_id');

        $this->crud->removeField('user_id');
        $this->crud->removeColumn('user_id');

        // add asterisk for fields that are required in QuipperStudentAccountRequest
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

    public function searchStudent(Request $request)
    {
        $students           = Student::where('studentnumber', 'LIKE', '%' . $request->search . '%')
                                    ->orWhere('firstname', 'LIKE', '%' . $request->search . '%')
                                    ->orWhere('middlename', 'LIKE', '%' . $request->search . '%')
                                    ->orWhere('lastname', 'LIKE', '%' . $request->search . '%')
                                    ->paginate(5);
        $students->setPath(url()->current());
        return response()->json($students);
    }
}
