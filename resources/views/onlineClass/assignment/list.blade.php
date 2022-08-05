@extends('backpack::layout')

@section('header')
@endsection

@section('content')  
  <link rel="stylesheet" type="text/css" href="{{ asset('css/onlineclass/class.css') }}">

  <div class="row p-l-20 p-r-20">
    @include('onlineClass/partials/navbar')
  </div>

  <div class="row p-l-20 p-r-20">

    <!-- START OF MAIN FEED -->  
    <div class="col-md-8 col-lg-8 col-one">
      <div class="">
        <div class="info-box add-post shadow">
          <div class="box-body">
            <!-- <div class="row"> -->
              <div class="form-group col-2 col-xs-2 col-sm-2 col-md-1 col-lg-1 p-t-10" style="margin: 0px;">
                <a class="thumbnail form-control" >
                  @if($user->employee->photo)
                    @if(file_exists($user->employee->photo))
                    <img src="{{ asset($user->employee->photo) }}" alt="...">
                    @else
                    <img src="{{ asset('images/headshot-default.png') }}" alt="...">
                    @endif
                  @else
                    <img src="{{ asset('images/headshot-default.png') }}" alt="...">
                  @endif
                </a>
              </div>
              <div class="form-group col-8 col-xs-8 col-sm-8 col-md-10 col-lg-10 p-t-10" style="margin: 0px;">
                  <input style="border: none; margin-top: 5px;" 
                    placeholder="Create an assignment..." 
                    class="form-control" data-toggle="modal" data-target="#formModal">
              </div>
              <div class="form-group col-2 col-xs-2 col-sm-2 col-md-1 col-lg-1 p-t-10" style="margin: 0px; padding: 0px;">
                <button id="btnAddAssignment" type="submit" class="btn btn-primary btn-circle" style="height: 50px; width: 50px; border-radius: 50%; margin-right: 5px;" title="Create Assignment">
                  <i class="fa fa-plus"></i>
                </button>
              </div>
            </div>
          <!-- </div> -->
        </div>
      </div>
      <!-- START ASSIGNMENT LIST -->
      @if(count($assignments)>0)
        @foreach($assignments as $assignment)
          <div class="">
            <div class="box shadow">
              <!-- ASSIGNMENT HEADER -->
              <div class="box-header text-center">
                {{$assignment->class->name}}
              </div>
              <!-- ASSGINMENT BODY -->
              <div class="box-body with-border" style="padding: 20px !important;">
                {{ $assignment->title }}
                {!! $assignment->instructions !!}
                <p class="text-muted"><strong>Due Date: </strong>{{ date('F j, Y', strtotime($assignment->due_date)) }}</p>
              </div>
              <!-- ASSIGNMENT FOOTER -->
              <div class="box-footer no-padding" style="border-bottom-right-radius: 10px; border-bottom-left-radius: 10px;">
                <a class="btn box-button-one w-100" href="{{ url($crud->route . '/' . $assignment->id . '?class_code=' . $assignment->class->code) }}">
                  View
                </a>
              </div>
            </div>
          </div>
        @endforeach
        <!-- ALL ASSIGNMENTS LOADED -->
        <div class="text-center p-b-20">
          <h4> All assignments loaded </h4>
        </div>
      @else
        <div class="p-t-20 p-b-20">
          <img class="img-responsive" src="{{asset('/images/icons/assignment.png')}}" alt="Loading..." style="margin: auto; height: 130px;">
          <h3 class="text-center">No Assignments Yet.</h3>
        </div>
      @endif
      <!-- END OF ASSIGNMENT LIST -->
    </div>
    <!-- END OF MAIN FEED -->

    <!-- START OF RIGHT SIDE BAR -->
    <div class="col-md-4 col-lg-4 col-two">
      <div class="box shadow">
        <div class="box-header with-border m-b-10" style="padding: 10px;">
            <h4 class="" style="padding: 0px !important; margin: 0px !important">
              My Classes
            </h4>
        </div>
        @if($my_classes)
          @if(count($my_classes)>0)
            @foreach($my_classes->take(10) as $class)
            <div class="box-body with-border" style="padding: 0px 10px 10px 10px;">
              <div class="col-md-1 col-xs-1">
                <span class="circle-span" style="background-color:{{ $class->color }};"></span>
              </div>
              <div class="col-md-10 col-xs-10">
                <h5 class="" style="padding: 0px !important; margin: 0px !important">
                  <a href="{{ url($crud->route)}}?class_code={{$class->code }}">
                    {{ $class->name }}
                  </a>
                </h5>
              </div>
            </div>
            @endforeach
          @endif
        @endif
        <div class="box-footer with-border br-b-15" style="padding: 10px;">
          <a href="{{ asset('student/online-class') }}">View all classes</a>
        </div>
      </div>
      <!-- END RIGHT SIDE BAR -->
    </div>
    <!-- END OF RIGHT SIDE BAR -->
  </div>

@endsection

@push('after_styles')
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
@endpush

@push('before_scripts')
  {{-- VUE JS --}}
  <script src="{{ mix('js/onlineclass/assignment.js') }}"></script>
@endpush

@push('after_scripts')
  <script type="text/javascript">
    document.getElementById("nav-assignments").classList.add("active");
  </script>
   <!-- JQUERY CONFIRM -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
@endpush