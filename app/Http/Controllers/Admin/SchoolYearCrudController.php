<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\SchoolYearRequest as StoreRequest;
use App\Http\Requests\SchoolYearRequest as UpdateRequest;
use Illuminate\Http\Request;

use App\Models\SchoolYear;
use App\Models\Student;

class SchoolYearCrudController extends CrudController
{
    public function setup()
    {
        // $user = \Auth::user();
        // $permissions = collect($user->getAllPermissions());

        // $plucked = $permissions->pluck('name');
        // $this->allowed_method_access = $plucked->all();

        // $this->crud->denyAccess(['list', 'create', 'update', 'reorder', 'delete']);
        // $this->crud->allowAccess(['list', 'create', 'update', 'reorder', 'delete']);
        

        /*
        |--------------------------------------------------------------------------
        | BASIC CRUD INFORMATION
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\SchoolYear');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/schoolyear');
        $this->crud->setEntityNameStrings('schoolyear', 'School Year Management');

        /*
        |--------------------------------------------------------------------------
        | BASIC CRUD INFORMATION
        |--------------------------------------------------------------------------
        */


        // $this->crud->setFromDb();

        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');

        $this->crud->removeField('isActive', 'update/create/both');
        $this->crud->removeField('enable_enrollment', 'update/create/both');
        $this->crud->removeButton('edit');
        $this->crud->removeButton('delete');
        $this->crud->addButtonFromView('line', 'update', 'schoolYear.update', 'end');
        $this->crud->addButtonFromView('line', 'active', 'schoolYear.setActive', 'end');
        // $this->crud->addButtonFromView('line', 'Enable Enrollment', 'schoolYear.enableEnrollment', 'end');
        $this->crud->addButtonFromView('top', 'Sequence', 'schoolYear.sequence', 'ending');
        $this->crud->addButtonFromView('line', 'delete', 'schoolYear.delete', 'end');


        $this->crud->addColumn([
            'label'         => "School Year",
            'type'          => 'text',
            'name'          => 'schoolYear'
        ]);

        $this->crud->addColumn([
            'label'         => "Start Date",
            'type'          => 'date',
            'name'          => 'start_date'
        ]);

        $this->crud->addColumn([
            'label'         => "End Date",
            'type'          => 'date',
            'name'          => 'end_date'
        ]);

        $this->crud->addColumn([
            'label'         => "Active",
            'type'          => 'active',
            'name'          => 'isActive'
        ])->afterColumn('schoolyear');

        // $this->crud->addColumn([
        //     'label' => 'Enable Enrollment',
        //     'type'  => 'active',
        //     'name'  => 'enable_enrollment'
        // ]);

        $this->crud->addField([
            'label' => 'School Year <br><small style="font-weight: 100 !important;">(e.g. "2019 - 2020" , "2020 - 2021" ) </small>',
            'type'  => 'text',
            'name'  => 'schoolYear',
            'attributes'    => [
                'placeholder'  => 'ex. 2000-2001'
            ]
        ]);

        $this->crud->addField([
            'label' => 'Start Date <br><small style="font-weight: 100 !important;">Start of Academic Year (e.g. June 1, 2019)</small>',
            'type'  => 'date',
            'name'  => 'start_date',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6'
            ]
        ]);

        $this->crud->addField([
            'label' => 'End Date <br><small style="font-weight: 100 !important;">End of Academic Year (e.g. May 31, 2020) </small>',
            'type'  => 'date',
            'name'  => 'end_date',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6'
            ]
        ]);

        $this->crud->removeFields(['sequence']);
        
        $this->crud->orderBy('sequence');
        $this->crud->setDefaultPageLength(30);
        $this->crud->setCreateView('schoolYear.create');
        $this->crud->setListView('schoolYear.list');
        $this->crud->setEditView('crud.edit');

        // Setters
        $this->crud->setTitle('Add New School Year', 'create'); // set the Title for the create action
        $this->crud->setTitle('School Year Management', 'list'); // set the Title for the create action

        $this->crud->setHeading('Add New School Year', 'create'); // set the Heading for the create action
        $this->crud->setSubheading('Create school years for enrollment and allow early enrollments for the following term', 'create'); // set the Subheading for the create action
        $this->crud->setHeading('Update School Year', 'edit');
        $this->crud->setSubheading('Modify school year', 'edit');

    }


    public function resetActivation () {
        \DB::table('school_years')->update(['isActive' => 0, 'enable_enrollment' => 0]);
    }

    public function setActive ($id) 
    {
        $active_enrollment = SchoolYear::where('enable_enrollment', 1)->where('isActive', 1)->first();
        if($active_enrollment)
        {
            \Alert::warning("Deactivate Active Enrollment First.")->flash();
            return redirect()->back();
        }
        self::resetActivation();
        SchoolYear::where('id', $id)->update(['isActive' => 1]);
        \Alert::success("Successfully Activated School Year")->flash();
        return redirect()->back();
    }

    public function setDeactive ($id) 
    {
        $schoolYearID = SchoolYear::findOrFail($id);
        self::resetActivation();
        \Alert::success("Successfully Deactivated School Year")->flash();
        return redirect('admin/schoolyear');
    }

    public function enableEnrollment ($id) 
    {
        if(SchoolYear::where('id', $id)->where('isActive', 1)->first()) {
            self::resetActivation();
            SchoolYear::where('id', $id)->update(['enable_enrollment' => 1, 'isActive' => 1]);
            \Alert::success("Successfully Enable Enrollment")->flash();
            return redirect()->back();
        }

        \Alert::success("Error Enabling Enrollment, School Year Is Not Set As Active")->flash();
        return redirect()->back();
    }

    public function disableEnrollment ($id) 
    {
        $schoolYear= SchoolYear::where('id', $id)->where('isActive', 1)->first();
        if($schoolYear) {
            self::resetActivation();
            SchoolYear::where('id', $id)->update(['isActive' => 1]);
            \Alert::success("Successfully Disable Enrollment")->flash();
            return redirect('admin/schoolyear');
        }

        \Alert::success("Error Disabling Enrollment, School Year Is Not Set As Actives")->flash();
        return redirect('admin/schoolyear');
    }



    public function resequence ()
    {
        $items = $this->crud->model::orderBy('sequence', 'ASC')->get();
        $crud = $this->crud;

        $attribute = 'schoolYear';
        // dd($items);
        return view('resequence', compact('items', 'crud', 'attribute'));
    }
    
    public function saveSequence (Request $request) 
    {   
        $ids = json_decode($request->sequence); 
        $array = [];
        $flagError = false;
        for($i = 0; $i < count($ids); $i++) {
            $model = $this->crud->model::find($ids[$i]);
            $model->sequence = $i + 1;
            if(!$model->save()) {
                $flagError = true;
            }
        }

        if($flagError) {
            \Alert::success("Error Saving")->flash();
            return redirect()->back()->withInput($request->input());
        }

        \Alert::success("Succesfully Update")->flash();
        return redirect()->to($this->crud->route);
        // return redirect()->back()->withInput($request->input());
    }

    public function store(StoreRequest $request)
    {
        // GET THE MODEL 
        $sy = $this->crud->model::latest()->first();

        // CHECK IF NOT NULL SCHOOL YEAR SEQUENCE
        if($sy !== null)
        {
            if($sy->sequence !== null)
            {
                $sy;
                $request->request->set('sequence', $sy->sequence + 1);
            }
        } else {
            $request->request->set('sequence', 1);
        }

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
        $response = ['error' => true, 'message' => 'Unknown Error.', 'data' => null];

        if(!$this->crud->hasAccess('delete')) {
            $response['message'] = 'Unauthorized Access.';
            return $response;
        }

        // Check Password Is Set
        if(!isset(request()->password)) {
            $response['message'] = 'Password is required.';
            return $response;
        }

        // Check If User Has Role Of Administrator
        if(!backpack_auth()->user()->hasRole('Administrator')) {
            $response['message'] = 'You dont have permission to delete only admin can delete.';
            return $response;
        }

        // Check If Password Is Incorrect
        if(!\Hash::check(request()->password, backpack_auth()->user()->password)) {
            $response['message'] = 'Incorrect Password.';
            return $response;
        } 

        $model = $this->crud->model::where('id', $id)->exists();

        if($model) {
            activity()
           ->performedOn(SchoolYear::where('id', $id)->first())
           // ->withProperties(['customProperty' => 'customValue'])
           ->log('deleted');

            $deleted = $this->crud->model::where('id', $id)->delete();
            Student::where('schoolyear', $id)->delete();

            $response['error'] = false;
            $response['message'] = 'The item has been deleted successfully.';
           

        } else {
            $response['message'] = 'Item Not Found.';
        }
        
        return $response;
    }
}
