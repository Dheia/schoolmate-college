<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Controllers\Student\OnlineClassController;

use App\Models\Quiz;
use App\Models\OnlineClassQuiz;
use App\Models\OnlineClassAttendance;
use App\Models\QuipperStudentAccount;
use App\Models\StudentSubmittedAssignment;
use App\Models\StudentQuizResult;
use Carbon\Carbon;

class OnlineClassQuizController extends Controller
{
    public function showQuizzes()
    {
       	$onlineClassController  =   new OnlineClassController();

	    $student                =   auth()->user()->student;
	    $student_section        =   $onlineClassController->studentSectionAssignment();
	    $my_classes             =   $onlineClassController->getOnlineClasses();
	    $quipperAccount         =   QuipperStudentAccount::where('student_id', $student->id)->first();

	    $classQuizzes            	=   OnlineClassQuiz::with('onlineClass', 'quiz')
	                                    ->whereIn('online_class_id', $my_classes ? $my_classes->pluck('id') : [])
	                                    ->orderBy('start_at', 'DESC')
	                                    ->get();

        // dd($quizzes);
        $this->data['my_classes'] 	= 	$my_classes;
        $this->data['classQuizzes'] = 	$classQuizzes;
        $this->data['student']      =   $student;
        $this->data['user']         =   $student;

        return view('student.quiz.list', $this->data);
    }

    public function showClassQuizzes($class_code)
    {
    	$onlineClassController  =   new OnlineClassController();

        $student                =   auth()->user()->student;
        $student_section        =   $onlineClassController->studentSectionAssignment();
        $class                  =   $onlineClassController->getOnlineClass($class_code);

        $section_ids            =   collect($student_section)->pluck('section_id')->toArray();
        $quipperAccount         =   QuipperStudentAccount::where('student_id', $student->id)->first();

        if(!$class) {
            abort(403, 'Mismatch Class');
        }
        if(!in_array($class->section_id, $section_ids)){
            abort(403, 'Mismatch Class');
        }
        if(!in_array($class->term_type, collect($student_section)->pluck('term_type')->toArray())){
            abort(403, 'Mismatch Class');
        }
        if(!in_array($class->summer, collect($student_section)->pluck('summer')->toArray())){
            abort(403, 'Mismatch Class');
        }
 		$classQuizzes           =   OnlineClassQuiz::with('quiz')
                                    ->where('online_class_id', $class->id)
                                    ->orderBy('start_at', 'DESC')
                                    ->get();

        $this->data['class'] 			= 	$class;
        $this->data['classQuizzes'] 	= 	$classQuizzes;
     	$this->data['quipperAccount'] 	= 	$quipperAccount;
        $this->data['student']          =   $student;
        $this->data['user']             =   $student;
        $this->data['class_attendance'] =   OnlineClassAttendance::getStudentAttendanceToday($class->id, $student->id);

        return view('student.quiz.class-quizzes', $this->data);
    }

    public function startQuiz($class_quiz_id)
    {
        $student    = auth()->user()->student;
    	$classQuiz  = OnlineClassQuiz::findOrFail($class_quiz_id);

        // Check If Current Date is Greater Than The Start Of Class Quiz
        if(Carbon::now()->lt(Carbon::parse($classQuiz->start_at))) {
            \Alert::error('Quiz is not yet open.')->flash();
            return redirect()->back();
        }

        // Check If Current Date is Greater Than The End Of Class Quiz
        if(Carbon::now()->gt(Carbon::parse($classQuiz->end_at))) {
            // Check If Allowed The Late Submission
            if(!$classQuiz->allow_late_submission) {
                \Alert::error('Quiz is already closed.')->flash();
                return redirect()->back();
            }
        }

        // Check If Student Already Take The Quiz
        if(in_array($classQuiz->id, $student->submittedQuizzes->pluck('online_class_quiz_id')->toArray())) {
            // Check If Allowed To Retake Quiz
            if(!$classQuiz->allow_retake) {
                \Alert::error('You already take the quiz.')->flash();
                return redirect()->back();
            }
        }
        
    	// return $entity->quiz;
    	// dd($entity->quiz);
    	return view('student.quiz.start_quiz', compact('classQuiz'));
    }

    // public function submitQuiz($class_quiz_id)
    public function submitQuiz(Request $request)
    {
        $student_answers = $request->student_answers;
    
        $quizs = Quiz::where('id',$request->quiz_id)->first();
        $total_score = 0;
        $questions_item = [];

        foreach($quizs->questions as $questions){
            foreach($student_answers as $student_answer){
                if($questions['id'] == $student_answer['question_id']){
                    foreach ($quizs->isCorrect as $correct_answers) {
                        if($correct_answers['question_id'] == $student_answer['question_id']){

                            if($student_answer['question_type'] == 'choose_one'){
                                if($student_answer['choose_one'] == $correct_answers['choose_one']){
                                    $total_score = $total_score + $questions['points'];
                                    $questions_item[]=
                                                    ['question_id'=>$correct_answers['question_id']
                                                    ,'question_type'=>$questions['question_type']
                                                    ,'score'=>$questions['points']
                                                    ,'points'=>$questions['points']];
                                    
                                }else{
                                    //incorrect
                                    $questions_item[]=
                                                    ['question_id'=>$correct_answers['question_id']
                                                    ,'question_type'=>$questions['question_type']
                                                    ,'score'=>0
                                                    ,'points'=>$questions['points']];
                                }
                            }
                            if($student_answer['question_type'] == 'choose_many'){
                                $correct    = 0;
                                $number_correct = 0;
                                foreach($student_answer['multiple_choice'] as $student_multiple_answer){
                                    foreach($correct_answers['multiple_choice'] as $correct_answers_multiple_answer){
                                        if($correct_answers_multiple_answer['a'] == 'true'){
                                            $number_correct += 1;
                                        }
                                        if($correct_answers_multiple_answer['b'] == 'true'){
                                            $number_correct += 1;
                                        }
                                        if($correct_answers_multiple_answer['c'] == 'true'){
                                            $number_correct += 1;
                                        }
                                        if($correct_answers_multiple_answer['d'] == 'true'){
                                            $number_correct += 1;
                                        }
                                        
                                        if($student_multiple_answer['a'] == 'true' && $correct_answers_multiple_answer['a'] == 'true'){
                                            $correct = $correct + 1;
                                        }
                                        if($student_multiple_answer['b'] == 'true' && $correct_answers_multiple_answer['b'] == 'true'){
                                            $correct = $correct + 1;
                                        }
                                        if($student_multiple_answer['c'] == 'true' && $correct_answers_multiple_answer['c'] == 'true'){
                                            $correct = $correct + 1;
                                        }
                                        if($student_multiple_answer['d'] == 'true' && $correct_answers_multiple_answer['d'] == 'true'){
                                            $correct = $correct + 1;
                                        }

                                    } 
                                }
                                
                                
                                $total_score += $correct / $number_correct * $questions['points'] ;
                                $questions_item[]=
                                                ['question_id'=>$correct_answers['question_id']
                                                ,'question_type'=>$questions['question_type']
                                                ,'score'=>round($correct / $number_correct * $questions['points']) 
                                                ,'points'=>$questions['points']];
                                
                            
                            }
                            if($student_answer['question_type'] == 'true_false'){
                                if($student_answer['true_false'] == $correct_answers['true_false']){
                                    $total_score = $total_score + $questions['points'];
                                    $questions_item[]=
                                                    ['question_id'=>$correct_answers['question_id']
                                                    ,'question_type'=>$questions['question_type']
                                                    ,'score'=>$questions['points']
                                                    ,'points'=>$questions['points']];
                                }else{
                                    //incorrect
                                    $questions_item[]=
                                                    ['question_id'=>$correct_answers['question_id']
                                                    ,'question_type'=>$questions['question_type']
                                                    ,'score'=>0
                                                    ,'points'=>$questions['points']];
                                }
                            }
                            if($student_answer['question_type'] == 'fill_blank'){
                                if($student_answer['fill_blank'] == $correct_answers['fill_blank']){
                                    $total_score = $total_score + $questions['points'];
                                    $questions_item[]=
                                                    ['question_id'=>$correct_answers['question_id']
                                                    ,'question_type'=>$questions['question_type']
                                                    ,'score'=>$questions['points']
                                                    ,'points'=>$questions['points']];
                                }else{
                                     //incorrect
                                     $questions_item[]=
                                                     ['question_id'=>$correct_answers['question_id']
                                                     ,'question_type'=>$questions['question_type']
                                                     ,'score'=>0
                                                     ,'points'=>$questions['points']];
                                }
                            } 
                            if($student_answer['question_type'] == 'essay'){
                                $questions_item[]=
                                                ['question_id'=>$correct_answers['question_id']
                                                ,'question_type'=>$questions['question_type']
                                                ,'score'=>0
                                                ,'points'=>$questions['points']];
                               
                            }

                           
                        }

                    }

                }

            }
        }
    
        $student    = auth()->user()->student;
        $class_quiz_id = $request->class_quiz_id;
        
    	// Check The Date of Quiz Is Takeable 
    	$classQuiz = OnlineClassQuiz::findOrFail($class_quiz_id);

    	// if( !(now()->gte($classQuiz->start_at) && now()->toDateString() <= Carbon::parse($classQuiz->end_at)->toDateString()) ) {
	    
        
    	// }
         // Check If Current Date is Greater Than The Start Of Class Quiz
        if(Carbon::now()->lt(Carbon::parse($classQuiz->start_at))) {
            \Alert::error('Quiz is not yet open.')->flash();
            return redirect()->back();
        }

        // Check If Current Date is Greater Than The End Of Class Quiz
        if(Carbon::now()->gt(Carbon::parse($classQuiz->end_at))) {
            // Check If Allowed The Late Submission
            if(!$classQuiz->allow_late_submission) {
                \Alert::error('Quiz is already closed.')->flash();
                return redirect()->back();
            }
        }

        // Check If Student Already Take The Quiz
        if(in_array($classQuiz->id, $student->submittedQuizzes->pluck('online_class_quiz_id')->toArray())) {
                // Check If Allowed To Retake Quiz
                if(!$classQuiz->allow_retake) {
                    \Alert::error('You already take the quiz.')->flash();
                    return redirect()->back();
                }
        }
       
        // $quiz = new StudentQuizResult;
        // $quiz->studentnumber = auth()->user()->studentnumber;
        // $quiz->online_class_quiz_id = $class_quiz_id;
        // $quiz->attempts = 1;
        // $quiz->questionnaire = request()->questionnaire;
        // $quiz->results = request()->result;
        // $quiz->score = request()->answers['correct'];
        // $quiz->time_end_at = now()->format('h:i:s');
        // $quiz->save();

        $quiz = new StudentQuizResult;
        $quiz->studentnumber = auth()->user()->studentnumber;
        $quiz->online_class_quiz_id = $class_quiz_id;
        $quiz->attempts = 1;
        $quiz->questionnaire = json_encode($request->questions);
        $quiz->results = $request->student_answers;
        $quiz->final_score = $questions_item;
        $quiz->score = round($total_score, 1);
        $quiz->time_start_at = $request->start_time;
        $quiz->time_end_at =  Carbon::now()->format('H:i');
        $quiz->save();
    }
    public function getQuestions(Request $request)
    {
        $quiz = Quiz::where('id',$request->quiz_id) ->select('questions','temp_answers','duration','updated_at')->first();
        $student_quiz_results = StudentQuizResult::where('studentnumber',auth()->user()->studentnumber)
        ->where('online_class_quiz_id',$request->class_quiz_id)
        ->first();
        $online_class_quiz = OnlineClassQuiz::where('id',$request->online_quiz_id)->select('shuffle')->first();
        
        $respone = [
            'quiz' => $quiz,
            'student_quiz_result' => $student_quiz_results,
            'shuffle' => $online_class_quiz
        ];
        return $respone;
    }
    public function showResult($class_quiz_id)
    {
        $onlineClassController  =   new OnlineClassController();

	    $student                =   auth()->user()->student;
	    $student_section        =   $onlineClassController->studentSectionAssignment();
	    $my_classes             =   $onlineClassController->getOnlineClasses();
	    $quipperAccount         =   QuipperStudentAccount::where('student_id', $student->id)->first();

        $student_result = StudentQuizResult::where('online_class_quiz_id', $class_quiz_id)->first();
        if(!$student_result) {
            \Alert::warning('Quiz Not Found!')->flash();
            return redirect()->to('student/online-class-quizzes');
        }

        if(!$student_result->is_check) {
            \Alert::warning('Your Quiz is being process.')->flash();
            return redirect()->to('student/online-class-quizzes');
        }
        
        $this->data['my_classes']  	  = 	$my_classes;
        $this->data['classQuizzes']   = 	$class_quiz_id;
        $this->data['user']           =     $student;

        return view('student.quiz.quiz_result', $this->data);
    }
    public function showStudentResult(Request $request)
    {
        $student_result = StudentQuizResult::where('online_class_quiz_id',$request->id)->first();
        $quiz = Quiz::where('id',OnlineClassQuiz::where('id',$request->id)->pluck('quiz_id'))->first();
        $respone = [
            'quiz' => $quiz,
            'student_quiz_result' => $student_result
        ];
        return $respone;
    }
}
