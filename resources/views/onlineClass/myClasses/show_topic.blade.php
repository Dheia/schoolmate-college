@extends('backpack::layout')

@section('after_styles')
  <link rel="stylesheet" type="text/css" href="{{ asset('css/onlineclass/class.css') }}">
@endsection

@section('content')

  @php
    $class_code = Route::current()->parameter('class_code');
    $module_id  = Route::current()->parameter('module_id');
    $topic_id   = Route::current()->parameter('topic_id');
  @endphp

  {{-- START ONLINE CLASS NAVIGATION --}}
  <div class="row p-l-20 p-r-20">
    @include('onlineClass.partials.navbar')
  </div>
  {{-- END ONLINE CLASS NAVIGATION --}}

  <div class="row p-l-20 p-r-20">


    <!--START CLASS INFORMATION -->
    <div class="col-md-12 col-lg-12 no-padding">
      <div class="m-t-0">
          <div class="col-md-12 no-padding">
            <div class="box shadow br-15">
              <div class="box-body with-border" style="padding: 20px !important;">
                <span class="dot" style=" position: absolute; height: 70%; width: 10px; background-color:{{ $class->color }};"></span>
                <h2 style="padding: 0px 15px 0px 15px; margin: 0px !important">
                   {{ $class->name }}
                </h2>
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
    <div class="col-md-12 no-padding">
      <h3 class="no-padding no-margin">Module: <b>{{$selected_topic->module->title ?? 'Unknown'}}</b></h3>
    </div>
  </div>

  <div class="row p-l-20 p-r-20">
    <div class="col-md-8 col-lg-8 col-one">
      <!-- START SECTION CLASS POSTS -->
      <div class="">
          <div class="box shadow br-15">
            <div class="box-body with-border" style="padding: 20px !important;">
              <div class="row">
                <div style="padding: 10px 20px;">
                  {{-- Topic Title --}}
                  <h4 style="padding-bottom: 10px;">Topic: <strong>{!!$selected_topic->title!!}</strong></h4>
                  {{-- Start Of Next and Prev Button --}}
                  <div class="row">
                    <div class="col-md-12">
                      @if($prev_topic)
                      <h3 class="pull-left">
                        <a href="{{ url('admin/teacher-online-class/' . $class_code . '/course/' . $prev_topic->online_class_module_id . '/' .$prev_topic->id) }}">
                          <i class="glyphicon glyphicon-chevron-left"></i> Prev
                        </a>
                       
                      </h3>
                      @endif
                      @if($next_topic)
                        <h3 class="pull-right">
                          <a href="{{ url('admin/teacher-online-class/' . $class_code . '/course/' . $next_topic->online_class_module_id . '/' .$next_topic->id) }}">
                            Next <i class="glyphicon glyphicon-chevron-right"></i>
                          </a>
                        </h3>
                      @endif
                    </div>
                  </div>
                  {{-- End Of Next and Prev Button --}}
                  @if($selected_topic_pages->currentPage() == 1)
                  <div class="ckeditor" style="padding: 20px 30px;">
                    {{-- Topic Description --}}
                    {!!$selected_topic->description!!}
                  </div>
                  @endif
                </div>
              </div>
               
            </div>
           
          </div>
      </div>
      
      <div id="pages-container">
        <!-- END SECTION CLASS POSTS -->
        @if(count($selected_topic_pages)>0)
        
          @foreach($selected_topic_pages as $page_number => $topic_page)
            <div class="col-xs-12 col-md-12 col-lg-12" style="padding-left: 0px; padding-right: 0px;">
              <div class="box shadow br-15">
                 <div class="box-body with-border" style="padding: 20px !important;">
                <div class="text-center">
                     {!! $selected_topic_pages->links()!!}
                  </div>
                </div>
                <div class="box-body with-border" style="padding: 20px !important;">
                  <h4 style="padding-bottom: 10px;">Page {{ request()->page ?? '1' }}: <strong>{{ $topic_page->title }}</strong></h4>
                  <div class="row ckeditor" style="padding: 20px 30px;">
                      {!! $topic_page->description !!}
                  </div>
                  <div class="text-center" style="padding: 20px 30px;">
                    @if($topic_page->type == "discussion")
                      @if($topic_page->files)
                        @if(count($topic_page->files)>0)

                          @foreach($topic_page->files as $file)
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

                    @elseif($topic_page->type == 'video')

                      @if($topic_page->video)
                        <div style="padding: 20px 30px">
                          @php 
                            $topic_page->video = json_decode($topic_page->video);
                          @endphp
                          @if($topic_page->video->provider == "youtube")
                            <div class="embed-responsive embed-responsive-16by9">
                                <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/{{$topic_page->video->id}}?rel=0" allowfullscreen></iframe>
                            </div>
                          @elseif($topic_page->video->provider == "vimeo")
                            <div class="embed-responsive embed-responsive-16by9">
                              <a href="facebook.com" target="_blank">
                                <iframe class="embed-responsive-item" src="https://player.vimeo.com/video/{{$topic_page->video->id}}?title=0&amp;byline=0&amp;portrait=0&amp;badge=0&amp;color=ffffff" allowfullscreen></iframe>
                              </a>
                            </div>
                          @endif
                        </div>
                      @endif

                      @if($topic_page->files)
                        @if(count($topic_page->files)>0)
                          @foreach($topic_page->files as $file)
                            <div class="embed-responsive embed-responsive-16by9">
                               <video width="320" height="240" controls>
                                  <source src="{{asset($file['filepath'])}}" type="video/mp4">
                              </video>
                            </div>
                            <br>
                          @endforeach
                        @endif
                      @endif
                      
                    @endif
                  </div>
                  {{-- @if(strlen($topic_page->description)>500 || $topic_page->files)
                   <div style="padding: 20px 30px;">
                    <a href="{{ url('admin/teacher-online-class/' . $class_code . '/course/' . $selected_topic->online_class_module_id . '/' .$selected_topic->id . '/' . $topic_page->id) }}" class="btn btn-success pull-right m-b-10"><i class="fa fa-plus"></i> View more</a>
                  </div>
                  @endif --}}
                </div>
              </div>
            </div>
          @endforeach
        @endif

        <div class="text-center">
           {!! $selected_topic_pages->links()!!}
        </div>

      </div>
    </div>


    

    <div class="col-md-4 col-lg-4 col-two">
      <!-- START RIGHT SIDE BAR -->
      <div class="box shadow" >
        <div class="box-footer with-border m-b-10" style="padding: 10px;">
          <h5 class="" style="padding: 0px !important; margin: 0px !important">
            <b> My Classes </b>
          </h5>
        </div>
        @if(count($my_classes)>0)
          @foreach($my_classes as $my_class)
            @if($my_class->online_course_id)
              <div class="box-body with-border" style="padding: 0px 10px 10px 10px;">

                <div class="col-md-1 col-xs-1">
                  <span class="circle-span" style="background-color:{{ $my_class->color }};"></span>
                </div>
                <div class="col-md-10 col-xs-10">
                  <h5 style="padding: 0px !important; margin: 0px !important">
                    <a href="{{ url('admin/teacher-online-class/'.$my_class->code.'/course') }}">
                        {{ $my_class->name }}
                      </a>
                  </h5>
                </div>

              </div>
            @endif
          @endforeach
        @endif
        <div class="box-footer with-border m-b-10" style="padding: 10px; @if(Request::get('class_code')) border-radius: 5px; @endif">
            <h5 class="" style="padding: 0px !important; margin: 0px !important">
              <b> Topics </b>
            </h5>
        </div>
        @if(count($topics)>0)
          @foreach($topics as $module_topic)
            <div class="box-body with-border" style="padding: 0px 10px 10px 10px;">

              <div class="col-md-1 col-xs-1">
                <span class="circle-span" style="background-color:{{ $module_topic->course->color }};"></span>
              </div>
              <div class="col-md-10 col-xs-10">
                <h5 class="" style="padding: 0px !important; margin: 0px !important">
                  <a href="{{ url('admin/teacher-online-class/' . $class_code . '/course/' . $module_id . '/' . $module_topic->id) }}">
                    {{ $module_topic->title }}
                  </a>
                </h5>
              </div>

            </div>
          @endforeach
        @endif
      </div>
    </div>
      <!-- END RIGHT SIDE BAR -->
    
  </div>
@endsection

@section('after_scripts')
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.7/MathJax.js?config=TeX-MML-AM_CHTML"></script>
  <script type="text/javascript">
      document.getElementById("nav-classes").classList.add("active");

      $(document).ready(function()
      {
          $(document).on('click', '.pagination a',function(event)
          {
              event.preventDefault();
    
              $('li').removeClass('active');
              $(this).parent('li').addClass('active');

              var myurl = $(this).attr('href');
              var page=$(this).attr('href').split('page=')[1];
              var url = '?page='+page;
              $.ajax(
              {
                  url: url,
                  type: "get",
                  datatype: "html",
              }).done(function(data){
                $('#pages-container').empty();
                $('#pages-container').append(data.html);

                // Call setMathjax() function.
                setMathjax();
                // Set Course Navbar Active
                document.getElementById("nav-classes").classList.add("active");
              }).fail(function(jqXHR, ajaxOptions, thrownError){
                alert('No response from server');
              });
          });

          // Display Mathjax / Math Equation
          function setMathjax() {
            MathJax.Hub.Queue(["Typeset",MathJax.Hub]);
          }
    
      });
  </script>
@endsection

