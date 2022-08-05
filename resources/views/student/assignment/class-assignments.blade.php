@extends('backpack::layout_student')

@section('header')
@endsection

@section('content')
  <link rel="stylesheet" type="text/css" href="{{asset('css/onlineclass/class.css')}}">

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

      <!-- START ASIGNMENTS -->
      @if(count($assignments)>0)
        @foreach($assignments as $assignment)
          <div class="">
            <div class="box shadow">
              <div class="box-body with-border" style="padding: 20px !important;">
                <div class="col-md-12 col-lg-12">
                  {{ $assignment->title }}
                  {!! $assignment->instructions !!}

                  <!-- DUE DATE -->
                  <p class="text-muted"><strong>Due Date: </strong>{{ date('F j, Y', strtotime($assignment->due_date)) }}</p>

                  <!-- STATUS -->
                  <p class="text-muted"><strong>Status: </strong>
                    {{$submittedAssignments ? $submittedAssignments->where('assignment_id', $assignment->id)->pluck('status')->first() : 'Not Yet Submitted'}}
                  </p>
                  
                  <a class="btn btn-default pull-right" href="{{ url('student/online-class-assignments?id=' . $assignment->id) }}">
                    {{-- <span class="glyphicon glyphicon-star" aria-hidden="true"></span> --}} 
                    View
                  </a>
                </div>
              </div>
            </div>
          </div>
        @endforeach
        <!-- ALL POST LOADED -->
        <div class="">
          <div class="box" style="border-radius: 5px;">
            <div class="box-body" style="padding: 0px;">
              <div class="text-center">
                <h4> All assignments loaded </h4>
              </div>
            </div>
          </div>
        </div>
      @else
        <div class="p-t-20 p-b-20">
          <img class="img-responsive" src="{{asset('/images/icons/assignment.png')}}" alt="Loading..." style="margin: auto; height: 130px;">
          <h3 class="text-center">No Assignments Yet.</h3>
        </div>
      @endif
      <!-- END ASIGNMENTS -->
    </div>
    
  </div>

  <script type="text/javascript">
    document.getElementById("nav-assignment").classList.add("active");
  </script>

@endsection
