@extends('backpack::layout')

@section('header')
	
@endsection

@section('content')
  <link rel="stylesheet" href="{{ asset('css/onlineclass/class.css') }}">

  <div class="row p-l-20 p-r-20">
    @include('onlineClass.partials/navbar')
  </div>

  @if(request()->course_code)
  <div class="row p-l-20 p-r-20">
    <!--START CLASS INFORMATION -->
    <div class="col-md-12 col-lg-12 no-padding">
      <div class="">
        <div class="col-md-12" style="padding-left: 0px; padding-right: 0px;">
          <div class="box" style="border-radius: 5px;">
            <div class="box-header with-border" style="padding: 20px !important;">
              <span class="dot" style=" position: absolute; height: 60%; width: 10px; background-color:{{ $course->color }};"></span>
              <h2 style="padding: 0px 15px 0px 15px; margin: 0px !important">
                 {{ $course->name }}
              </h2>
              <h5 style="padding: 0px 15px 0px 15px; margin-top: 0px;  margin-bottom: 0px;">
                {{ $course->teacher->prefix }}. {{ $course->teacher->firstname }} {{ $course->teacher->lastname }} | {{ $course->subject->subject_title ?? 'Unknown' }}
              </h5>
            </div>
            <!-- <div class="box-body" style="padding: 10px;">
              <div class="container">
                <h4> {!! $course->description !!} </h4>
              </div>
            </div> -->
          </div>
        </div>
      </div>
    </div>
    <!-- END CLASS INFORMATION -->
  </div>
{{--   <div class="row p-l-20 p-r-20">
    <div class="col-12">
      <h3 style="padding: 0px 15px 15px 15px; margin-top: 0px;  margin-bottom: 0px;">Manage Modules</h3>
    </div>
  </div> --}}

  @endif
  <div class="row p-l-20 p-r-20">
    <div class="col-md-8 col-lg-8 col-one" style="border-radius: 5px;">

      <!-- START SECTION CLASS POSTS -->
      <div @if(!request()->class_code)  @endif>
        <div class="box" style="border-radius: 5px;">
          <div class="box-body" style="padding-left: 20px;">
            <div class="text-justify" style="padding: 0;">
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
                        <h5 class="mb-0 btn btn-lg btn-block" style="margin: 0px; color: #000; background-color: #3c8dbc; color:white; border: 1px solid #ccc; margin-bottom: 5px; ">
                          <b><span class="pull-left" style="padding: 0 20px 0 0;"> {{ $key+1 }} </span></b>
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

                              <h5>  
                                
                                <a href="{{ asset('admin/online-class-topic?course_code='.$topic->course->code.'&module_id='.$topic->module->id.'&topic_id='.$topic->id) }}">
                                <p style="color: #000;">{{ $topic->title }}</p>
                              </a></h5>
                            @endforeach
                          @endif
                        @endif
                        <div style="text-align: right;">
                          @php
                            if(request()->class_code)
                            {
                              $showTopic = 'admin/online-class-topic?class_code='.request()->class_code.'&course_code='.$module->course->code.'&module_id='.$module->id;
                            }
                            else
                            {
                              $showTopic = 'admin/online-class-topic?course_code='.$module->course->code.'&module_id='.$module->id;
                            }
                          @endphp
                          <a href="{{ asset($showTopic) }}" style="right: 0; padding: 0px;">+ Manage Topics</a>
                        </div>
                      </div>
                    </div>

                  </div>
                  <!-- Accordion card -->
                @endforeach
                @if(!request()->class_code)
                  @if(backpack_user()->hasRole('School Head') || backpack_auth()->user()->employee_id == $course->teacher_id)
                    <h5 class="mb-0 btn btn-lg btn-block new-course" style="margin: 0px; margin-bottom: 5px; ">
                      <a href="{{url($crud->route . '/create?course_code=' . request()->course_code)}}" style="padding: 0 50px;" >+ Add Modules</a> 
                    </h5>
                  @endif
                @endif
              </div>
              <!-- Accordion wrapper -->
              @else
                <div class="box" style="border-radius: 5px;">
                  <div class="box-body" style="padding: 10px; padding-bottom: 0; margin-bottom: 0;">
                    @if(!request()->class_code)
                      @if(backpack_user()->hasRole('School Head') || backpack_auth()->user()->employee_id == $course->teacher_id)
                        <h5 class="mb-0 btn btn-lg btn-block new-course" style="margin: 0px; margin-bottom: 5px; ">
                          <a href="{{url($crud->route . '/create?course_code=' . request()->course_code)}}" style="padding: 0 50px;" >+ Add Modules</a> 
                        </h5>
                      @endif
                    @endif
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
      <!-- END SECTION CLASS POSTS -->
    </div>

    <div class="col-md-4 col-lg-4 col-two">
      <!-- START RIGHT SIDE BAR -->
      <div class="box shadow" style="border-radius: 5px;">
        <div class="box-header with-border br-t-15 m-b-10" style="padding: 10px;">
            <h4 class="" style="padding: 0px !important; margin: 0px !important">
              My Courses
            </h4>
        </div>
        @if(count($my_courses)>0)
          @foreach($my_courses as $my_course)
            <div class="box-body with-border" style="padding: 0px 10px 10px 10px;">

                <div class="col-md-1 col-xs-1">
                  <span class="circle-span" style="background-color:{{ $my_course->color }};"></span>
                </div>
                <div class="col-md-10 col-xs-10">
                  <h5 class="" style="padding: 0px !important; margin: 0px !important">
                    <a href="{{ asset('admin/online-course?course_code='.$my_course->code) }}">
                      {{ $my_course->name }}
                    </a>
                  </h5>
                </div>
                
                {{-- <h4 class="" style="padding: 0px !important; margin: 0px !important">
                  <span class="circle-span" style="background-color:{{ $my_course->color }};"></span> <a href="{{ asset('admin/online-course?course_code='.$my_course->code) }}">{{ $my_course->name }}</a>
                </h4> --}}

            </div>
          @endforeach
        @endif
        <div class="box-footer with-border br-b-15" style="padding: 10px;">
          <a href="{{ asset('admin/online-course') }}">View all courses</a>
        </div>
      </div>
      <!-- END RIGHT SIDE BAR -->
    </div>
    
  </div>

  <script type="text/javascript">
    document.getElementById("nav-courses").classList.add("active");
  </script>

@endsection