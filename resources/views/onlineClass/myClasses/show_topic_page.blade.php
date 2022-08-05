@extends('backpack::layout')

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

  @php
    $class_code = Route::current()->parameter('class_code');
    $module_id  = Route::current()->parameter('module_id');
    $topic_id   = Route::current()->parameter('topic_id');
  @endphp
{{--  --}}
  <div class="row p-l-20 p-r-20">
    <!--START CLASS INFORMATION -->
    <div class="col-md-12 col-lg-12">
      <div class="m-t-0">
          <div class="col-md-12" style="padding-left: 0px; padding-right: 0px;">
            <div class="box" style="border-radius: 5px;">
              <div class="box-header with-border" style="padding: 20px !important;">
                <span class="dot" style=" position: absolute; height: 70%; width: 10px; background-color:{{ $class->color }};"></span>
                <h1 style="padding: 0px 15px 0px 15px; margin: 0px !important">
                   {{ $class->name }}
                </h1>
                <h4 style="padding: 0px 15px 0px 15px; margin-top: 0px;  margin-bottom: 0px;">
                  {{ $class->teacher->prefix }}. {{ $class->teacher->firstname }} {{ $class->teacher->lastname }} | {{ $class->subject->subject_title ?? 'Unknown' }}
                </h4>
              </div>
            </div>
          </div>
      </div>
    </div>
    <!-- END CLASS INFORMATION -->
  </div>

  <div class="row p-l-20 p-r-20">
    <div class="col-12">
      <h3 style="padding: 0px 15px 15px 15px; margin-top: 0px;  margin-bottom: 0px;">Topic: {{$selected_page->topic->title ?? 'Unknown'}}</h3>
    </div>
  </div>
  <div class="row p-l-20 p-r-20">
    <div class="col-md-8 col-lg-8" style="border-radius: 5px;">
      @if(count($pages)>0)
        @foreach($pages as $page_number => $page)
          @if($page->id == $selected_page->id)
            <div class="col-xs-12 col-md-12 col-lg-12" style="padding-left: 0px; padding-right: 0px;">
              <div class="box" style="border-radius: 5px;">
                <div class="box-header with-border" style="padding: 20px !important;">
                  <h4 style="padding-bottom: 10px;">Page {{ $page_number+1 }}: <strong>{{ $selected_page->title }}</strong></h4>
                  <div class="row">
                    <div class="col-md-12">
                      @if($prev_topic_page)
                      <h3 class="pull-left">
                        <a href="{{ url('admin/teacher-online-class/' . $class_code . '/course/' . $selected_page->topic->module->id . '/' .$selected_page->topic->id . '/' .$prev_topic_page->id) }}">
                          <i class="glyphicon glyphicon-chevron-left"></i> Prev
                        </a>
                       
                      </h3>
                      @endif
                      @if($next_topic_page)
                        <h3 class="pull-right">
                          <a href="{{ url('admin/teacher-online-class/' . $class_code . '/course/' . $selected_page->topic->module->id . '/' .$selected_page->topic->id . '/' .$next_topic_page->id) }}">
                            Next <i class="glyphicon glyphicon-chevron-right"></i>
                          </a>
                        </h3>
                      @endif
                    </div>
                  </div>
                  <div style="padding: 20px 30px;">
                      {!! $selected_page->description !!}
                  </div>
                  <div class="text-center" style="padding: 20px 30px;">
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
                  </div>
                </div>
              </div>
            </div>
          @endif
        @endforeach
      @endif
    </div>
    

    <div class="col-md-4" style="padding-left: 0px; padding-right: 0px; margin-left: 0px; margin-right: 0px;">
      <!-- START RIGHT SIDE BAR -->
          <div class="col-xs-12 col-md-12 col-lg-12">
            <div class="box" style="border-radius: 5px;">
              <div class="box-footer with-border" style="padding: 10px; border-radius: 5px;">
                <h4 class="" style="padding: 0px !important; margin: 0px !important">
                  My Classes
                </h4>
              </div>
              @if(count($my_classes)>0)
                @foreach($my_classes as $my_class)
                  @if($my_class->online_course_id)
                    <div class="box-body with-border" style="padding: 10px;">
                      <div class="container">
                        <h4 class="" style="padding: 0px !important; margin: 0px !important">
                          <span class="dot" style="background-color:{{ $my_class->color }};"></span> 
                            <a href="{{ url('admin/teacher-online-class/'.$my_class->code.'/course') }}">
                              {{ $my_class->name }}
                            </a>
                        </h4>
                      </div>
                    </div>
                  @endif
                @endforeach
              @endif
              <div class="box-footer with-border" style="padding: 10px; @if(Request::get('class_code')) border-radius: 5px; @endif">
                  <h4 class="" style="padding: 0px !important; margin: 0px !important">
                    Pages
                  </h4>
              </div>
              @if(count($pages)>0)
                @foreach($pages as $page)
                  <div class="box-body with-border" style="padding: 10px;">
                    <div class="container">
                      <h4 class="" style="padding: 0px !important; margin: 0px !important">
                        <span class="dot" style="background-color:{{ $selected_page->topic->module->course->color }};"></span> <a href="{{ url('admin/teacher-online-class/' . $class_code . '/course/' . $module_id . '/' . $topic_id . '/' . $page->id) }}">{{ $page->title }}</a>
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
