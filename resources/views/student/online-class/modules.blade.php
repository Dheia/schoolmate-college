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
              <!-- <div class="box-body" style="padding: 10px;">
                <div class="container">
                  <h4> {!! $class->description !!} </h4>
                </div>
              </div> -->
            </div>
          </div>
      </div>
    </div>
    <!-- END CLASS INFORMATION -->
  </div>
  <div class="row">
    <div class="col-12">
      <h3 style="padding: 0px 15px 15px 15px; margin-top: 0px;  margin-bottom: 0px;">Manage Modules</h3>
    </div>
  </div>
  @endif
  <div class="row">
    <div class="col-md-8 col-lg-8" style="border-radius: 10px;">

      <!-- START SECTION CLASS POSTS -->
        <div class="">
          <div class="col-xs-12 col-md-12 col-lg-12" style="padding: 0px; padding-right: 0px;">
            <div class="box" style="border-radius: 10px;">
              <div class="box-body" style="padding: 20px;">
                <div class="text-justify" style="padding: 10px 10px;">
                  <h4>Modules</h4>
                  @if(count($modules)>0)
                  <!--Accordion wrapper-->
                  <div style="padding: 30px;" class="accordion md-accordion" id="accordionEx" role="tablist" aria-multiselectable="true">
                    @foreach($modules as $key => $module)
                        <!-- Accordion card -->
                        <div class="card" @if($loop->last) style="padding-bottom: 20px;" @endif>

                          <!-- Card header -->
                          <div class="card-header" role="tab" id="module-header-{{$key}}">
                            <a data-toggle="collapse" data-parent="#accordionEx" href="#module-body-{{$key}}" aria-expanded="true"
                              aria-controls="module-body-{{$key}}">
                              <h5 class="mb-0 btn btn-lg btn-block" style="margin: 0px; color: #000; background-color: #D9DDDC; border: 1px solid;">
                                <span class="pull-left" style="padding: 0 20px 0 0;"> {{ $key+1 }} </span>
                                <p class="" style="margin: 0; text-align: left"> {{ $module->title }} </p> 
                              </h5>
                            </a>
                          </div>
                        
                          <!-- Card body -->
                          <div id="module-body-{{$key}}" class="collapse" role="tabpanel" aria-labelledby="module-header-{{$key}}"
                            data-parent="#accordionEx">
                            <div class="card-body" style="padding: 5px 50px; border: 1px solid; border-radius: 5px;">
                              @if($module->topics)
                                @if(count($module->topics)>0)
                                  @foreach($module->topics as $topic)
                                    <p style="color: #000;">{{ $topic->title }}</p>
                                  @endforeach
                                @endif
                              @endif
                              @if($module->topics)
                                @if(count($module->topics)>0)
                                  <div style="text-align: right;">
                                    <a href="{{ asset('student/online-class-topic/'.$module->course->code.'/'.$module->id) }}" style="right: 0; padding: 0px;">+ Manage Topics</a>
                                  </div>
                                @endif
                              @endif
                            </div>
                          </div>

                        </div>
                        <!-- Accordion card -->
                    @endforeach
                  </div>
                  <!-- Accordion wrapper -->
                  @else
                    <div class="box" style="border-radius: 10px;">
                      <div class="box-body" style="padding: 10px; padding-bottom: 0; margin-bottom: 0;">
                          <h4 class="text-center" style="padding: 0px !important; margin: 0px !important">
                            No available module
                          </h4>
                      </div>
                    </div>
                  @endif
                      
                </div>
              </div>
            </div>
          </div>
        </div>
      <!-- END SECTION CLASS POSTS -->
    </div>

    <div class="col-md-4" style="padding-left: 0px; padding-right: 0px; margin-left: 0px; margin-right: 0px;">
      <!-- START RIGHT SIDE BAR -->
          <div class="col-xs-12 col-md-12 col-lg-12">
            <div class="box" style="border-radius: 10px;">
              <div class="box-header with-border" style="padding: 10px;">
                  <h4 class="" style="padding: 0px !important; margin: 0px !important">
                    My Classes
                  </h4>
              </div>
              @if(count($my_classes)>0)
                @foreach($my_classes as $my_class)
                  @if($my_class->course)
                    <div class="box-body with-border" style="padding: 0px 10px 10px 10px;">
                      <div class="col-md-1 col-xs-1">
                        <span class="circle-span" style="background-color:{{ $my_class->color }};"></span>
                      </div>
                      <div class="col-md-10 col-xs-10">
                        <h5 class="" style="padding: 0px !important; margin: 0px !important">
                          <a href="{{ asset('student/online-class/course/'.$my_class->code) }}">
                            {{ $my_class->name }}
                          </a>
                        </h5>
                      </div>
                    </div>
                  @endif
                @endforeach
              @endif
              <div class="box-footer with-border" style="border-radius: 10px; padding: 10px;">
                <a href="{{ asset('student/online-class') }}">View all classes</a>
              </div>
            </div>
          </div>
      <!-- END RIGHT SIDE BAR -->
    </div>
    
  </div>

@endsection

