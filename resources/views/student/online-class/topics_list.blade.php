@extends('backpack::layout_student')

@section('header')

@endsection

@section('content')
  <style type="text/css">
     .dot {
      height: 12px;
      width: 12px;
      background-color: #bbb;
      border-radius: 15px;
      display: inline-block;
    }
    .w-100 {
      width: 100%;
    }
    .h-100 {
      height: : 100%;
    }
    .box {
      border-radius: 10px;
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
  </style>

  @if($class)
  <div class="row">
    <!--START CLASS INFORMATION -->
    <div class="col-md-12 col-lg-12">
      <div class="m-t-15">
          <div class="col-md-12" style="padding-left: 0px; padding-right: 0px;">
            <div class="box" style="border-radius: 10px;">
              <div class="box-header with-border" style="padding: 20px !important;">
                <span class="dot" style=" position: absolute; height: 70%; width: 10px; background-color:{{ $class->color }};"></span>
                <h1 style="padding: 0px 15px 0px 15px; margin: 0px !important">
                   {{ $class->name }}
                </h1>
                <h4 style="padding: 0px 15px 0px 15px; margin-top: 0px;  margin-bottom: 0px;">
                  {{ $class->teacher ? $class->teacher->prefix . '. ' .  $class->teacher->full_name  : 'Unknown Teacher' }} |  {{ $class->subject ? $class->subject->subject_title : 'Unknown Subject Title' }}
                </h4>
              </div>
            </div>
          </div>
      </div>
    </div>
    <!-- END CLASS INFORMATION -->
  </div>
  <div class="row">
    <div class="col-12">
      <h3 style="padding: 0px 15px 15px 15px; margin-top: 0px;  margin-bottom: 0px;">Module: {{$module->title ?? 'Unknown'}}</h3>
    </div>
  </div>
  @endif
  
  <div class="row">
    <div class="col-md-8 col-lg-8" style="border-radius: 10px;">

      <!-- START SECTION CLASS POSTS -->
      <div class="">
        <div class="col-xs-12 col-md-12 col-lg-12" style="padding-left: 0px; padding-right: 0px;">
          <div class="box" style="border-radius: 10px;">
            <div class="box-header with-border" style="padding: 20px !important;">
              <div class="row">
                <div style="padding: 10px 20px;">
                  <h4 style="padding-bottom: 10px;">Topics</h4>
                  <div style="padding: 20px 30px;">
                    @if($topics)
                      @if(count($topics)>0)
                        @foreach($topics as $key => $topic)
                          <a href="{{ asset('student/online-class-topic/'.$class->code) .'/'. $topic->module->id .'/'. $topic->id }}">
                            <h5 class="mb-0 btn-lg btn-block" style="margin: 0px; color: #000; background-color: #D9DDDC; border: 1px solid; @if($loop->last) margin-bottom: 20px; @endif">
                              <span class="pull-left" style="padding: 0 20px 0 0;"> {{ $key+1 }} </span>
                              <p class="" style="margin: 0; text-align: left"> {{ $topic->title }} </p> 
                            </h5>
                          </a>
                        @endforeach
                      @else
                        <div class="box" style="border-radius: 10px;">
                          <div class="box-body" style="padding: 10px; padding-bottom: 0; margin-bottom: 0;">
                              <h4 class="text-center" style="padding: 0px !important; margin: 0px !important">
                                No available topic
                              </h4>
                          </div>
                        </div>
                      @endif
                    @else
                      <div class="box" style="border-radius: 10px;">
                        <div class="box-body" style="padding: 10px; padding-bottom: 0; margin-bottom: 0;">
                          <a href="#" style="padding: 0 50px;" data-toggle="modal" data-target="#formModal">+ Add Topic</a>
                            <h4 class="text-center" style="padding: 0px !important; margin: 0px !important">
                              No available topic
                            </h4>
                        </div>
                      </div>
                    @endif
                  </div>
                </div>
              </div>
            </div>
           <!--  <div class="box-body" style="padding: 20px;">
              <div class="">
                
              </div>
            </div> -->
          </div>
        </div>
      </div>
      <!-- END SECTION CLASS POSTS -->
    </div>

    <div class="col-md-4" style="padding-left: 0px; padding-right: 0px; margin-left: 0px; margin-right: 0px;">
      <!-- START RIGHT SIDE BAR -->
          <div class="col-xs-12 col-md-12 col-lg-12">
            <div class="box" style="border-radius: 10px;">
              <div class="box-footer with-border" style="padding: 10px; border-radius: 10px;">
                  <h4 class="" style="padding: 0px !important; margin: 0px !important">
                    My Classes
                  </h4>
              </div>
               @if(count($my_classes)>0)
                @foreach($my_classes as $my_class)
                  @if($my_class->course)
                    <div class="box-body with-border" style="padding: 10px;">
                      <div class="container">
                        <h4 class="" style="padding: 0px !important; margin: 0px !important">
                          <span class="dot" style="background-color:{{ $my_class->color }};"></span> <a href="{{ asset('student/online-class/course/'.$my_class->code) }}">{{ $my_class->name }}</a>
                        </h4>
                      </div>
                    </div>
                  @endif
                @endforeach
              @endif
              <div class="box-header with-border" style="border-radius: 10px; padding: 10px;">
                <a href="{{ asset('student/online-class') }}">View all classes</a>
              </div>
              <div class="box-footer with-border" style="padding: 10px;">
                  <h4 class="" style="padding: 0px !important; margin: 0px !important">
                    Modules
                  </h4>
              </div>
              @if(count($module->course->modules)>0)
                @foreach($module->course->modules as $my_module)
                  <div class="box-body with-border" style="padding: 10px;">
                    <div class="container">
                      <h4 class="" style="padding: 0px !important; margin: 0px !important">
                        <span class="dot" style="background-color:{{ $class->color }};"></span> <a href="{{ asset('student/online-class-topic/'.$class->code).'/'.$my_module->id }}">{{ $my_module->title }}</a>
                      </h4>
                    </div>
                  </div>
                @endforeach
              @endif
            </div>
          </div>
      <!-- END RIGHT SIDE BAR -->
    </div>
    
  </div>
@endsection