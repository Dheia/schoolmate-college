@extends('backpack::layout_student')

@section('header')
@endsection

@section('content')
  <link rel="stylesheet" type="text/css" href="{{asset('css/onlineclass/class.css')}}">
  <style>
      @media only screen and (min-width: 768px) {
        .content-wrapper{
      border-top-left-radius: 50px;
      }
      .sidebar-toggle{
        margin-left:40px;
      }
     
    }
    .main-footer{
      border-bottom-left-radius: 50px;
    
    }
    .card-status{
      margin:10px;
      background-color: #1CC883;
      border-radius: 5px;
      margin-bottom:0px;
      width: 100px;
      height: 35px;

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
      border-radius: 13px;
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
  </style>
  <body style="background: #3c8dbc;">
  <div class="row p-l-30 p-r-30">
    <div class="col-md-8 col-lg-8 no-padding">
    @include('student/online-class/partials/navbar')
   
      <!-- START QUIZ LIST -->
      @if(count($classQuizzes)>0)
        @foreach($classQuizzes as $classQuiz)
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
            
            <div class="col-md-6 col-lg-6 ">
              <div class="card">

                <div class="row-quiz">
                  <!-- START SUBJECT NAME -->
                  <div class="column-left">
                    <h4 style="margin-top:20px"> 
                      <a href="{{ url('student/online-class/' . $classQuiz->onlineClass->code  .'/quizzes')}}" style="font-family:sans-serif; font-weight: bold;">{{ Str::limit($classQuiz->onlineClass->subject_name,32 )}}</a>
                    </h4>
                  </div>
                  <!-- END SUBJECT NAME -->

                  <!-- START QUIZ STATUS -->
                  <div class="column-right m-b-0" style="margin-left: auto; margin-right:10px;">

                    @if($quiz_status == 'not available')
                      <div class="card-status text-center" style="background: #FD8596; width:140px;">
                        <h5><b style="color:#fff; font-size:15px;">NOT AVAILABLE</b></h5>
                      </div>
                    @endif

                    @if($quiz_status == 'available')
                      <div class="card-status text-center" >
                        <h5> <b style="color:#fff; font-size:15px;">AVAILABLE</b></h5>
                      </div>
                    @endif

                    @if($quiz_status == 'taken')
                      <div class="card-status text-center" style="background: #FD8596;">
                        <h5><b style="color:#fff; font-size:15px;">TAKEN</b></h5>
                      </div>
                    @endif

                    @if($quiz_status == 'checked')
                      <div class="card-status text-center bg-success">
                        <h5><b style="color:#fff; font-size:15px;">CHECKED</b></h5>
                      </div>
                    @endif
                  </div>
                  <!-- END QUIZ STATUS -->
                </div>

                <!-- START QUIZ TITLE -->
                <div class="row-quiz">
                  <div class="column-left">
                    <h4 style="margin-bottom: 0px;"><b>{{ Str::limit($classQuiz->quiz->title,50) }}</b></h4>
                  </div>
                </div>
                <!-- END QUIZ TITLE -->

                <!-- START QUIZ DESCRIPTION -->
                <div class="row-quiz m-l-20">
                  <p>{!! Str::limit($classQuiz->quiz->description,50) !!}</p>
                </div>
                <!-- END QUIZ DESCRIPTION -->

                <!-- START QUIZ INFORMATION -->
                <div class="row-quiz">
                  <div class="card-info m-l-20 m-r-20 m-b-15">
                    <p class="mb-0 m-l-10"><strong>No. of Items : </strong> {{ $classQuiz->quiz->total_questions }}</p>
                    @if($submitted_quiz)
                      <p class="text-muted mb-0 m-l-10"><strong>Score : </strong>{{ $submitted_quiz->student_score }}</p>
                      <p class="text-muted mb-0 m-l-10"><strong>Total : </strong>{{ $submitted_quiz->total_score }}</p>
                    @else
                      @if($classQuiz->quiz)
                        <p class="text-muted mb-0 m-l-10"><strong>Total : </strong>{{ $classQuiz->quiz->total_score }}</p>
                      @endif
                    @endif
                    <p class="text-muted mb-0 m-l-10"><strong>Start Date : </strong>{{ date('F j, Y h:m a', strtotime($classQuiz->start_at)) }}</p>
                    <p class="text-muted mb-0 m-l-10"><strong>End Date : </strong>{{ date('F j, Y  h:m a', strtotime($classQuiz->end_at)) }}</p>
                  </div>
                </div>
                <!-- END QUIZ INFORMATION -->

                <!-- START QUIZ BUTTON -->
                <div class="row-quiz">
                  <div class="col-md-12 p-0">
                    @if($quiz_status == 'available')
                      <form target="_blank" action="{{ url('student/online-class-quizzes/' . $classQuiz->id . '/start') }}" method="POST">
                        @csrf
                        <button class="btn box-button-one w-100">Take Quiz Now</button>
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
        <div class="p-t-20 p-b-20">
          <img class="img-responsive" src="{{asset('/images/icons/assignment.png')}}" alt="Loading..." style="margin: auto; height: 130px;">
          <h3 class="text-center">No Quiz Yet.</h3>
        </div>
      @endif
      <!-- END OF QUIZ LIST -->
    </div>

    <div class="col-md-4 col-lg-4 col-two">
      <!-- START RIGHT SIDE BAR -->
      
      <div class="col-md-12 col-lg-12 p-l-20">
        <div class="row-text">
          <div class="column-text-left" >
             <h3 class="oc-header-title " style="font-size:19px; color:#2683B9;"><b>My Classes</b> </h3>
          </div>
            <div class="column-text-right" style="align-items: right">
              <a href="{{ asset('student/online-class') }}">
                 <h3 class="oc-header-title" style="font-size:14px; color:#a5a5a5;"><b>View all</b> </h3>
              </a>
            </div>
        </div>
      </div>

     <div class="col-md-12 col-lg-12 oc">
       <div class="" style="">
         <div class="" style="">
           @if($my_classes)
             @if(count($my_classes)>0)
               @php
                 $class_count = 0;
               @endphp
               @foreach($my_classes as $my_class)

                 @if($class_count%2 == 0)
                 @endif
                  <div class="row">
                   <div class="col-xs-12 col-md-12 col-lg-12">
                       <div class="box shadow">
                         <div class="box-body" style="padding: 20px !important;">
                           <span class="dot" style="position: absolute; z-index: 999; height: 55%; background-color:{{ $my_class->color }};"></span>
                           <div class="row">
                             <div class="">
                               
                               <!-- Right Circle -->
                               <span class="dot" style="right: 10px; width: 10px; height: 10px; background-color: {{$my_class->ongoing ? '#1cc88a' : '#e1e1e1'}};"></span>

                               <!-- Subject Code and Class Code -->
                               <h6 class = "class-desc"> 
                                   {{ $my_class->subject_code }}
                               </h6>

                               <!-- Class Name -->
                               <a href="{{ asset('student/online-post?class_code='.$my_class->code) }}">
                                 @if($my_class->subject)
                                   <h4 class="class-header">
                                     {{ $my_class->subject->subject_title ? $my_class->subject->subject_title : '-' }}
                                     {{ $my_class->summer ? '(Summer)' : '' }}
                                   </h4>
                                 @else
                                   <h4>Unknown Subject {{ $my_class->summer ? '(Summer)' : '' }} </h4>
                                 @endif
                               </a>

                               <!-- Class Teacher -->
                               <h6 class = "class-desc">
                                 {{ $my_class->teacher ? $my_class->teacher->prefix . '. ' .  $my_class->teacher->full_name  : 'Unknown Teacher' }}
                               </h6>

                               <!-- Grade and Section -->
                               @if($my_class->section)
                                 <h6 class = "class-desc">
                                     {{ $my_class->section->name_level }} | {{ $my_class->section->track_code }}
                                 </h6>
                               @endif

                               <!-- Video Conference Status -->
                               {{-- <h5 style="padding: 5px 15px 0px 30px; margin-top: 0px; margin-bottom: 0px;">
                                 <span id="video_conference" class="badge {{ $my_class->conference_status ?  'label-success' : 'label-default' }} smo-vc">
                                   <i class="fa fa-video-camera"></i> 
                                   {{ $my_class->conference_status ?  'Video Conference On-going' : 'No On-going Video Conference' }}  
                                 </span>
                               </h5> --}}
                               <h5 style="padding: 5px 15px 0px 30px; margin-top: 0px; margin-bottom: 0px;">
                                 <span id="video_conference" class="badge {{ $my_class->ongoing ?  'label-success' : 'label-default' }} smo-vc">
                                   <i class="fa fa-video-camera"></i> 
                                   {{ $my_class->ongoing ?  'Class is on going' : 'No On-going Class' }}  
                                 </span>
                               </h5>

                             </div>
                           </div>
                         </div>
                       </div>
                   </div>

                  </div>
                 @if($class_count%2 != 0)   
                 @elseif($loop->last)
                   </div>
                 @endif
                 
                 @php
                 if($class_count == 3){
                   break;
                 }else{
                   $class_count =  $class_count + 1;
                 }
                 @endphp
               @endforeach
             @else
               <div>
                 <div class="col-xs-12 col-md-12 col-lg-12">
                   <div class="box" style="border-radius: 5px;">
                     <div class="box-body" style="padding: 10px; height: 120px;">
                       <div class="text-center" style="margin-top: 40px !important;">
                         <h3 class="text-center">
                           No available class
                         </h3>
                       </div>
                     </div>
                   </div>
                 </div>
               </div>
             @endif
           @else
             <div>
               <div class="col-xs-12 col-md-12 col-lg-12">
                 <div class="box" style="border-radius: 5px;">
                   <div class="box-body" style="padding: 10px; height: 120px;">
                     <div class="text-center" style="margin-top: 40px !important;">
                       <h3 class="text-center">
                         No available class
                       </h3>
                     </div>
                   </div>
                 </div>
               </div>
             </div>
           @endif
         </div>
       </div>
     <!-- END SECTION CLASS POSTS -->
      <!-- END RIGHT SIDE BAR -->
    </div>
    
  </div>
  </body>
  <script type="text/javascript">
    document.getElementById("nav-quiz").classList.add("active");
  </script>

@endsection
