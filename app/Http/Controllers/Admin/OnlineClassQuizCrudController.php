<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\OnlineClassQuizRequest as StoreRequest;
use App\Http\Requests\OnlineClassQuizRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;

use App\Models\OnlineClassQuiz;
use App\Models\OnlineClassAttendance;
use App\Models\OnlineClass;
use App\Models\Student;
use App\Models\Quiz;

/**
 * Class OnlineClassQuizCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class OnlineClassQuizCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | Check If User is Tag as Employee
        |--------------------------------------------------------------------------
        */
        if(backpack_auth()->user()->employee_id === null) {
            abort(403, 'Your User Account Is Not Yet Tag As Employee');
        }
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\OnlineClassQuiz');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/online-class/quiz');
        $this->crud->setEntityNameStrings('Class Quiz', 'Class Quizzes');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        // $this->crud->setFromDb();

        // add asterisk for fields that are required in OnlineClassQuizRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
        $this->crud->allowAccess('show');

        $this->data['user']        =   backpack_auth()->user();

        if(!request()->class_code)
        {
            $my_classes     =   $this->getMyClasses();
            $this->data['my_classes']   =   $my_classes;
            $this->data['classQuizzes']  =   $this->crud->model::with('onlineClass', 'quiz')->whereIn('online_class_id',collect($my_classes)->pluck('id'))->get();
            $this->crud->setListView('onlineClass.quiz.list');
        }
        else
        {
            $class = $this->getClass(request()->class_code);
            $this->data['class']            =   $class;
            $this->data['classQuizzes']     =   $this->crud->model::with('quiz')->where('online_class_id', $class->id)->get();
            $this->data['class_attendance'] =   OnlineClassAttendance::getEmployeeAttendanceToday($class->id, backpack_user()->employee_id);

            // dd( $this->data['quizzes']);
            $this->crud->setListView('onlineClass.quiz.class-quizzes');
            $this->crud->setCreateView('onlineClass.quiz.class-create');
            $this->crud->setShowView('onlineClass.quiz.show');
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

    public function show($id)
    {
        $content                =   parent::show($id);
        $entry                  =   $content->getData()['entry']->with('onlineClass', 'quiz')->findOrFail($id);
        $content->with('entry', $entry);
        if(!$entry)
        {
            abort(404);
        }
        if(!$entry->onlineClass)
        {
            abort(404, "Class Not Found.");
        }
        if(!backpack_user()->hasRole('School Head'))
        {
            if($entry->onlineClass->code != request()->class_code)
            {
                abort(400, 'Mismatch Class.');
            }
        }

        return $content;
    }

    public function getMyClasses()
    {
        $classes = [];
        if(backpack_auth()->user()->hasRole('School Head')){
            $classes    =   OnlineClass::with([
                                    'section', 
                                    'teacher', 
                                    'subject',
                                    'course',
                                    'activeStudentSectionAssignment'
                                ])
                                ->orderBy('online_classes.name')
                                ->activeSchoolYear()
                                ->notArchive()
                                ->active()
                                ->get();
            return $classes;
        }
        else if(backpack_user()->hasRole('Teacher')){
            $classes    =   OnlineClass::with([
                                    'section', 
                                    'teacher', 
                                    'subject',
                                    'course',
                                    'activeStudentSectionAssignment'
                                ])
                                ->orderBy('online_classes.name')
                                ->where('teacher_id', backpack_auth()->user()->employee_id)
                                ->activeSchoolYear()
                                ->notArchive()
                                ->active()
                                ->get();
            return $classes;
        }
        return $classes;
    }

    public function getClass($class_code)
    {
        $class  =   OnlineClass::with([
                            'subject',
                            'section',
                            'teacher',
                            'activeStudentSectionAssignment'
                        ])
                        ->where('code', $class_code)
                        ->activeSchoolYear()
                        ->notArchive()
                        ->active()
                        ->first();

        if(!$class)
        { 
            \Alert::warning("Error, Class Code not Found.")->flash();
            abort(403, 'Class Code not found.'); 
        }

        // Check If Employee ID is Equal To Class Employee ID
        if(!backpack_user()->hasRole('School Head') && $class->teacher_id != backpack_auth()->user()->employee_id)
        {
            $class->isTeacherSubstitute   = 1;
            
            abort_if( !$class->substitute_teachers, 403, 'Unauthorized access.' );
            abort_if( !count($class->substitute_teachers) > 0, 403, 'Unauthorized access.' );
            abort_if( !in_array(backpack_auth()->user()->employee_id, $class->substitute_teachers), 403, 'Unauthorized access.' );
        }
        
        return $class;
    }

    public function getResults($class_quiz_id)
    {
        if(!request()->class_code)
        {
            abort(400, 'Missing Parameters.');
        }

        $classQuiz = OnlineClassQuiz::with('quiz', 'onlineClass')->findOrFail($class_quiz_id);

        if($this->data['class']->studentSectionAssignment) {
            $studentnumbers     =   json_decode($this->data['class']->studentSectionAssignment->students);
        }
        else {
            $studentnumbers = collect([]);
        }

        $students           =   Student::whereIn('studentnumber', $studentnumbers)->with(['submittedQuizzes' => function($query) use ($class_quiz_id){
                                    $query->where('online_class_quiz_id', $class_quiz_id);
                                    $query->orderBy('score', 'DESC');
                                }])->get();

        // $hubert             =   Student::where('studentnumber', 190828)->with('submittedQuizzes', 'submittedQuizzes.classQuiz', 'submittedQuizzes.classQuiz.Quiz')->first();
        // $hubertClassQuiz    =   $hubert->submittedQuizzes->where('online_class_quiz_id', $class_quiz_id)->first();
        // dd($hubertClassQuiz->classQuiz->quiz->total_score);
        // dd($hubertClassQuiz->where('online_class_quiz_id', $class_quiz_id)->first());
        $this->data['crud']         =   $this->crud;
        $this->data['quiz']         =   $classQuiz->quiz;
        $this->data['classQuiz']         =   $classQuiz;
        $this->data['students']     =   $students;

        return view('onlineClass.quiz.class-quiz-results', $this->data);
    }
    public function print($id){

        $quiz            = Quiz::where('id',$id)->first();
        $schoollogo      = config('settings.schoollogo') ? (string)\Image::make(config('settings.schoollogo'))->encode('data-url') : null;
        $schoolmate_logo = (string)\Image::make('images/schoolmate_logo.jpg')->encode('data-url');
        return view('onlineClass.quiz.generateReport',compact('schoollogo','schoolmate_logo','quiz'));
    }
}
