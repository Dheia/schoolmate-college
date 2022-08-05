@extends('backpack::layout')

@section('header')
@endsection

@section('content')  
  <link rel="stylesheet" type="text/css" href="{{ asset('css/onlineclass/class.css') }}">

  <div class="row p-l-20 p-r-20">
    @include('onlineClass/partials/navbar')
  </div>

  <div class="row p-l-20 p-r-20">

    <!-- START RIGHT SIDEBAR -->
    @include('onlineClass/partials/right_sidebar')
    <!-- END RIGHT SIDEBAR -->

    <!-- START OF MAIN FEED -->  
    <div class="col-md-8 col-lg-8 col-one">

      <!-- START ASSIGNMENT INFORMATION -->
      <div class="">
        <div class="box shadow">
          <div class="box-body with-border" style="padding: 20px !important;">
            <div class="col-md-12 col-lg-12"> 
              {{ $quiz->title }}
              {!! $quiz->description !!}
              <p class="text-muted"><strong>Due Date: </strong>{{ date('F j, Y', strtotime($classQuiz->due_date)) }}</p>
            </div>
          </div>
        </div>
      </div>
      <!-- END ASSIGNMENT INFORMATION -->

      <!-- START STUDENT'S SUBMIITED ASSIGNMENT -->
      @if($entry)
        <div class="box shadow">
          <div class="box-body with-border" style="padding: 20px !important;">

            <!-- START STUDENT INFORMATION -->
            <div class="col-md-12 col-lg-12">
              <label class="pt-5">Submitted by:</label>
              <table class="table table-bordered mb-0">
                  <tbody>
                      <tr>
                          <td><b><small>Student ID:</small></b></td>
                          <td id="studentID"> {{ $student->studentnumber }} </td>
                          
                          <td><b><small>Fullname:</small></b></td>
                          <td id="fullname"> {{ $student->fullname }} </td>

                          <td><b><small>Date:</small></b></td>
                          <td id="year">{{ date('F j, Y - h:i A', strtotime($entry->created_at)) }}</td>
                          {{-- <td id="year">{{ date('F j, Y', strtotime($entry->created_at)) }}</td> --}}
                      </tr>
                  </tbody>
              </table>
            </div>
            <!-- END STUDENT INFORMATION -->

            <!-- STUDENT SUBMITTED QUIZ -->
            <!-- <student-quiz-result :id="{{$entry->id}}"></student-quiz-result> -->
            <student-quiz-result :id="{{$entry->id}}" :quiz_id="{{$quiz->id}}"></student-quiz-result>
            <!-- END STUDENT SUBMITTED QUIZ -->

          </div>
        </div>
      @endif
      <!-- END STUDENT'S SUBMIITED ASSIGNMENT -->

    </div>
    <!-- END OF MAIN FEED -->
  </div>

  {{-- START ONLINE CLASS FORM --}}
  @if(backpack_auth()->user()->employee_id == $class->teacher_id || $class->isTeacherSubstitute)
    <form action="{{url('admin/teacher-online-class/video_conference') }}" method="GET" target="_blank" id="form{{ $class->code }}">
      @csrf
      <input type="hidden" name="_method" value="GET">
      <input type="hidden" name="classid" value="{{$class->id}}">
      <input type="hidden" name="class_code" value="{{$class->code}}">
    </form>
  @endif

@endsection

@push('after_styles')
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
  <link href="https://unpkg.com/survey-vue/survey.min.css" type="text/css" rel="stylesheet"/>
@endpush

@push('after_scripts')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>

  <script type="text/javascript">
    document.getElementById("nav-quizzes").classList.add("active");
  </script>

  <script>
    $('.startVideoConferencing').click(function () {
      var classCode = $(this).attr('classcode');
      console.log(classCode);
      $('#form' + classCode).submit();
    });

    $('#btnAddAssignment').click(function () {
      window.location.href = "{{ url('admin/online-class/assignment/create?class_code=' . $class->code) }}";
    });
  </script>

  {{-- VUE JS --}}
  {{-- <link rel="stylesheet" href="{{ mix('css/app.css') }}"> --}}
  <script src="{{ mix('js/onlineclass/quiz.js') }}"></script>
@endpush
