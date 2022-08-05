@extends('backpack::layout')

@section('header')

@endsection

@section('content')
   <link rel="stylesheet" href="{{ asset('css/onlineclass/ct-navbar.css') }}">

  <style type="text/css">
    .dot {
      background-color: #bbb;
      height: 25px;
      width: 3px;
      border-radius: 5px;
      display: inline-block;
      position: absolute;
      margin-right: 10px;
      padding-right: 10px;
    }
    .w-100 {
      width: 100%;
    }
    .h-100 {
      height: : 100%;
    }
    .box {
      border-radius: 5px;
    }
    .form-control {
       border-radius: 5px;
    }
    .box {
      border: none; box-shadow: 0 0 0 rgba(0,0,0,0) !important;
    }
    .btn {
      border-radius: 5px;
    }
    .form-group {
      border-radius: 5px;
    }
    .select2-container--bootstrap .select2-selection--multiple {
      border-radius: 5px !important; 
    }
    .file-cont {
      width: 100%; 
      height: 40px; 
      border: 1px solid #ccc; 
      border-radius: 5px; 
      background-color: #ddd; 
      display: flex; 
      margin: 10px 0px;
    }

    .file-text {
      padding: 8px;
    }

    .file-icon {
      padding: 5px; 
      border-radius:  5px; 
      background-color: #ddd; 
      text-align: left;
    }

    .file-close {
      float: right !important;
      position: absolute;
      right: 5px;
    }

        .nav-new li{
      width: 200px;
    }

    .nav-new li a{
      padding: 15px;
    }

    .nav-new li a i{
      color: #3c8dbc;

    }

  </style>

  <div id="navbar-default nav-new" class="col-md-12 col-lg-12 col-xs-12" style="border-radius: 5px;margin-top: 15px">
    <nav class="navbar navbar-default nav-new" role="navigation">
      <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
    
        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
          <ul class="nav navbar-nav navbar-left" >
                <li>
                    <a href="#">
                        <i class="fa fa-newspaper-o"></i>
                        <p>Explore</p>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <i class="fa fa-book">
                            {{-- <span class="label">23</span> --}}
                        </i>
                        <p>My Courses</p>
                    </a>
                </li> 
                <li>
                      <a href="#">
                            <i class="fa fa-users"></i>
                            <p>My Classes</p>
                       </a>
                </li>
           </ul>
        </div><!-- /.navbar-collapse -->
      </div><!-- /.container-fluid -->
    </nav>
  </div><!--  end navbar -->

  <div class="row m-t-15">
    <!-- START FIRST ROW -->
    <div class="col-md-12 col-lg-12">
      <!-- START FIRST BOX -->
      <div class="col-md-8 col-lg-8">
        <div class="box" style="border-radius: 5px;">
          <div class="box-body" style="padding: 10px 50px 25px 50px;">
            <h3> {{ $selected_course->name }} </h3>
            <h5> {{ $selected_course->level->year }} </h5>
            @if(isset($selected_course->subject->subject_description))
            <h5> <strong>Subject Description:</strong> {!! $selected_course->subject->subject_description !!} </h5>
            @endif
          </div>
        </div>
      </div>
      <!-- END FIRST BOX -->
      <!-- START SECOND BOX -->
      <div class="col-md-4" style="padding-left: 0px; margin-left: 0px;">
        <div class="box" style="border-radius: 5px;">
          <div class="box-body text-center" style="padding: 25px 25px 25px 25px;;">
            <div class="row">
              <div class="col-6 col-md-6" style="padding-left: 35px; text-align: left;">
                <h5 class=""> <strong> Course Code: </strong></h5>
                @if($selected_course->duration)
                  <h5 class=""> <strong> Duration: </strong></h5>
                @endif
              </div>
              <div class="col-6 col-md-6" style="padding-left: 0px !important; text-align: left;">
                <h5><strong> {{ $selected_course->code }} </strong></h5>
                @if($selected_course->duration)
                  <h5> <strong> {{ $selected_course->duration }} </strong></h5>
                @endif
              </div>
            </div>
            <div class="col-12 col-md-12">
              @if(backpack_auth()->user()->hasRole('School Head') || backpack_auth()->user()->employee_id == $selected_course->teacher_id)
                <a href="{{ url($crud->route.'/'.$selected_course->id.'/edit?course_code='.$selected_course->code) }}" style="margin-right: 10px;" type="submit" class="btn btn-success pull-right" >
                  </span> Edit Course
                </a>
              @endif
            </div>
          </div>
          
        </div>
      </div>
      <!-- END SECOND BOX -->
    </div>
    <!-- END FIRST ROW -->

    <!-- START DESCRIPTION/REQUIREMENTS SECTION -->
    <div class="col-md-8 col-lg-8">
      <div class="col-md-12 col-lg-12">
        <div class="box" style="border-radius: 5px;">
          <div class="box-body text-justify" style="padding: 25px 50px;">
            <h4 style="text-transform: uppercase;"> About this Course </h4>
            <h5><small>{!! $selected_course->description !!}</small></h5>
            @if ( $selected_course->requirements )
              <h4 style="text-transform: uppercase;"> Requirements </h4>
              <h5><small>{!! $selected_course->requirements !!}</small></h5>
            @endif
            @if ( $selected_course->content_standard )
              <h4 style="text-transform: uppercase;"> Content Standard </h4>
              <h5><small>{!! $selected_course->content_standard !!}</small></h5>
            @endif
             @if ( $selected_course->performance_standard )
              <h4 style="text-transform: uppercase;"> Performance Standard </h4>
              <h5><small>{!! $selected_course->performance_standard !!}</small></h5>
            @endif
          </div>
        </div>
      </div>
    </div>
    <!-- END DESCRIPTION/REQUIREMENTS SECTION -->
    @if($selected_course->modules)
      @if(count($selected_course->modules)>0)
        <!-- START CONTENT/MODULES SECTION -->
        <div class="col-md-12 col-lg-12">
          <div class="col-md-12 col-lg-12">
            <div class="box" style="border-radius: 5px;">
              <div class="box-body text-justify" style="padding: 25px 50px;">
                <h3>Content</h3>
                <!--Accordion wrapper-->
                <div style="padding: 30px;" class="accordion md-accordion" id="accordionEx" role="tablist" aria-multiselectable="true">
                  @foreach($selected_course->modules as $key => $module)
                      <!-- Accordion card -->
                      <div class="card" @if($loop->last) style="padding-bottom: 20px;" @endif>

                        <!-- Card header -->
                        <div class="card-header" role="tab" id="module-header-{{$key}}">
                          <a data-toggle="collapse" data-parent="#accordionEx" href="#module-body-{{$key}}" aria-expanded="true"
                            aria-controls="module-body-{{$key}}">
                            <h5 class="mb-0 btn btn-lg btn-block" style="margin: 0px; color: #000; background-color: #3c8dbc; color:white; border: 1px solid #ccc; margin-bottom: 5px;">
                              <span class="pull-left" style="padding: 0 20px 0 0;"> {{ $key+1 }} </span>
                              <p class="" style="margin: 0; text-align: left"> {{ $module->title }} </p> 
                            </h5>
                          </a>
                        </div>
                      
                        <!-- Card body -->
                        <div id="module-body-{{$key}}" class="collapse" role="tabpanel" aria-labelledby="module-header-{{$key}}"
                          data-parent="#accordionEx">
                          <div class="card-body" style="padding: 5px 50px; border: 0px ; background-color:#f7f7f7;  border-radius: 5px; margin-bottom: 5px;">
                            @if($module->topics)
                              @if(count($module->topics)>0)
                                @foreach($module->topics as $topic)
                                  @if(Request::get('class_code'))
                                    @php
                                      $topicURL = 'admin/online-class-topic?class_code='.request()->class_code.'&course_code='.$selected_course->code.'&module_id='.$module->id.'&topic_id='.$topic->id;
                                    @endphp
                                  @else
                                    @php
                                      $topicURL = 'admin/online-class-topic?course_code='.$selected_course->code.'&module_id='.$module->id.'&topic_id='.$topic->id;
                                    @endphp
                                  @endif
                                  <a href="{{ asset($topicURL) }}"><p style="color: #000;">{{ $topic->title }}</p></a>
                                @endforeach
                              @else
                                <p style="color: #000;">No Topic Available</p>
                              @endif
                            @else
                              <p style="color: #000;">No Topic Available</p>
                            @endif
                          </div>
                        </div>

                      </div>
                      <!-- Accordion card -->
                  @endforeach
                </div>
                <!-- Accordion wrapper -->
              </div>
            </div>
          </div>
        </div>
        <!-- END CONTENT/MODULES SECTION -->
      @endif
    @endif
  </div>

@endsection

@section('after_styles')
  <style type="text/css">
    .with-border {
      border: 1px solid #6c757d !important;
    }
  </style>
  <!-- DATA TABLES -->
  <link href="https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap.min.css" rel="stylesheet" type="text/css" />
  <link rel="stylesheet" href="https://cdn.datatables.net/fixedheader/3.1.5/css/fixedHeader.dataTables.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.1/css/responsive.bootstrap.min.css">

  <link rel="stylesheet" href="{{ asset('vendor/backpack/crud/css/crud.css') }}">
  <link rel="stylesheet" href="{{ asset('vendor/backpack/crud/css/form.css') }}">
  <link rel="stylesheet" href="{{ asset('vendor/backpack/crud/css/list.css') }}">

  <!-- CRUD LIST CONTENT - crud_list_styles stack -->
  @stack('crud_list_styles')
@endsection

@section('after_scripts')
	@include('crud::inc.datatables_logic')

  <script src="{{ asset('vendor/backpack/crud/js/crud.js') }}"></script>
  <script src="{{ asset('vendor/backpack/crud/js/form.js') }}"></script>
  <script src="{{ asset('vendor/backpack/crud/js/list.js') }}"></script>
  <!-- DELETE CLASS -->

  <!-- CRUD LIST CONTENT - crud_list_scripts stack -->
  @stack('crud_list_scripts')
@endsection
