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

    <!-- START OF MAIN CONTENT -->  
    <div class="col-md-8 col-lg-8 col-one">

      	<!-- START ASSIGNMENT INFORMATION -->
      	<div class="">
	        <div class="box shadow">
	        	<div class="box-body with-border" style="padding: 20px !important;">
		            {{ $quiz->title }}
		            <br>
		            {!! $quiz->description !!}
		            <p class="text-muted"><strong>Due Date: </strong>{{ date('F j, Y', strtotime($quiz->due_date)) }}</p>
	          	</div>
	        </div>
      	</div>
      	<!-- END ASSIGNMENT INFORMATION -->

      	<!-- START CLASS STUDENT LIST -->
      	<div class="">
	        <div class="box shadow">
	          	<div class="box-body with-border" style="padding: 20px !important;">
		            @if($students)
			            @if(count($students)>0)
			              	<table class="table table-striped table-bordered">
				                <thead class="thead-dark">
				                  	<tr>
					                    <th scope="col">No.</th>
					                    <th scope="col">Name</th>
					                    <th scope="col">Status</th>
					                    <th scope="col">Score</th>
					                    <th scope="col">Action</th>
				                  	</tr>
				                </thead>
				                <tbody>
				                  	@foreach($students as $key => $student)
					                  	@php
					                  		$studentQuizResult = $student->submittedQuizzes;
					                  		if(count($studentQuizResult)>0)
					                  		{
					                  			$studentQuizResult = $studentQuizResult = $student->submittedQuizzes->first();
					                  		}
					                  		else
					                  		{
					                  			$studentQuizResult = null;
					                  		}
					                  	@endphp
				                    	<tr>
				                      		<td scope="row">{{ $key+1 }}</td>
				                      		<td>{{ $student->fullname_last_first }}</td>
				                      		<!-- Student Quiz Status -->
				                      		<td>
				                      			<span class="badge {{ $studentQuizResult ? 'badge-success' : 'badge-default' }}">
				                      				@if($studentQuizResult)
				                      					{{ $studentQuizResult->is_check ? 'Checked' : 'Submitted' }}
				                      				@else
				                      					Not Yet Submitted
				                      				@endif
				                      			</span>
				                      		</td>
				                      		<!-- Student Score -->
				                      		<td> 
					                      			{{ $studentQuizResult ? $studentQuizResult->score . '/' . $studentQuizResult->total_score : '0/' . $quiz->total_score }} 
					                      	</td>
				                      		<td class="text-center">
						                      	@if($studentQuizResult)
						                      		<a href="{{ url('admin/online-class/student-quiz-result/' . $studentQuizResult->id) }}" class="btn btn-xs btn-primary action-btn" style="width: 100%;">View</a>
						                      	@else
						                      	-
						                      	@endif
				                      		</td>
				                    	</tr>
				                  	@endforeach
				                </tbody>
			              	</table>
			            @else
			              <div class="box" style="border-radius: 10px;">
			                <div class="box-body" style="padding: 10px; padding-bottom: 0; margin-bottom: 0;">
			                  <h4 class="text-center" style="padding: 0px !important; margin: 0px !important">
			                    No Student Enrolled In This Class
			                  </h4>
			                </div>
			              </div>
			            @endif
			          @else
			            <div class="box" style="border-radius: 10px;">
			              <div class="box-body" style="padding: 10px; padding-bottom: 0; margin-bottom: 0;">
			                <h4 class="text-center" style="padding: 0px !important; margin: 0px !important">
			                  No Student Enrolled In This Class
			                </h4>
			              </div>
			            </div>
			          @endif
	          	</div>
	        </div>
      	</div>
      	<!-- END CLASS STUDENT LIST -->

    </div>
    <!-- END OF MAIN CONTENT -->
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
  <script type="text/javascript">
    document.getElementById("nav-assignments").classList.add("active");
  </script>

@endsection

@push('after_styles')
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
@endpush

@push('after_scripts')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
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
@endpush
