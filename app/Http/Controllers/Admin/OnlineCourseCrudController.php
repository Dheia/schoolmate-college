<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\OnlineCourseRequest as StoreRequest;
use App\Http\Requests\OnlineCourseRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;

// MODELS
use App\Models\User;
use App\Models\Employee;
use App\Models\OnlineClass;
use App\Models\YearManagement;
use App\Models\Department;
use App\Models\OnlineCourse;

/**
 * Class OnlineCourseCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class OnlineCourseCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\OnlineCourse');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/online-course');
        $this->crud->setEntityNameStrings('Online Course', 'my courses');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        // $this->crud->setFromDb();

        // add asterisk for fields that are required in OnlineCourseRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');

        /*
        |--------------------------------------------------------------------------
        | Get The Teacher Information (teacher_id: required)
        |--------------------------------------------------------------------------
        */

        if(!backpack_auth()->user()->employee_id) {
            // \Alert::warning("Missing Required Parameters!")->flash();
            // abort(403);
             abort(403, 'Your User Account Is Not Yet Tag As Employee');
        } else {
            
            if(backpack_auth()->user()->employee_id === null) {
                abort(403, 'Your User Account Is Not Yet Tag As Employee');
            }
            $this->data['teacher'] = User::where('employee_id', backpack_auth()->user()->employee_id)->first();

            if($this->data['teacher'] === null) {
                abort(403, 'User Not Found');
            }
        }

        $course_tag = $this->getCourseTag();

        if(request()->course_code)
        {
            $this->data['selected_course'] = $this->getCourse(request()->course_code);
            if(!$this->data['selected_course'])
            {
                abort(403, 'Mismatch Course');
            }
            if(request()->class_code){
                $class = OnlineClass::where('code', request()->class_code)->first();
                if(!$class){
                    abort(403, 'Mismatch Class');
                }
                elseif($class->online_course_id != $this->data['selected_course']->id){
                    abort(403, 'Mismatch Class and Course');
                }
            }
            else{
                if(backpack_auth()->user()->employee_id != $this->data['selected_course']->teacher_id){
                    
                    if( count($course_tag) == 0)
                    {
                        abort(403, 'Mismatch User and Course'); 
                    }
                    else if(!$course_tag->where('online_course_id', $this->data['selected_course']->id)->first()){
                        abort(403, 'Mismatch User and Course');
                    }
                    
                }
            }
            $this->crud->setListView('onlineClass.onlineCourse.show');
            $this->crud->setCreateView('onlineClass.onlineCourse.create');

            $online_course_id = \Route::current()->parameter('online_course');
            if($online_course_id)
            {
               if($online_course_id != $this->data['selected_course']->id)
               {
                    abort(403, 'Mismatch Course');
               }
            }
            if(backpack_user()->hasRole('School Head') || backpack_auth()->user()->employee_id == $this->data['selected_course']->teacher_id)
            {
                $this->crud->setEditView('onlineClass.onlineCourse.edit');
            }
            else
            {
                $this->crud->setEditView('onlineClass.onlineCourse.dashboard');
                if(count($course_tag) == 0)
                {
                    abort(403, 'Mismatch User and Course');
                }
                else if(!$course_tag->where('online_course_id', $this->data['selected_course']->id)->first()){
                    abort(403, 'Mismatch User and Course');
                }
            }
        }
        else
        {
            $this->crud->setListView('onlineClass.onlineCourse.dashboard');
            $this->crud->setCreateView('onlineClass.onlineCourse.create');
            $this->crud->setEditView('onlineClass.onlineCourse.dashboard');
        }


         /*
        |--------------------------------------------------------------------------
        | FIELDS
        |--------------------------------------------------------------------------
        */
        $this->crud->removeFields(['teacher_id']);
        if(request()->course_code){
            $this->data['code'] = request()->course_code;
        }
        else{
            $this->data['code'] = substr(md5(uniqid(mt_rand(), true)) , 0, 7);
        }
        

        $this->crud->addField([
            'label' => 'Teacher',
            'type'  => 'hidden',
            'name'  => 'teacher_id',
            'value' => backpack_auth()->user()->employee_id
        ]);
        
         $this->crud->addField([
            'label' => 'Level',
            'type' => 'select_from_array',
            'name' => 'level_id',
            'options'   => YearManagement::whereIn('department_id', Department::active()->pluck('id'))
                            ->pluck('year', 'id'),
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6'
            ]
        ]);

        $this->crud->addField([  // Select
            'name'      => 'subject_id',
            'label'     => "Subject",
            'type'      => 'select2', // the db column for the foreign key
            'entity'    => 'subject', // the method that defines the relationship in your Model
            'attribute' => 'subject_title', // foreign key attribute that is shown to user
            'model'     => "App\Models\SubjectManagement", // foreign key model
            'allows_null' => false,
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6'
            ]
        ]);

        $this->crud->addField([
            'label' => 'Code',
            'type' => 'hidden',
            'name' => 'code',
            'value' => $this->data['code']
        ]);

        $this->crud->addField([
            'label' => 'Name',
            'type' => 'text',
            'name' => 'name'
        ])->beforeField('name');

        $this->crud->addField([
            'label' => 'Description',
            'type' => 'ckeditor',
            'name' => 'description'
        ]);

        $this->crud->addField([
            'label' => 'Requirements',
            'type' => 'ckeditor',
            'name' => 'requirements'
        ]);

        $this->crud->addField([
            'label' => 'Content Standard',
            'type' => 'ckeditor',
            'name' => 'content_standard'
        ]);

        $this->crud->addField([
            'label' => 'Performance Standard',
            'type' => 'ckeditor',
            'name' => 'performance_standard'
        ]);

        $this->crud->addField([
            'label' => 'Duration',
            'type' => 'text',
            'name' => 'duration',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-3'
            ]
        ]);

        // Field For Online Class
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

        $this->crud->addField([       // Select2Multiple = n-n relationship (with pivot table)
            'label' => "Share To",
            'type' => 'select2_multiple',
            'name' => 'share', // the method that defines the relationship in your Model
            'entity' => 'share', // the method that defines the relationship in your Model
            'attribute' => 'full_name', // foreign key attribute that is shown to user
            'model' => "App\Models\Employee", // foreign key model
            'pivot' => true, // on create&update, do you need to add/delete pivot table entries?
            'select_all' => true // show Select All and Clear buttons?
        ]);

        if(backpack_auth()->user()->hasRole('School Head')){
            $this->data['my_courses']   =   $this->crud->model::with('teacher')
                                                ->notArchive()
                                                ->active()
                                                ->get();
        }
        else if(backpack_user()->hasRole('Teacher')){

            $this->data['my_courses']   =   $this->crud->model::with('teacher')
                                                ->where('teacher_id', backpack_auth()->user()->employee_id)
                                                ->orWhereIn('id', $course_tag->pluck('online_course_id'))
                                                ->notArchive()
                                                ->active()
                                                ->get();
        }
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

        $course = OnlineCourse::findOrFail($id);
        if(!backpack_user()->hasRole('School Head'))
        {
            if($course->teacher_id != backpack_auth()->user()->employee_id){
                \Alert::warning("Unauthorized Access.")->flash();
                return redirect()->back();
            }
        }
        $this->crud->delete($id);

        \Alert::success("Successfully Deleted.")->flash();
        return redirect()->back();    
    }

    public function getCourse($course_code)
    {
        $course  =  $this->crud->model::with('subject', 'level', 'teacher', 'modules', 'modules.topics', 'share')
                            ->where('code', $course_code)
                            ->first();
        return $course;
    }

    public function getCourseTag()
    {
        $course_tag     =   $this->crud->model::with('teacher', 'share')
                                ->join('online_course_teacher', function ($join) {
                                    $join->on('online_course_teacher.online_course_id', '=', 'online_courses.id')
                                         ->where('online_course_teacher.teacher_id', '=',  backpack_auth()->user()->employee_id);
                                })
                                ->notArchive()
                                ->active()
                                ->get();
        return $course_tag;
    }
}
