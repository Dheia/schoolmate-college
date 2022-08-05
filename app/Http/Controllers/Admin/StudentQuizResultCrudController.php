<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\StudentQuizResultRequest as StoreRequest;
use App\Http\Requests\StudentQuizResultRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;

use Illuminate\Http\Request;
use App\Models\StudentQuizResult;
use App\Models\Quiz;

use App\Models\OnlineClassAttendance;

/**
 * Class StudentQuizResultCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class StudentQuizResultCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\StudentQuizResult');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/online-class/student-quiz-result');
        $this->crud->setEntityNameStrings('Student Quiz Result', 'Student Quiz Result');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        $this->crud->setFromDb();

        $this->crud->allowAccess('show');
        $this->crud->denyAccess('create');
        $this->crud->denyAccess('update');
        $this->crud->denyAccess('delete');

        // add asterisk for fields that are required in StudentQuizResultRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');

        $this->crud->setShowView('onlineClass.quiz.student-submitted-quiz');
    }

    public function show($id)
    {
        $content            =   parent::show($id);
        $entry              =   $content->getData()['entry']->with('student', 'classQuiz', 'classQuiz.onlineClass', 'classQuiz.quiz', 'classQuiz.onlineClass.subject', 'classQuiz.onlineClass.teacher')->findOrFail($id);
        $content->with('entry', $entry);

        if(!$entry->student)
        {
            abort(400, 'Student not found.');
        }
        if(!$entry->classQuiz)
        {
            abort(400, 'Class Quiz not found.');
        }
        if(!$entry->classQuiz->onlineClass)
        {
            abort(400, 'Class not found.');
        }
        if(!$entry->classQuiz->quiz)
        {
            abort(400, 'Quiz not found.');
        }
        
        if(!backpack_user()->hasRole('School Head'))
        {
            if($entry->classQuiz->onlineClass->teacher_id != backpack_auth()->user()->employee_id)
            {
                 abort(403, 'Unauthorized access - you dont have the necessary permissions to see this page.');
            }
        }

        $class_attendance =   OnlineClassAttendance::getEmployeeAttendanceToday($entry->classQuiz->onlineClass->id, backpack_user()->employee_id);

        $content->with('classQuiz', $entry->classQuiz);
        $content->with('class', $entry->classQuiz->onlineClass);
        $content->with('quiz', $entry->classQuiz->quiz);
        $content->with('student', $entry->student);
        $content->with('class_attendance', $class_attendance);
        $content->with('user', backpack_user());
        
        return $content;
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

    public function getResult($id)
    {
        $studentQuizResult  =    $this->crud->model::with('classQuiz', 'classQuiz.quiz')->where('id', $id)->first();
        if($studentQuizResult){
            return response()->json($studentQuizResult);
        }
        return null;
    }

    public function submitScore($id, Request $request)
    {
        $response = [
            'error' => true, 
            'title' => 'Checking failed',
            'message' => 'The item could not be checked. Please try again.', 
            'data' => null
        ];

        $studentQuizResult = StudentQuizResult::findOrFail($id);
        $studentQuizResult->final_score = $request->score;
        $studentQuizResult->is_check    = 1;

        if($studentQuizResult->update()) {
            $studentQuizResult = StudentQuizResult::findOrFail($id);
            $response['error']   = false;
            $response['title']   = 'Item checked';
            $response['message'] = 'The item has been checked.';
            $response['data']    = $studentQuizResult;
        }
        return $response;
    }

    public function getQuestions(Request $request)
    {
       
        $student_quiz_results = StudentQuizResult::where('id',$request->id)->first();
        $quiz = Quiz::where('id',$request->quiz_id)->first();
        $respone = [
            'quiz' => $quiz,
            'student_quiz_result' => $student_quiz_results
        ];
        return $respone;
    }

    
    public function submitfinalScore(Request $request)
    {
        $response = [
            'error' => false,
            'message' => null,
            'data' => null
        ];
        $total_final_score = 0;
        foreach($request->final_scores as $final_scores){
            $total_final_score += $final_scores['score'];
        }
        $student_quiz_results = StudentQuizResult::where('id',$request->student_result_quiz_id)
        ->update([
            'final_score'=>json_encode($request->final_scores),
            'score'=>$total_final_score,
            'is_check'=>1
        ]);

        $response['title']   = 'Quiz Saved';
        $response['message'] = 'Quiz Saved Successfully.';
        return $response;
    }
}
