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
  </style>
  
  <body style="background: #3c8dbc;">
  <div class="row p-l-30 p-r-30">
    @include('student/online-class/partials/navbar')
  </div>
  
  <div class="row p-l-30 p-r-30">

    <!-- START RIGHT SIDEBAR -->
    <div class="col-md-4 col-lg-4 col-two">
      <!-- START CLASS INFORMATION -->
      @include('student/online-class/partials/class_information')
      <!-- END CLASS INFORMATION -->

      <!-- Start Quipper Account -->
      @include('student/online-class/partials/quipperAccount')
      <!-- END Quipper Account -->
    </div>
    <!-- END RIGHT SIDEBAR -->

    <div class="col-md-8 col-lg-8 col-one" style="border-radius: 5px;">

       <!-- START QUIZ LIST -->
      @if(count($classQuizzes)>0)
        @foreach($classQuizzes as $classQuiz)
          @if($classQuiz->quiz)
            <div class="">
              <div class="box shadow">
                <!-- QUIZ HEADER -->
                {{-- <div class="box-header text-center">
                  <b>{{$classQuiz->onlineClass->name}}</b>
                </div> --}}
                <!-- QUIZ BODY -->
                <div class="box-body with-border" style="padding: 20px !important;">
                  <h4><b>{{ $classQuiz->quiz->title }}</b></h4>
                  {!! $classQuiz->quiz->description !!}
                  <p class="text-muted"><strong>Due Date: </strong>{{ date('F j, Y', strtotime($classQuiz->end_at)) }}</p>
                </div>
                <!-- QUIZ FOOTER -->
                <div class="box-footer no-padding" style="border-bottom-right-radius: 10px; border-bottom-left-radius: 10px;">
                  @if( now()->toDatetimeString() >= $classQuiz->start_at)
                    @if(now()->toDatetimeString() <= $classQuiz->end_at || $classQuiz->allow_late_submission)
                      @if(!in_array($classQuiz->id, $student->submittedQuizzes->pluck('online_class_quiz_id')->toArray()) || $classQuiz->allow_retake)
                        <form target="_blank" action="{{ url('student/online-class-quizzes/' . $classQuiz->id . '/start') }}" method="POST">
                          @csrf
                            <button class="btn box-button-one w-100">Start Quiz</button>
                        </form>
                      @else
                        <button class="btn box-button-one w-100 disabled">Disabled</button>
                      @endif
                    @else
                      <button class="btn box-button-one w-100 disabled">Disabled</button>
                    @endif
                  @else
                    <button class="btn box-button-one w-100 disabled">Disabled</button>
                  @endif
                </div>
              </div>
            </div>
          @endif
        @endforeach
        <!-- ALL ASSIGNMENTS LOADED -->
        <div class="text-center p-b-20">
          <h4> All quizzes loaded </h4>
        </div>
      @else
        <div class="p-t-20 p-b-20">
          <img class="img-responsive" src="{{asset('/images/icons/assignment.png')}}" alt="Loading..." style="margin: auto; height: 130px;">
          <h3 class="text-center">No Quizzes Yet.</h3>
        </div>
      @endif
      <!-- END OF QUIZ LIST -->
    </div>
    
  </div>

  <script type="text/javascript">
    document.getElementById("nav-quiz").classList.add("active");
  </script>
  </body>
@endsection
