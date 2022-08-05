@extends('backpack::layout_student')

@section('header')
    {{-- <section class="content-header">
      <h1>
        {{ trans('backpack::base.dashboard') }}<small>{{ trans('backpack::base.first_page_you_see') }}</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ backpack_url() }}">{{ config('backpack.base.project_name') }}</a></li>
        <li class="active">{{ trans('backpack::base.dashboard') }}</li>
      </ol>
    </section> --}}
@endsection

@section('after_styles')
    <link rel="stylesheet" href="{{ asset('js/calendar/style.css')}}">
    <link rel="stylesheet" href="{{ asset('js/calendar/theme.css')}}">
@endsection

@section('content')
  <style>
    .calendar-wrapper {
        padding: 15px;

    }
    .assignments {
        font-size: 20px;
        font-weight: 800;
    }
    .assignment {
       
    }

    .lessons {
        font-size: 20px;
        font-weight: 800;
    }

    .classes {
      border-radius: 2px;
    }

    .inner {
      margin: 0px !important;
    }

    .inner h3 {
      font-size: 25px !important;
      margin: 0px !important;
    }

    .inner p {
      font-size: 15px !important;
      margin: 0px !important;
    }

    .inner .class-time {
      font-size: 10px !important;
    }

    .box-footer {
      border-radius: 0 0 10px 10px;
    }

    @media only screen and (min-width: 768px) {
      #welcomeImage {
        float: right;
      }
          /* For desktop phones: */
        .oc-header-title {
          margin-top: 80px;
        }
        .content-wrapper{
            border-top-left-radius: 50px;
            }
        .sidebar-toggle{
          margin-left:30px;
        }
        .main-footer{
        border-bottom-left-radius: 50px;
        padding-left: 80px;
      }
    }
    .card-status{
      margin:10px;
      background-color: #1CC883;
      border-radius: 50px;
      margin-bottom:0px;
      width: 20px;
      height: 20px;

    }
    .card{
      margin-bottom: 10px;
      background-color: #fff;
      border-radius: 13px;
      box-shadow: 0 4px 6px 0 rgba(0,0,0,0.2);
      width: 100%;
   
    }
    .card:hover{
      margin-left: -5px;
      box-shadow: 0 4px 6px 0 #3C8DBC;
      width: 102%;
      
    }
    .card-info{
      border-radius: 10px;
      width: 100%;
      border: 1px solid rgb(238, 232, 232);
    }
    .row-quiz{
      display: flex;
      flex-direction: row;
    }
    .column-right {
      display: flex;
      flex-direction: column;
      align-items: flex-end;
      
    }
    .column-left {
      margin-left:20px;
      display: flex;
      flex-direction: column;
      align-items: flex-start;
      
    }
    .column-description{
      margin-left:20px;
      margin-right:20px;
      display: flex;
      flex-direction: column;
      align-items: flex-start;
    }
    .row-text{
      display: flex;
      flex-direction: row;
      width: 100%;
    }
    .column-text-right{
      display: flex;
      flex-direction: column;
      flex-basis: 100%;
      flex: 1;
      width: 100%;
      align-items: flex-end;
    }
    .column-text-left{
      display: flex;
      flex-direction: column;
      flex-basis: 100%;
      flex: 1;
      width: 100%;
      align-items: flex-start;
    }
    .inside{
      font-size:11px;
    }

  </style>

  <!-- <div class="alert alert-warning alert-dismissible fade show" role="alert">
    <strong>Holy guacamole!</strong> You should check in on some of those fields below.
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
  </div> -->
  <body style="background: #3c8dbc;">
  <div class="row">
    <div class="d-flex flex-row">
        <div class="col-md-9">
            {{-- Welcome Page --}}
            <div class="col-md-12">
                
                <div class="info-box " style="min-height: 150px;">

                    <div class="col-md-4 text-center" id="welcomeImage">
                        <img src="{{ asset('images/studentdashboard.png')}}" alt="">
                    </div>

                    <div class="col-md-8">
                      <h4>Welcome Message</h4>
                      <p>Hi <b>{{ $student->firstname }}</b>! Welcome back and let's learn new things! 
                        {{ count($my_classes->where('ongoing', 1)) > 0 
                          ? 'You have ' . count($my_classes->where('ongoing', 1)) . ' class that is on going!' 
                          : 'You have no on going class!' }} 
                      </p>

                      @if( count($my_classes->where('ongoing', 1)) > 0 )
                        <a href="online-class" class="btn btn-success">Go to your class!</a>
                      @endif

                    </div>
                    <!-- <div class="col-md-4 text-center">
                        <img src="{{ asset('images/studentdashboard.png')}}" alt="">
                    </div> -->
                  
                </div>
            </div>
            {{-- End of Welcome Message --}}
            <div class="col-md-12 assignment-wrapper">
              <p>
                <span class="assignments">Your Quiz</span> 
                <span class="pull-right">
                  <a href="online-class-quizzes"> View More</a>
                </span>
              </p>
              <hr>

              {{-- Active Quiz Loop Slider --}}
              
               <!-- START QUIZ LIST -->
              @if(count($classQuizzes)>0)
                @foreach($classQuizzes->take(3) as $classQuiz)
                  @if($classQuiz->quiz && $classQuiz->onlineClass)

              <!-- START OF GET QUIZ STATUS (AVAILABLE, NOT AVAILABLE, TAKEN OR CHHECKED) -->
              @php
                $submitted_quiz = $student->submittedQuizzes->where('online_class_quiz_id', $classQuiz->id)->first();
                $quiz_status    = 'not available';

                if(now()->toDatetimeString() >= $classQuiz->start_at) {
                  if(now()->toDatetimeString() <= $classQuiz->end_at || $classQuiz->allow_late_submission) {
                      if(!$submitted_quiz || $classQuiz->allow_retake) {
                        $quiz_status = 'available';
                      }
                  }
                }

                if($submitted_quiz) {
                  $quiz_status = 'taken';
                  if($submitted_quiz->is_check) {
                    $quiz_status = 'checked';
                  }
                }
              @endphp
            <!-- END OF GET QUIZ STATUS (AVAILABLE, NOT AVAILABLE, TAKEN OR CHHECKED) -->
            
              <div class="col-md-4 col-lg-4 m-b-10">
                <div class="card">

                  <div class="row-quiz">
                    <!-- START SUBJECT NAME -->
                    <div class="column-left">
                      <h4> 
                        <a href="{{ url('student/online-class/' . $classQuiz->onlineClass->code  .'/quizzes')}}" style="font-size:15px;font-family:sans-serif; font-weight: bold;">{{ Str::limit($classQuiz->onlineClass->subject_name,32 )}}</a>
                      </h4>
                    </div>
                    <!-- END SUBJECT NAME -->

                    <!-- START QUIZ STATUS -->
                    <div class="column-right m-b-0" style="margin-left: auto; margin-right:10px;">

                      @if($quiz_status == 'not available')
                        <div class="card-status text-center" style="background: #FD8596;">
                          <h5><b style="color:#fff; font-size:15px;"></b></h5>
                        </div>
                      @endif

                      @if($quiz_status == 'available')
                        <div class="card-status text-center" >
                          <h5> <b style="color:#fff; font-size:15px;"></b></h5>
                        </div>
                      @endif

                      @if($quiz_status == 'taken')
                        <div class="card-status text-center" style="background: #FD8596;">
                          <h5><b style="color:#fff; font-size:15px;"></b></h5>
                        </div>
                      @endif

                      @if($quiz_status == 'checked')
                        <div class="card-status text-center bg-success">
                          <h5><b style="color:#fff; font-size:15px;"></b></h5>
                        </div>
                      @endif
                    </div>
                    <!-- END QUIZ STATUS -->
                  </div>

                  <!-- START QUIZ TITLE -->
                  <div class="row-quiz">
                    <div class="column-left">
                      <h4 style="margin-bottom: 0px; font-size:13px; margin-top:0px"><b>{{ Str::limit($classQuiz->quiz->title,50) }}</b></h4>
                    </div>
                  </div>
                  <!-- END QUIZ TITLE -->

                  <!-- START QUIZ DESCRIPTION -->
                  <div class="row-quiz m-l-20">
                    <p style="font-size:11px;">{!! Str::limit($classQuiz->quiz->description,50) !!}</p>
                  </div>
                  <!-- END QUIZ DESCRIPTION -->

                  <!-- START QUIZ INFORMATION -->
                  <div class="row-quiz">
                    <div class="card-info m-l-15 m-r-15 m-b-15">
                      <p class="mb-0 m-l-10 m-b-0 inside">No. of Items : <strong>{{ $classQuiz->quiz->total_questions }}</strong></p>
                      @if($submitted_quiz)
                        <p class="text-muted mb-0 m-l-10 m-b-0 inside">Score : <strong>{{ $submitted_quiz->student_score }}</strong></p>
                        <p class="text-muted mb-0 m-l-10 m-b-0 inside">Total Points : <strong>{{ $submitted_quiz->total_score }}</strong></p>
                      @else
                        @if($classQuiz->quiz)
                          <p class="text-muted mb-0 m-l-10 m-b-0 inside">Total Points : <strong>{{ $classQuiz->quiz->total_score }}</strong></p>
                        @endif
                      @endif
                      <p class="text-muted mb-0 m-l-10 m-b-0 inside">Start Date : <strong>{{ date('F j, Y h:m a', strtotime($classQuiz->start_at)) }}</strong></p>
                      <p class="text-muted mb-0 m-l-10 m-b-0 inside">End Date : <strong>{{ date('F j, Y  h:m a', strtotime($classQuiz->end_at)) }}</strong></p>
                    </div>
                  </div>
                  <!-- END QUIZ INFORMATION -->

                  <!-- START QUIZ BUTTON -->
                  <div class="row-quiz">
                    <div class="col-md-12 p-0">
                      @if($quiz_status == 'available')
                        <form target="_blank" action="{{ url('student/online-class-quizzes/' . $classQuiz->id . '/start') }}" method="POST">
                          @csrf
                          <button class="btn box-button-one w-100 ">Take Quiz Now</button>
                        </form>
                      @endif

                      @if($quiz_status == 'not available')
                        <a href="javascript:void(0)" class="btn box-button-one w-100 disabled">Not Available</a>
                      @endif

                      @if($quiz_status == 'taken')
                      <a href="javascript:void(0)" class="btn box-button-one w-100 disabled">Waiting For Result</a>
                      @endif

                      @if($quiz_status == 'checked')
                        <a href="{{ url('student/online-class-quizzes/show_quiz_result/'. $classQuiz->id .'')}}" class="btn box-button-one w-100">View Result</a>
                      @endif
                    </div>
                  </div>
                  <!-- END QUIZ BUTTON -->

                </div>
              </div>
                  @endif
                @endforeach
                <!-- ALL QUIZ LOADED -->
                {{-- <div class="text-center p-b-20">
                  <h4> All quizzes loaded </h4>
                </div> --}}
              @else
              <h5 class="text-center">No Quiz yet.</h5>
              @endif
              <!-- END OF QUIZ LIST -->

              {{-- End Active Quiz Loop Slider --}}
              
            </div>

            <div class="col-md-12 assignment-wrapper">
              <p>
                <span class="assignments">Your Assignments</span> 
                <span class="pull-right">
                  <a href="online-class-assignments"> View More</a>
                </span>
              </p>
              <hr>

              {{-- Active Assignments Loop Slider --}}

              @forelse ($assignments->take(3) as $assignment)
                <div class="col-md-4">
                  <div class="box" data-widget="box-widget" style="border-top-color: {{ $assignment->class->color }};">
                    <div class="box-header">
                      <h3 class="box-title">{{ Str::limit($assignment->title, 15) }}</h3>
                      <div class="box-tools">
                        <!-- This will cause the box to collapse when clicked -->
                        <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                          <i class="fa fa-minus"></i>
                        </button>
                      </div>
                    </div>
                    <div class="box-body">
                      <!-- Assignment Instruction -->
                      <div style="height:90px;">
                      <p>{!! Str::limit(strip_tags($assignment->instructions), 150) !!}</p>
                         
                     
                        </div> 
                        Due Date: <b>{{ date('F j, Y', strtotime($assignment->due_date)) }}</b>
                      <a href="{{ url('student/online-class-assignments?id=' . $assignment->id) }}" class="btn btn-primary btn-block"><i class="fa fa-upload"></i> Turn In</a>
                      <!-- <div class="progress">
                        <div class="progress-bar progress-bar-primary progress-bar-striped" style="width: 70%">70%</div>
                      </div> -->
                    </div>
                  </div>
              </div>
              @empty
                  <h5 class="text-center">No assignments yet.</h5>
              @endforelse

              {{-- End Active Assignments Loop Slider --}}
              
            </div>


            <div class="col-md-12">
              <p>
                <span class="lessons">My Classes</span> 
                <span class="pull-right">
                  <a href="{{ url('student/online-class') }}"> View More</a>
                </span>
              </p>
              <hr>

              {{-- Active lessons Loop Slider --}}

              @forelse ($my_classes->take(3) as $my_class)
                <div class="col-lg-4 col-xs-12">
                  <!-- small box -->
                  <div class="small-box bg-aqua classes" style="background-color: {{$my_class->color}} !important;">
                    <div class="inner">
                      <h4> <b> {{ Str::limit($my_class->subject_name, 15) }} </b> </h4>
                      <h5>Teacher {{ $my_class->teacher ? $my_class->teacher->firstname : '-' }}</h5>
                      <p class="class-time">Time:
                        {{ $my_class->start_time ? date('h:i A', strtotime($my_class->start_time)) : '' }}
                         - 
                        {{ $my_class->end_time ? date('h:i A', strtotime($my_class->end_time)) : '' }}
                      </p>
                    </div>

                    <!-- PROGRESSBAR -->
                    @if($my_class->course)
                      @php
                        $student_progress = count($studentClassProgresses->where('online_class_id', $my_class->id));
                        $total_topics = count($my_class->course->topics);

                        $total_progress = $student_progress / $total_topics * 100;
                      @endphp
                            {{-- CHECK IF TOTAL PROGRESS IS EQUAL TO 0 --}}
                            @if($total_progress != 0)
                              <div class="progress active" style="background-color: {{ $my_class->color }};">
                                <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: {{$total_progress}}%; background-color: rgba(0,0,0,0.1);" aria-valuenow="{{$total_progress}}" aria-valuemin="0" aria-valuemax="100">
                                  {{$total_progress}}%
                                </div>
                              </div>
                            @else
                              <div class="progress active" style="background-color: {{ $my_class->color }};">
                                <div hidden class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 100%; background-color: rgba(0,0,0,0.1);" aria-valuenow="{{$total_progress}}" aria-valuemin="0" aria-valuemax="100">
                                  0%
                                </div>
                              </div>
                            @endif
                        {{-- FOR SAME WIDTH - SMALL BOX  --}}
                   @else
                      <div class="progress active" style="background-color: {{ $my_class->color }};">
                        <div hidden class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 100%; background-color: rgba(0,0,0,0.1);" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                          0%
                        </div>
                      </div>
                    @endif
                    <!-- PROGRESSBAR -->

                    <a href="{{ url('student/online-post?class_code=' . $my_class->code) }}" class="small-box-footer">
                      Enter Class Now <i class="fa fa-arrow-circle-right"></i>
                    </a>
                  </div>
                </div>
              @empty
                <h5 class="text-center">No available class.</h5>
              @endforelse

              {{-- End Active lessons Loop Slider --}}
              
            </div>
        </div>

        
    </div>
    <div class="d-flex flex-row" style="height: 350px;">
      <div class="col-md-3 col-sm-12 col-xs-12 pull-right" >
        <div class="col-md-12">
          <div class="info-box calendar-wrapper">
              <div class="calendar-container"></div>
          </div>
          
          <!-- TODO LIST -->
          <div class="box box-primary direct-chat direct-chat-primary" style="margin: 0px !important;" id="todoBox">
            <div class="box-header with-border">
              <h3 class="box-title">Set your goals!</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <!-- Conversations are loaded here -->
              <div class="direct-chat-messages p-b-0">
                <!-- Message. Default to the left -->
                <div class="direct-chat-msg" id="todo-list">

                  @forelse ($goals as $goal)
                    <div class="direct-chat-info clearfix" id="todo-info-{{ $goal->id }}">
                      <div class="col-md-1 col-xs-1" style="padding-left: 5px;">
                        <span class="pull-left m-r-10" id="goal-check-icon-{{ $goal->id }}">
                          <!-- <input type="checkbox" data-id="{{ $goal->id }}" name="checkGoal" id="goal{{ $goal->id }}" value="{{ $goal->id }}"> -->
                          @if($goal->done)
                            <i class="fa fa-check text-success"></i>
                          @endif
                        </span>
                      </div>
                      <div class="col-md-9 col-xs-9" style="padding-left: 5px;">
                        <span class="direct-chat-name pull-left">
                          {{ $goal->content }}
                        </span>
                      </div>
                      <div class="col-md-1 col-xs-1" style="padding-right: 5px;">
                        <span class="pull-left m-r-10">
                          <input type="checkbox" data-id="{{ $goal->id }}" data-checked="{{ $goal->done ? 'checked' : 'unchecked' }}" name="checkGoal" id="goal{{ $goal->id }}" value="{{ $goal->id }}">
                        </span>
                      </div>
                    </div>
                  @empty
                    <p class="text-center" id="empty-todo">No goal set.</p>
                  @endforelse
                </div>
                <!-- /.direct-chat-msg -->
              </div>
              <!--/.direct-chat-messages-->  
            </div>
            <!-- /.box-body -->

            <div class="box-footer" id="check-and-delete-buttons" style="display: none;">
              <div class="col-md-6 col-xs-6 text-center">
                <button id="btnCheckTodo" type="button" class="btn btn-box-tool m-b-0 p-b-0">
                  <i class="fa fa-check" aria-hidden="true"></i>
                </button>
              </div>
              <div class="col-md-6 col-xs-6 text-center">
                <button id="btnDeleteTodo" type="button" class="btn btn-box-tool m-b-0 p-b-0">
                  <i class="fa fa-trash" aria-hidden="true"></i>
                </button>
              </div>
            </div>

            <div class="box-footer">
              <div class="input-group">
                <input type="text" id="message" name="message" placeholder="Type your goals ..." class="form-control">
                <span class="input-group-btn">
                  <button type="button" id="btnTodo" class="btn btn-primary btn-flat">Add Todo</button>
                </span>
              </div>
            </div>
            <!-- /.box-footer-->
          </div>
          <!--/.TODO -->
        </div>
     
      </div>
    </div>
  </div>
  </body>
@endsection

@section('after_scripts')
  <!-- CALENDAR SCRIPT -->
  <script src="{{ asset('js/calendar/calendar.js')}}"></script>
  <script>
      $('.calendar-container').calendar({
        date:new Date()// today
    });
  </script>

  <!-- SCRIPT FOR ADDING TODO -->
  <script>
    function checkboxClick() {
        if($("input:checkbox[name=checkGoal]:checked").length >= 1) {
          $("#check-and-delete-buttons").show();
        } else {
          $("#check-and-delete-buttons").hide();
        }
      }

    $(document).ready(function () {
      $('#todoBox #btnTodo').click(function () {
        saveTodo();
      });

      $('#message').keypress(function (e) {
        if (e.which == 13) {
          saveTodo();
        }
      });

      $('#btnCheckTodo').click(function () {
        doneTodo();
      });

      $('#btnDeleteTodo').click(function () {
        deleteTodo();
      });

      $("input:checkbox[name=checkGoal]").on("click",function() {
        if($("input:checkbox[name=checkGoal]:checked").length >= 1) {
          $("#check-and-delete-buttons").show();
        } else {
          $("#check-and-delete-buttons").hide();
        }
      });

      $("input:checkbox[name=checkGoal]").change(function() {
        if($("input:checkbox[name=checkGoal]:checked").length >= 1) {
          $("#check-and-delete-buttons").show();
        } else {
          $("#check-and-delete-buttons").hide();
        }        
    });

      function saveTodo() {
        var message = $('#todoBox #message').val();
        $('#todoBox #message').val('');
        if(message == '') {
          return;
        }

        $.ajax({
          url: 'goal',
          method: 'post',
          data:{
            message: message,
            _token:"{{csrf_token()}}"
          },
          success: function (response) {
            if(response.data) {
              var todo = 
                '<div class="direct-chat-info clearfix" id="todo-info-' + response.data.id +'">\
                  <div class="col-md-1 col-xs-1" style="padding-left: 5px;">\
                    <span class="pull-left m-r-10" id="goal-check-icon-' + response.data.id + '">\
                    </span>\
                  </div>\
                  <div class="col-md-9 col-xs-9" style="padding-left: 5px;">\
                    <span class="direct-chat-name pull-left">' + response.data.content + '</span>\
                  </div>\
                  <div class="col-md-1 col-xs-1" style="padding-right: 5px;">\
                    <span class="pull-left m-r-10">\
                      <input type="checkbox" data-id="' + response.data.id + '" data-checked="unchecked" name="checkGoal" id="goal' + response.data.id + '" value="' + response.data.id + '" onclick="checkboxClick()">\
                    </span>\
                  </div>\
                </div>';

              $('#todo-list').append(todo);
              $('#empty-todo').remove();

              new PNotify({
                title: response.title,
                text: response.message,
                type: response.status
              });
              
              checkboxClick();
            }
          },
          error:function(data){
            new PNotify({
              title: 'Oops...',
              text: 'Something went wrong!',
              type: 'warning'
            });
          }
        });
      }

      function doneTodo() {
        var todos = [];
        $("input:checkbox[name=checkGoal]:checked").each(function() {
          if($(this).data('checked') != 'checked' && $(this).data('checked') == 'unchecked') {
            todos.push($(this).val());
          }
        });

        if(todos.length < 1) {
          return;
        }

        $.ajax({
          url: 'goal/done',
          type: 'patch',
          data:{
            ids: todos,
            _token:"{{csrf_token()}}"
          },
          success: function (response) {
            $.each(response.data, function( index, value ) {
              if(value.done) {
                $('#goal' + value.id).data('checked', 'checked');
                $('#goal-check-icon-' + value.id).append('<i class="fa fa-check text-success"></i>');
              }
            });

            new PNotify({
              title: response.title,
              text: response.message,
              type: response.status
            });

            checkboxClick();
          },
          error:function(data){
            new PNotify({
              title: 'Oops...',
              text: 'Something went wrong!',
              type: 'warning'
            });
          }
        });

      }

      function deleteTodo() {
        var todos = [];
        // console.log(todos);
        $("input:checkbox[name=checkGoal]:checked").each(function() {
          todos.push($(this).val());
        });

        if(todos.length < 1) {
          return;
        }
        
        $.ajax({
          url: 'goal/delete',
          type: 'delete',
          data:{
            ids: todos,
            _token:"{{csrf_token()}}"
          },
          success: function (response) {
            if(response.status == 'success') {
              $.each(response.data, function( index, value ) {
                $('#todo-info-' + value).remove();
              });

              if ($('#todo-list').children().length == 0) {
                $('#todo-list').append('<p class="text-center" id="empty-todo">No goal set.</p>');
              }
            }

            new PNotify({
              title: response.title,
              text: response.message,
              type: response.status
            });

            checkboxClick();
          },
          error:function(data){
            new PNotify({
              title: 'Oops...',
              text: 'Something went wrong!',
              type: 'warning'
            });
          }
        });

      }

    });
  </script>
@endsection