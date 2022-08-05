<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\QuizRequest as StoreRequest;
use App\Http\Requests\QuizRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;

use Illuminate\Http\Request;
use App\Models\Questionnaire; 
use App\Models\SchoolYear;
use App\Models\Quiz;
use App\Models\OnlineClassQuiz;
use App\Models\StudentQuizResult;
use App\Models\TeacherSubject;

/**
 * Class QuizCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class QuizCrudController extends CrudController
{
    public function setup()
    {

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Quiz');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/quiz');
        $this->crud->setEntityNameStrings('quiz', 'quizzes');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        // $this->crud->setFromDb();

        // add asterisk for fields that are required in QuizRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');

        $this->crud->allowAccess('show');

        /*
        |--------------------------------------------------------------------------
        | USER AND LINK VALIDATION
        |--------------------------------------------------------------------------
        */
        if(!backpack_user()->hasRole('School Head')){

           $this->crud->addClause('where', 'user_id', backpack_auth()->user()->id);
               
        }
        /*
        |--------------------------------------------------------------------------
        | FIELDS
        |--------------------------------------------------------------------------
        */
        $this->crud->addField([
            'name' => 'user_id',
            'label' => 'User',
            'type' => 'hidden',
            'value' => backpack_auth()->user()->id
        ]);

        $this->crud->addField([
            'name' => 'teacher_id',
            'label' => 'Teacher',
            'type' => 'hidden',
            'value' => backpack_auth()->user()->employee_id
        ]);

         $this->crud->addField([
            'name' => 'school_year_id',
            'label' => 'School Year',
            'type' => 'hidden',
            'value' => SchoolYear::active()->first()->id
        ]);

         $this->crud->addField([  // Select2
            'label'     =>  "Subject",
            'type'      =>  'select2_from_array',
            'name'      =>  'subject_id', // the db column for the foreign key
            'options'   =>  TeacherSubject::where('teacher_id', backpack_auth()->user()->employee_id)
                                ->get()->pluck('code_subject_name', 'subject_id')
         ]);

        $this->crud->addField([
            'name' => 'title',
            'label' => 'Title',
            'type' => 'text',
        ]);

        $this->crud->addField([
            'name' => 'description',
            'label' => 'Description',
            'type' => 'textarea',
        ]);

        // $this->crud->addField([
        //     'name' => 'search',
        //     'type' => 'assessment.questions',
        //     'label' => 'Search'
        // ])->beforeField('student_no');


        /*
        |--------------------------------------------------------------------------
        | COLUMN DETAILS
        |--------------------------------------------------------------------------
        */

        $this->crud->addColumn([  // Select2
           'label' => "Subject",
           'type' => 'select',
           'name' => 'subject_id', // the db column for the foreign key
           'entity' => 'subject', // the method that defines the relationship in your Model
           'attribute' => 'subject_title', // foreign key attribute that is shown to user
           'model' => "App\Models\SubjectManagement" // foreign key model
        ]);

         $this->crud->addColumn([
            'name' => 'title',
            'label' => 'Title',
            'type' => 'text',
        ]);



        $this->crud->addColumn([
            'name' => 'description',
            'label' => 'Description',
            'type' => 'textarea',
        ]);
        $this->crud->setShowView('onlineClass.assessment.quiz.show');
        $this->crud->setCreateView('onlineClass.assessment.quiz.create');
        $this->crud->setEditView('onlineClass.assessment.quiz.edit');
    }

    public function show($id)
    {
        $content = parent::show($id);
        $questions = null;

        if(!backpack_user()->hasRole('School Head')){

            if(backpack_auth()->user()->id != $this->crud->entry->user_id)
            {
                abort(403);
            }
               
        }

        $this->crud->addColumn([  // Select2
           'label' => "Subject",
           'type' => 'select',
           'name' => 'subject_id', // the db column for the foreign key
           'entity' => 'subject', // the method that defines the relationship in your Model
           'attribute' => 'subject_title', // foreign key attribute that is shown to user
           'model' => "App\Models\TeacherSubject" // foreign key model
        ]);

         $this->crud->addColumn([
            'name' => 'title',
            'label' => 'Title',
            'type' => 'text',
        ]);

        $this->crud->addColumn([
            'name' => 'description',
            'label' => 'Description',
            'type' => 'textarea',
        ]);

        $questions = [];
        if($this->crud->getEntry($id)->questions)
        {
            if(count($this->crud->getEntry($id)->questions)>0)
            {
                foreach ($this->crud->getEntry($id)->questions as $key => $value) {
                    $questions[]  =    Questionnaire::with('subject')->where('id', $value)->first();
                }
            }
        }

        $this->data['questions'] = $questions;
        return view($this->crud->getShowView(), $this->data);
        // return $content;
    }

    public function edit ($id)
    {
        // get the info for that entry
        $this->data['entry']       = $this->crud->getEntry($id);
        $this->data['crud']        = $this->crud;
        $this->data['saveAction']  = $this->getSaveAction();
        $this->data['fields']      = $this->crud->getUpdateFields($id);
        $this->data['title']       = $this->crud->getTitle() ?? trans('backpack::crud.edit').' '.$this->crud->entity_name;
        $this->data['id']          = $id;

        if(!backpack_user()->hasRole('School Head')){

            if(backpack_auth()->user()->id != $this->data['entry']->user_id)
            {
                abort(403);
            }
               
        }
        
        // load the view from /resources/views/vendor/backpack/crud/ if it exists, otherwise load the one in the package
        return view($this->crud->getEditView(), $this->data);
    }

    public function store(StoreRequest $request)
    {
        $request->request->set('user_id', backpack_auth()->user()->id);
        $request->request->set('employee_id', backpack_auth()->user()->employee_id);
        // your additional operations before save here
        $redirect_location = parent::storeCrud($request);

        $id = $this->crud->entry->id;
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        // return $redirect_location;
        return redirect($this->crud->route.'/create/'.$id.'/questions');
    }

    public function update(UpdateRequest $request)
    {
        // your additional operations before save here
        $redirect_location = parent::updateCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }

    public function getUserQuizzes()
    {
        $quizzes = Quiz::where('user_id', backpack_auth()->user()->id)->get();
        return $quizzes;
    }

    public function getQuestions (Request $request) 
    {   
        // $questions  =    Questionnaire::join('subject_managements', function ($join) {
        //                                     $join->on('subject_managements.id', 'questionnaires.subject_id');
        //                                 })
        //                     ->with('subject')
        //                     ->select('questionnaires.*')
        //                     ->where('type', 'LIKE', '%' . $request->search . '%')
        //                     ->orWhere('question', 'LIKE', '%' . $request->search . '%')
        //                     ->orWhere('questionnaires.type', 'LIKE', '%' . $request->search . '%')
        //                     ->orWhere('questionnaires.description', 'LIKE', '%' . $request->search . '%')
        //                     ->orWhere('subject_managements.subject_title', 'LIKE', '%' . $request->search . '%')
        //                     ->orWhere('subject_managements.subject_code', 'LIKE', '%' . $request->search . '%')
        //                     ->paginate(5);
        // $questions->setPath(url()->current());
        // return response()->json($questions);
        return Quiz::where('id',$request->id)->first();
    }

    public function getQuizQuestions ($quiz_id) 
    {   
        $quiz  =    $this->crud->model::with('subject')->where('id', $quiz_id)->first();
        if($quiz){
            return response()->json($quiz);
        }
        return null;
    }

    public function createQuestions($id)
    {
        $this->data['entry'] = $this->crud->getEntry($id);
        $this->data['crud'] = $this->crud;
        $this->data['title'] = "Create Quiz";

        if(!backpack_user()->hasRole('School Head')){

            if(backpack_auth()->user()->id != $this->data['entry']->user_id)
            {
                abort(403);
            }
               
        }
        return view('onlineClass.assessment.quiz.quiz_questions_v2', $this->data);
    }

    public function getQuiz(Request $request)
    {
        $quiz = $this->crud->model::where('id', $request->id)->first();
        return response()->json($quiz);
    }

    public function addQuestion(Request $request)
    {
        $quiz = $this->crud->model::where('id', $request->quiz_id)->first();
        $question_id = [];
        $json = [];
        foreach (($request->questions) as $key => $value) {
            $question_id[] = $value['id'];
            $question = Questionnaire::where('id', $value['id'])->first();
            $json[] = json_decode($question->json);
        }
        $quiz->questions = $question_id;
        $quiz->json = $json;
        if($quiz->update())
        {
            $questions  =    Questionnaire::with('subject')
                            ->select('questionnaires.*')
                            ->whereIn('id', $question_id)
                            ->get();

            return response()->json($questions);
        }

    }

    // public function save(Request $request)
    // {
    //     dd(1);
    //     $response = [
    //                     'error' => false,
    //                     'message' => null,
    //                     'data' => null
    //                 ];
    //     // $quiz = $this->crud->model::where('id', $request->quiz_id)->first();
    //     // $quiz->json = json_decode($request->questions);
    //     // if($quiz->update())
    //     // {
    //     //     $response['title']   = 'Quiz Saved';
    //     //     $response['message'] = 'Quiz Saved Successfully.';
    //     //     return $response;
    //     // }
    //     // else{
    //     //     $response['error'] = true;
    //     //     $response['title']   = 'Error';
    //     //     $response['message'] = 'Error Updating, Something Went Wrong, Please Try To Reload The Page.';
    //     //     return $response;
    //     // }
        
    //     $count = $request->question_count;
    //     $choice_count = $request->choice_count;
    //     if($count != 0 ){

    //         for ($i = 0; $i < $count; $i++) {
    //             $concat_quiz_type = "quiz_type".'' . ($i+1);
    //             $concat_question = "question".'' . ($i+1);
    //             $concat_answer = "answer".'' . ($i+1);
    //             $concat_points = "points".'' . ($i+1);

    //             $quiz[] = [
    //                 'quiz_type' => $request-> $concat_quiz_type,
    //                 'question' => $request-> $concat_question,
    //                 'correct_answer' => $request-> $concat_answer,
    //                 'points' => $request-> $concat_points,
    //             ];
               
    //         }
    //         $getadded = $choice_count / 4;
           
    //         for ($a = 0; $a < $choice_count; $a++) {
    //             $concat_mutiple_choice = "choice".'' . ($a+1);
    //             $mutiple_choice[] = [
    //                 'id' => 1, //ID IN quiz_question_tbl
    //                 'mutiple_choice' => $request-> $concat_mutiple_choice
    //             ];
    //         }
    //             $response['title']   = 'Quiz Saved';
    //             $response['message'] = 'Quiz Saved Successfully.';
    //             return $response;
    //     }else{
    //             $response['error'] = true;
    //             $response['title']   = 'Error';
    //             $response['message'] = 'No Quiz Selected.';
    //             return $response;
    //     }
    // }

    public function questionSave(Request $request)
    {
       
        $quiz = Quiz::where('id',$request->quiz_id)
        ->update(['questions'=> json_encode($request->quiz),
        'isCorrect'=> json_encode($request->correctAnswers),
        'temp_answers'=> json_encode($request->template_answers),
        'duration' => $request->duration
        ]);
        
    }
    
    public function showQuestion(Request $request)
    {
        $quiz =  Quiz::where('id',$request->quiz_id)->first();
        $online_class_quiz = OnlineClassQuiz::where('quiz_id',$request->quiz_id)->first();
        
        if($quiz->questions == null){
            $respone = [
                'quiz' => $quiz,
                'question_null' => 1
            ];
        }else{
            if($online_class_quiz == null){
                $respone = [
                    'quiz' => $quiz
                ];
            }else{
                $student_quiz_results = StudentQuizResult::where('online_class_quiz_id',$online_class_quiz->id)->first();
                $respone = [
                    'quiz' => $quiz,
                    'student_quiz_results' => $student_quiz_results
                ];
            }
         
        }
       
        return $respone;
    }

    public function reportQuiz(Request $request)
    {    
        return view('onlineClass.quiz.generateReport');
    }
    
}
