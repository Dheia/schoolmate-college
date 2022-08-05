@extends('backpack::layout')

@section('header')

@endsection

@section('content')

  <div class="row p-l-20 p-r-20">
    @include('onlineClass.partials.navbar')
  </div>

    <div class="row p-l-20 p-r-20">

      <!--START CLASS INFORMATION -->
      <div class="col-md-12 col-lg-12 no-padding">
        <div class="box shadow">
          <div class="box-body with-border" style="padding: 20px !important;">
            <span class="dot" style=" position: absolute; height: 70%; width: 10px; background-color:{{ $class->course->color }};"></span>
            <h2 style="padding: 0px 15px 0px 15px; margin: 0px !important">
               {{ $class->name }}
            </h2>
            <h4 style="padding: 0px 15px 0px 15px; margin-top: 0px;  margin-bottom: 0px;">
              {{ $class->teacher->prefix }}. {{ $class->teacher->firstname }} {{ $class->teacher->lastname }} | {{ $class->subject->subject_title ?? 'Unknown' }}
            </h4>
          </div>
        </div>
      </div>
      <!-- END CLASS INFORMATION -->
    </div>
  
  <div class="row p-l-20 p-r-20">
    <div class="col-md-8 col-lg-8 col-one">
      
      {{-- START MODULE DESCRIPTION --}}
      <div class="box shadow">
        <div class="box-body with-border" style="padding: 20px !important;">
          <div class="row">
            <div style="padding: 10px 20px;">
              {{-- Topic Title --}}
              <h4 style="padding-bottom: 10px;">Module: <strong>{!!$module->title!!}</strong></h4>
              <div class="p-l-30 p-r-30 ckeditor">
                {{-- Topic Description --}}
                {!!$module->description!!}
              </div>
              @if($module->content_standard)
                <div class="ckeditor" style="padding: 10px 30px;">
                  <h4 class="text_uppercase">Content Standard</h4>
                  {!!$module->content_standard!!}
                </div>
              @endif
              @if($module->performance_standard)
                <div class="ckeditor" style="padding: 10px 30px;">
                  <h4 class="text_uppercase">Performance Standard</h4>
                  {!!$module->performance_standard!!}
                </div>
              @endif
              @if($module->learning_competency)
                <div class="ckeditor" style="padding: 10px 30px;">
                  <h4 class="text_uppercase">Learning Competencies</h4>
                  {!!$module->learning_competency!!}
                </div>
              @endif
              @if($module->learning_objective)
                <div class="ckeditor" style="padding: 10px 30px;">
                  <h4 class="text_uppercase">Learning Objective</h4>
                  {!!$module->learning_objective!!}
                </div>
              @endif
            </div>
          </div>
          {{-- Start Of Add Page Button --}}
          {{-- href="{{ url('admin/online-class-topic-page/create?course_code=' . request()->course_code . '&module_id=' . request()->module_id . '&topic_id=' . request()->topic_id) }}" --}}
          {{-- <a href="#" class="btn btn-primary pull-right m-b-10" data-toggle="modal" data-target="#addPageModal"><i class="fa fa-plus"></i> Add Page</a> --}}
          {{-- End of Add Page Button --}}
           
        </div>
       
      </div>
      {{-- END MODULE --}}
      <!-- START SECTION CLASS POSTS -->
      <div>
        <div class="box shadow">
          <div class="box-body with-border" style="padding: 20px !important;">
            <h4>Topics</h4>
            <div class="row">
              <div style="padding: 10px 20px;">

                <div style="padding: 20px 30px;">
                  @if($topics)
                    @if(count($topics)>0)
                      @foreach($topics as $key => $topic)
                          <a href="{{ url($crud->route . '/' . $class->code . '/course/' . $module->id . '/' . $topic->id) }}">
                            <h5 class="mb-0 btn-lg btn-block" style="margin: 0px; color: #000; background-color: #3c8dbc; color:white; border: 1px solid #ccc; margin-bottom: 5px; @if($loop->last) margin-bottom: 20px; @endif">
                              <span class="pull-left" style="padding: 0 20px 0 0;"> {{ $key+1 }} </span>
                              <p class="" style="margin: 0; text-align: left"> {{ $topic->title }} </p> 
                            </h5>
                          </a>
                      @endforeach
                      @if(!request()->class_code)
                        @if(backpack_user()->hasRole('School Head') || backpack_auth()->user()->employee_id == $course->teacher_id)
                          <a href="{{ url($crud->route) }}/create?course_code={{$course->code}}&module_id={{$module->id}}" style="padding: 0 50px;">+ Add Topic</a>
                        @endif
                      @endif
                    @else
                      <div class="box" style="border-radius: 5px;">
                        <div class="box-body" style="padding: 10px; padding-bottom: 0; margin-bottom: 0;">
                          @if(!request()->class_code)
                            @if(backpack_user()->hasRole('School Head') || backpack_auth()->user()->employee_id == $course->teacher_id)
                              <a href="{{ url($crud->route) }}/create?course_code={{$course->code}}&module_id={{$module->id}}" style="padding: 0 50px;">+ Add Topic</a>
                            @endif
                          @endif
                          <h4 class="text-center" style="padding: 0px !important; margin: 0px !important">
                            No available topic
                          </h4>
                        </div>
                      </div>
                    @endif
                  @else
                    <div class="box" style="border-radius: 5px;">
                      <div class="box-body" style="padding: 10px; padding-bottom: 0; margin-bottom: 0;">
                        @if(!request()->class_code)
                          @if(backpack_user()->hasRole('School Head') || backpack_auth()->user()->employee_id == $course->teacher_id)
                            <a href="{{ url($crud->route) }}/create?course_code={{$course->code}}&module_id={{$module->id}}" style="padding: 0 50px;" style="padding: 0 50px;">+ Add Topic</a>
                          @endif
                        @endif
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
      <!-- END SECTION CLASS POSTS -->
    </div>

    <div class="col-md-4 col-lg-4 col-two">
      <!-- START RIGHT SIDE BAR -->
      <div class="box shadow">
        <div class="box-footer br-15 m-b-10" style="padding: 10px;">
            <h5 style="padding: 0px !important; margin: 0px !important">
              <b> Modules </b>
            </h5>
        </div>
        @if(count($module->course->modules)>0)
          @foreach($module->course->modules as $my_module)
            <div class="box-body with-border" style="padding: 0px 10px 10px 10px;">

              <div class="col-md-1 col-xs-1">
                <span class="circle-span" style="background-color:{{ $module->course->color }};"></span>
              </div>
              <div class="col-md-10 col-xs-10">
                <h5 class="" style="padding: 0px !important; margin: 0px !important">
                  <a href="{{ url($crud->route . '/' . $class->code . '/course/' . $my_module->id) }}">
                    {{ $my_module->title }}
                  </a>
                </h5>
              </div>

            </div>
          @endforeach
        @endif
      </div>
      <!-- END RIGHT SIDE BAR -->
    </div>
    
  </div>
@endsection

@section('after_styles')
  <link rel="stylesheet" href="{{ asset('css/onlineclass/class.css') }}">
@endsection

@section('after_scripts')

  <script type="text/javascript">
    document.getElementById("nav-courses").classList.add("active");
  </script>
@endsection
