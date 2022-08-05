<?php 

Route::group([
    'prefix'     => config('backpack.base.route_prefix', 'admin'),
    'middleware' => ['web', config('backpack.base.middleware_key', 'admin'), 'school_module:OnlineClass', 'first_time_login', 'verify_schoolyear'],
    // 'namespace'  => 'App\Http\Controllers\Admin',
], function () { 

	/*
    |--------------------------------------------------------------------------
    | Course
    |--------------------------------------------------------------------------
    */
    CRUD::resource('online-course', 'OnlineCourseCrudController')->with(function () {
        Route::post('online-course/{id}', 'OnlineCourseCrudController@destroy')->where('id', '[0-9]+');
    });

    /*
    |--------------------------------------------------------------------------
    | Class
    |--------------------------------------------------------------------------
    */
    CRUD::resource('teacher-online-class', 'OnlineClassCrudController')->with(function () {
        Route::get('teacher-online-class/{id}/archive', 'OnlineClassCrudController@setArchive');
        Route::get('teacher-online-class/get-tracks', 'OnlineClassCrudController@getTracks');
        Route::get('teacher-online-class/get-sections', 'OnlineClassCrudController@getSections');
        Route::get('teacher-online-class/get-subjects', 'OnlineClassCrudController@getSubjects');
        Route::get('teacher-online-class/get-terms', 'OnlineClassCrudController@getTerms');
        Route::get('teacher-online-class/{class_code}/student-list', 'OnlineClassCrudController@showStudentList');
        Route::get('teacher-online-class/{class_code}/course', 'OnlineClassCrudController@showClassCourse');
        // Show Course Module
        Route::get('teacher-online-class/{class_code}/course/{module_id}', 'OnlineClassCrudController@showCourseModule')->where(['module_id' => '[0-9]+']);
        // Show Course Topic
        Route::get('teacher-online-class/{class_code}/course/{module_id}/{topic_id}', 'OnlineClassCrudController@showCourseTopic')->where(['module_id' => '[0-9]+', 'topic_id' => '[0-9]+']);

        Route::get('teacher-online-class/{class_code}/course/{module_id}/{topic_id}/api/get-page', 'OnlineClassCrudController@showCourseTopicPage')->where(['module_id' => '[0-9]+', 'topic_id' => '[0-9]+']);

        Route::get('teacher-online-class/{class_code}/course/{module_id}/{topic_id}/{page_id}', 'OnlineClassCrudController@showTopicPage')->where(['module_id' => '[0-9]+', 'topic_id' => '[0-9]+', 'page_id' => '[0-9]+']);

        // Route::get('teacher-online-class/{classId}/video/logout/{meetingId}/{password}', 'BigBlueButtonController@endMeeting');
        Route::get('teacher-online-class/video_conference', 'OnlineClassCrudController@videoConference');
        Route::get('teacher-online-class/join-conference/{class_code}', 'OnlineClassCrudController@joinConference');
        Route::get('teacher-online-class/{class_code}/recordings', 'OnlineClassCrudController@getRecordings');

        Route::get('teacher-online-class/video_conference_status', 'OnlineClassCrudController@getVideoConferenceStatus');

        Route::get('teacher-online-class/{class_code}/quizzes', 'OnlineClassCrudController@showClassQuizzes');

        Route::get('teacher-online-class/api/get/class', 'OnlineClassCrudController@searchMyClasses');
    });

    /*
    |--------------------------------------------------------------------------
    | Module / Topics / Topic Page
    |--------------------------------------------------------------------------
    */
    CRUD::resource('online-class-module', 'OnlineClassModuleCrudController')->with(function () {
        Route::get('online-class-module/{id}/delete', 'OnlineClassModuleCrudController@destroy');
    });
    CRUD::resource('online-class-topic', 'OnlineClassTopicCrudController')->with(function () {
        Route::get('online-class-topic/{id}/delete', 'OnlineClassTopicCrudController@destroy');
        Route::get('online-class-topic/api/get-page', 'OnlineClassTopicCrudController@ajaxFetchPage');
    });
    Route::get('online-class-topic/api/get-quiz', 'QuizCrudController@getUserQuizzes');

    CRUD::resource('online-class-topic-page', 'OnlineTopicPageCrudController')->with(function () {
        Route::get('online-class-topic-page/{id}/delete', 'OnlineTopicPageCrudController@delete');
    });
    
    /*
    |--------------------------------------------------------------------------
    | Posts
    |--------------------------------------------------------------------------
    */
    CRUD::resource('online-post', 'OnlinePostCrudController')->with(function () {
        Route::get('online-post/api/get-topics', 'OnlinePostCrudController@getTopics')->name('teacher-online-post/api/get-topics');
        Route::get('online-post/api/get-modules', 'OnlinePostCrudController@getModules')->name('teacher-online-post/api/get-modules');
        Route::get('online-post/api/get/class-posts', 'OnlinePostCrudController@getClassPosts')->name('teacher-online-post/api/get/class-posts');
        Route::post('online-post/{post_id}/like', 'OnlinePostCrudController@likePost');
        // Comment
        Route::post('online-post/{post_id}/comment', 'OnlinePostCrudController@storeComment');
        Route::post('online-post/{post_id}/comment/{comment_id}/delete', 'OnlinePostCrudController@deleteComment');
    });

    /*
    |--------------------------------------------------------------------------
    | Comment
    |--------------------------------------------------------------------------
    */
    CRUD::resource('online-comments', 'OnlineCommentCrudController');

    /*
    |--------------------------------------------------------------------------
    | Quiz
    |--------------------------------------------------------------------------
    */
    CRUD::resource('questionnaire', 'QuestionnaireCrudController');
    CRUD::resource('quiz', 'QuizCrudController')->with(function () {
        Route::get('quiz/api/get/quiz', 'QuizCrudController@getQuiz')->name('quiz/api/get/quiz');
        Route::post('quiz/api/add-question', 'QuizCrudController@addQuestion');
        Route::get('quiz/api/get/questions/{quiz_id}', 'QuizCrudController@getQuizQuestions');
         // Search Student
        Route::get('quiz/api/get/questions', 'QuizCrudController@getQuestions')->name('quiz/api/get/questions');
        Route::get('quiz/create/{id}/questions', 'QuizCrudController@createQuestions');
        Route::post('quiz/save', 'QuizCrudController@save');
        Route::post('quiz/question/save', 'QuizCrudController@questionSave');
        Route::get('quiz/question', 'QuizCrudController@showQuestion');
        Route::post('quiz/report', 'QuizCrudController@reportQuiz');
    });	

     /*
    |--------------------------------------------------------------------------
    | Class Quiz
    |--------------------------------------------------------------------------
    */
    CRUD::resource('online-class/quiz', 'OnlineClassQuizCrudController')->with(function () {
        Route::get('online-class/quiz/{id}/results', 'OnlineClassQuizCrudController@getResults');
        Route::get('online-class/quiz/print/{id}', 'OnlineClassQuizCrudController@print');
    });

    CRUD::resource('online-class/student-quiz-result', 'StudentQuizResultCrudController')->with(function () {
        Route::get('online-class/student-quiz-result/api/get/student-quiz-result/{id}', 'StudentQuizResultCrudController@getResult');
        Route::post('online-class/student-quiz-result/{id}/submit-score', 'StudentQuizResultCrudController@submitScore');
        Route::post('online-class/student-quiz-result/api/submit-final-score', 'StudentQuizResultCrudController@submitfinalScore');
        Route::get('online-class/student-quiz-result/question', 'StudentQuizResultCrudController@getQuestions');
    });

    /*
    |--------------------------------------------------------------------------
    | Assignment
    |--------------------------------------------------------------------------
    */
    CRUD::resource('online-class/assignment', 'AssignmentCrudController')->with(function () {
        Route::get('online-class/{class_code}/assignment', 'AssignmentCrudController@showClassAssignment');
    });

    Route::get('online-class/{code}/recordings', 'OnlineClassCrudController@showRecordings');

    CRUD::resource('online-class/submitted-assignment', 'StudentSubmittedAssignmentCrudController');
    Route::get('online-class/assignment/{assignment_id}/{studentnumber}', 'StudentSubmittedAssignmentCrudController@showSubmittedAssignment');

    Route::post('online-class/on-going/set', 'OnlineClassCrudController@setOngoing');

    /*
    |--------------------------------------------------------------------------
    | Online Class Attendance
    |--------------------------------------------------------------------------
    */
    CRUD::resource('online-class/attendance', 'OnlineClassAttendanceCrudController')->with(function () {
        // Route::get('online-class/{class_code}/assignment', 'AssignmentCrudController@showClassAssignment');
        Route::get('online-class/{class_code}/api/students-attendance-logs', 'OnlineClassAttendanceCrudController@getStudentsAttendanceLogs');
        Route::get('online-class/attendance/api/employee/{employee_id}/attendance-logs', 'OnlineClassAttendanceCrudController@employeeAttendanceLogs');

        Route::post('online-class/attendance/employee/{class_code}/{employee_id}','OnlineClassAttendanceCrudController@submitEmployeeAttendance')->where(['employee_id' => '[0-9]+']);

        Route::post('online-class/attendance/employee/{employee_id}/download', 'OnlineClassAttendanceCrudController@downloadEmployeeAttendance');
    });

    Route::get('online-class/{code}/attendance', 'OnlineClassCrudController@showClassAttendance');
    Route::post('online-class/qr-code/{qrcode}','OnlineClassAttendanceCrudController@submitClassAttendance');

}); // this should be the absolute last line of this file
