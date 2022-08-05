@extends('backpack::layout')

@section('header')
  <!-- <section class="content-header">
    <h1>
      <span class="text-capitalize">
        {{ $course->name ?? 'Unknown' }}
        {{-- {!! $crud->getHeading() ?? $crud->entity_name_plural !!} --}}
      </span>
      <small id="datatable_info_stack">{!! $crud->getSubheading() ?? trans('backpack::crud.all').'<span>'.$crud->entity_name_plural.'</span> '.trans('backpack::crud.in_the_database') !!}.</small>
      <br>
      <small>{{ $teacher->fullname ?? 'Unknown' }} | {{ $course->subject->subject_title ?? 'Unknown' }} | {{ $course->section->name_level ?? 'Unknown' }}</small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="{{ url(config('backpack.base.route_prefix'), 'dashboard') }}">{{ trans('backpack::crud.admin') }}</a></li>
      <li><a href="{{ url($crud->route) }}?teacher_id={{ request()->get('teacher_id') }}&course_code={{ request()->get('course_code') }}" class="text-capitalize">{{ $crud->entity_name_plural }}</a></li>
      <li class="active">{{ trans('backpack::crud.list') }}</li>
    </ol>
  </section> -->
@endsection

@section('content')

  <link rel="stylesheet" type="text/css" href="{{ asset('css/onlineclass/class.css') }}">

  @if(request()->course_code)
   @php
    if(request()->class_code)
    {
      $showTopics = url($crud->route).'?class_code='.request()->class_code.'&course_code='.$course->code.'&module_id='.$module->id;
    }
    else
    {
      $showTopics = url($crud->route).'?course_code='.$course->code.'&module_id='.$module->id;
    }
  @endphp
  <nav aria-label="breadcrumb" class="p-l-20 p-r-20">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ asset('admin/online-course?course_code='.$course->code) }}">{{$course->name}}</a></li>
      <li class="breadcrumb-item"><a href="{{ asset($showTopics) }}">{{$module->title ?? 'Unknown'}}</a></li>
      <li class="breadcrumb-item active" aria-current="page">{{$selected_topic->title}}</li>
    </ol>
  </nav>
  <div class="row p-l-20 p-r-20">
    <!--START CLASS INFORMATION -->
    <div class="col-md-12 col-lg-12">
      <div class="m-t-0">
          <div class="col-md-12" style="padding-left: 0px; padding-right: 0px;">
            <div class="box">
              <div class="box-body with-border" style="padding: 20px !important;">
                <span class="dot" style=" position: absolute; height: 70%; width: 10px; background-color:{{ $course->color }};"></span>
                <h1 style="padding: 0px 15px 0px 15px; margin: 0px !important">
                   {{ $course->name }}
                </h1>
                <h4 style="padding: 0px 15px 0px 15px; margin-top: 0px;  margin-bottom: 0px;">
                  {{ $course->teacher->prefix }}. {{ $course->teacher->firstname }} {{ $course->teacher->lastname }} | {{ $course->subject->subject_title ?? 'Unknown' }}
                </h4>
              </div>
            </div>
          </div>
      </div>
    </div>
    <!-- END CLASS INFORMATION -->
  </div>
  @endif
  <div class="row p-l-20 p-r-20">
    <div class="col-12">
      <h3 style="padding: 0px 15px 15px 15px; margin-top: 0px;  margin-bottom: 0px;">Topic: {{$selected_topic->title ?? 'Unknown'}}</h3>
    </div>
  </div>
  <div class="row p-l-20 p-r-20">
    <div class="col-md-8 col-lg-8">
      <!-- START SECTION CLASS POSTS -->
      <div class="">
        <div class="col-xs-12 col-md-12 col-lg-12" style="padding-left: 0px; padding-right: 0px;">
	        <div class="box">
	            <div class="box-body with-border" style="padding: 20px !important;">
		            <div class="row">
		                <div style="padding: 10px 20px;">
			                @if(!request()->class_code)
			                    @if(backpack_user()->hasRole('School Head') || backpack_auth()->user()->employee_id == $course->teacher_id)
			                      <div class="dropdown" style="">
			                        <a href="#" class="dropdown-toggle pull-right" data-toggle="dropdown"><i class="fa fa-ellipsis-h fa-2x"></i></a>
			                        <ul class="dropdown-menu pull-right" style="margin-top: 15px; margin-right: 20px;">

			                          <li>
			                            <a href="{{ url('admin/online-class-topic-page') }}/{{ $selected_page->id }}/edit?course_code={{ $selected_topic->module->course->code }}&module_id={{ $selected_topic->module->id }}&topic_id={{ $selected_topic->id }}&topic_page={{ $selected_page->id }}">Edit {{ ucwords($selected_page->type) }}</a>
			                          </li>
			                          <li>
			                            <a id="deletePage" href="javascript:void(0)">Delete {{ ucwords($selected_page->type) }}</a>
			                          </li>
			                        </ul>
			                      </div>
			                    @endif
			                @endif
		                  	{{-- Topic Title --}}
		                    <h4 style="padding-bottom: 10px;">Page: <strong>{!!$selected_page->title!!}</strong></h4>
		                    {{-- Start Of Next and Prev Button --}}
		                    <div class="row">
			                    <div class="col-md-12">
			                      	@if($prev_topic_page)
				                      	<h3 class="pull-left">
					                        <a href="{{ url($crud->route .'?course_code=' . $selected_topic->module->course->code . '&module_id=' . $selected_topic->module->id . '&topic_id=' . $selected_topic->id . '&topic_page=' . $prev_topic_page->id) }}">
					                          <i class="glyphicon glyphicon-chevron-left"></i> Prev
					                        </a>
				                       
				                      	</h3>
				                      @endif
			                      @if($next_topic_page)
			                        <h3 class="pull-right">
			                          <a href="{{ url($crud->route .'?course_code=' . $selected_topic->module->course->code . '&module_id=' . $selected_topic->module->id . '&topic_id=' . $selected_topic->id . '&topic_page=' . $next_topic_page->id) }}">
			                            Next <i class="glyphicon glyphicon-chevron-right"></i>
			                          </a>
			                        </h3>
			                      @endif
			                    </div>
			                </div>
		                  	{{-- End Of Next and Prev Button --}}
			                <div class="row ckeditor" style="padding: 20px 30px;">
			                    {{-- Topic Description --}}
			                    {!! $selected_page->description !!}
			                </div>

                      <div class="row ckeditor" style="padding: 20px 30px;">
                        @if($selected_page->type == 'discussion')
                          @if($selected_page->files)
                            @if(count($selected_page->files)>0)

                              @foreach($selected_page->files as $file)
                                @if($file['filepath'])
                                  @php
                                    $file_extension = pathinfo($file['filepath'], PATHINFO_EXTENSION);
                                  @endphp
                                  @if ( ! empty($imageExtensions[$file_extension]) )
                                      <img style="max-width: 100%; max-height: 700px;  padding-bottom: 50px;" src="{{ asset($file['filepath']) }}">
                                  @else
                                    <a class="pull-left" target="_blank" href="{{ asset($file['filepath']) }}" download="{{ asset($file['filepath']) }}">
                                      @if($file_extension == 'pdf')
                                        <i class="fa fa-file-pdf-o"></i>
                                      @elseif($file_extension == 'docx' || $file_extension == 'doc' || $file_extension == 'docm')
                                        <i class="fa fa-file-word-o"></i>
                                      @elseif($file_extension == 'xlsx' || $file_extension == 'xltx' || $file_extension == 'xlsm')
                                        <i class="fa fa-file-excel-o"></i>
                                      @elseif($file_extension == 'pptx' || $file_extension == 'ppt' || $file_extension == 'pptm' || $file_extension == 'potx' || $file_extension == 'ppsx')
                                        <i class="fa fa-file-powerpoint-o"></i>
                                      @else
                                        <i class="fa fa-file"></i>
                                      @endif
                                      {{$file['filename']}}
                                    </a>
                                    <br>
                                  @endif
                                @endif
                              @endforeach
                            @endif
                          @endif

                        @elseif($selected_page->type == 'video')
                          @if($selected_page->video)
                            <div style="padding: 20px 30px;">

                              @php 
                                $selected_page->video = json_decode($selected_page->video);
                              @endphp

                              @if($selected_page->video->provider == "youtube")
                                <div class="embed-responsive embed-responsive-16by9">
                                    <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/{{$selected_page->video->id}}?rel=0" allowfullscreen></iframe>
                                </div>
                              @elseif($selected_page->video->provider == "vimeo")
                                <div class="embed-responsive embed-responsive-16by9">
                                  <a href="facebook.com" target="_blank">
                                    <iframe class="embed-responsive-item" src="https://player.vimeo.com/video/{{$selected_page->video->id}}?title=0&amp;byline=0&amp;portrait=0&amp;badge=0&amp;color=ffffff" allowfullscreen></iframe>
                                  </a>
                                </div>
                              @endif
                            </div>
                          @endif

                          @if($selected_page->files)
                            @if(count($selected_page->files)>0)
                              <div style="padding: 20px 30px;">
                                @foreach($selected_page->files as $file)
                                  <div class="embed-responsive embed-responsive-16by9">
                                     <video width="320" height="240" controls>
                                        <source src="{{asset($file['filepath'])}}" type="video/mp4">
                                    </video>
                                  </div>
                                  <br>
                                @endforeach
                              </div>
                            @endif
                          @endif
                        @endif
			                </div>
		                </div>
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
            <div class="box shadow">
              @if(!Request::get('class_code'))
                <div class="box-body with-border" style="padding: 10px; border-radius: 5px;">
                    <h4 class="" style="padding: 0px !important; margin: 0px !important">
                      My Courses
                    </h4>
                </div>
                @if(count($my_courses)>0)
                  @foreach($my_courses as $my_course)
                    <div class="box-body with-border" style="padding: 10px;">
                      <div class="">
                        <h4 class="" style="padding: 0px !important; margin: 0px !important">
                          <span class="circle-span" style="background-color:{{ $my_course->color }};"></span> <a href="{{ asset('admin/online-course?course_code='.$my_course->code) }}">{{ $my_course->name }}</a>
                        </h4>
                      </div>
                    </div>
                  @endforeach
                @endif
              @endif
              <div class="box-footer with-border" style="padding: 10px; @if(Request::get('class_code')) border-radius: 5px; @endif">
                  <h4 class="" style="padding: 0px !important; margin: 0px !important">
                    Topics
                  </h4>
              </div>
              @if(count($topics)>0)
                @foreach($topics as $module_topic)
                  @php
                    if(request()->class_code)
                    {
                      $showTopic = url($crud->route).'?class_code='.request()->class_code.'&course_code='.$module_topic->module->course->code.'&module_id='.$module_topic->module->id.'&topic_id='.$module_topic->id;
                    }
                    else
                    {
                      $showTopic = url($crud->route).'?course_code='.$module_topic->module->course->code.'&module_id='.$module_topic->module->id.'&topic_id='.$module_topic->id;
                    }
                  @endphp
                  <div class="box-body with-border" style="padding: 10px;">
                    <div class="">
                      <h4 class="" style="padding: 0px !important; margin: 0px !important">
                        <span class="circle-span" style="background-color:{{ $module_topic->course->color }};"></span> <a href="{{ asset($showTopic) }}">{{ $module_topic->title }}</a>
                      </h4>
                    </div>
                  </div>
                @endforeach
              @endif
              <div class="box-footer with-border" style="padding: 10px; @if(Request::get('class_code')) border-radius: 5px; @endif">
                  <h4 class="" style="padding: 0px !important; margin: 0px !important">
                    Pages
                  </h4>
              </div>
              @if(count($selected_topic->pages)>0)
                @foreach($selected_topic->pages as $selected_topic_page)
                  <div class="box-body with-border" style="padding: 10px;">
                    <div class="">
                      <h4 class="" style="padding: 0px !important; margin: 0px !important">
                        <span class="circle-span" style="background-color:{{ $module_topic->course->color }};"></span> <a href="{{ url($crud->route . '?course_code=' . $selected_topic->module->course->code . '&module_id=' . $selected_topic->module->id . '&topic_id=' . $selected_topic->id . '&topic_page=' . $selected_topic_page->id) }}">{{ $selected_topic_page->title }}</a>
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

@section('after_styles')
  <link rel="stylesheet" href="{{ asset('css/onlineclass/class.css') }}">
  
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
  

  <!-- CRUD LIST CONTENT - crud_list_styles stack -->
  @stack('crud_list_styles')
@endsection

@section('after_scripts')
  @include('crud::inc.datatables_logic')

  <script src="{{ asset('vendor/backpack/crud/js/crud.js') }}"></script>
  <script src="{{ asset('vendor/backpack/crud/js/form.js') }}"></script>
  <script src="{{ asset('vendor/backpack/crud/js/list.js') }}"></script>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>

  <script>
    $('#deletePage').click(function (e) {
      $.confirm({
        title: 'Delete',
        content: 'Are you sure you want to delete?',
        buttons: {
            cancel: function () {
                // $.alert('Canceled!');
            },
            delete: {
              text: 'Delete', // text for button
              btnClass: 'btn-danger', // class for the button
              isHidden: false, // initially not hidden
              isDisabled: false, // initially not disabled
              action: function(event){
                window.location.href = "{{ url('admin/online-class-topic-page') }}/{{ $selected_page->id }}/delete?course_code={{ $selected_topic->module->course->code }}&module_id={{ $selected_topic->module->id }}&topic_id={{ $selected_topic->id }}&page={{ $selected_page->id }}";
              }
            }
           
        }
      });
    });
    
  </script>

  <!-- CRUD LIST CONTENT - crud_list_scripts stack -->
  @stack('crud_list_scripts')
@endsection
