@extends('backpack::layout')

@section('content')

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
  
  <div class="row p-l-20 p-r-20">
    @include('onlineClass.partials.navbar')
  </div>

  <div class="row p-l-20 p-r-20">
    <!--START CLASS INFORMATION -->
    <div class="col-md-12 col-lg-12 no-padding">
      <div class="m-t-0">
          <div class="col-md-12" style="padding-left: 0px; padding-right: 0px;">
            <div class="box shadow">
              <div class="box-body with-border" style="padding: 20px !important;">
                <span class="dot" style=" position: absolute; height: 70%; width: 10px; background-color:{{ $course->color }};"></span>
                <h2 style="padding: 0px 15px 0px 15px; margin: 0px !important">
                   {{ $course->name }}
                </h2>
                <h4 style="padding: 0px 15px 0px 15px; margin-top: 0px;  margin-bottom: 0px;">Module: {{$module->title ?? 'Unknown'}}</h4>
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
    <div class="col-md-8 col-lg-8 col-one" style="border-radius: 0 0 5px 5px;">
      <!-- START SECTION CLASS POSTS -->
      <div class="">
        <div class="box shadow">
          <div class="box-body with-border" style="padding: 20px !important;">
            <div class="row">
              <div style="padding: 10px 20px;">
                @if(!request()->class_code)
                  @if(backpack_user()->hasRole('School Head') || backpack_auth()->user()->employee_id == $course->teacher_id)
                    <div class="dropdown" style="">
                      <a href="#" class="dropdown-toggle pull-right" data-toggle="dropdown"><i class="fa fa-ellipsis-h fa-2x"></i></a>
                      <ul class="dropdown-menu pull-right" style="top: 25px; right: -18px;">

                        <li>
                          <a href="{{ url($crud->route) }}/{{ $selected_topic->id }}/edit?course_code={{ $selected_topic->module->course->code }}&module_id={{ $selected_topic->module->id }}&topic_id={{ $selected_topic->id }}">Edit Topic</a>
                        </li>
                        <li>
                          <a id="deleteTopic" href="javascript:void(0)">Delete Topic</a>
                        </li>
                      </ul>
                    </div>
                  @endif
                @endif
                {{-- Topic Title --}}
                <h4 style="padding-bottom: 10px;">Topic: <strong>{!!$selected_topic->title!!}</strong></h4>
                {{-- Start Of Next and Prev Button --}}
                <div class="row">
                  <div class="col-md-12">
                    @if($prev_topic)
                      @php
                        if(request()->class_code)
                        {
                          $prev = url($crud->route).'?class_code='.request()->class_code.'&course_code='.request()->course_code.'&module_id='.request()->module_id.'&topic_id='.$prev_topic->id;
                        }
                        else
                        {
                           $prev = url($crud->route).'?course_code='.request()->course_code.'&module_id='.request()->module_id.'&topic_id='.$prev_topic->id;
                        }
                      @endphp
                    <h3 class="pull-left">
                      <a href="{{ asset($prev) }}">
                        <i class="glyphicon glyphicon-chevron-left"></i> Prev
                      </a>
                     
                    </h3>
                    @endif
                    @if($next_topic)
                      @php
                        if(request()->class_code)
                        {
                          $next = url($crud->route).'?class_code='.request()->class_code.'&course_code='.request()->course_code.'&module_id='.request()->module_id.'&topic_id='.$next_topic->id;
                        }
                        else
                        {
                           $next = url($crud->route).'?course_code='.request()->course_code.'&module_id='.request()->module_id.'&topic_id='.$next_topic->id;
                        }
                      @endphp
                      <h3 class="pull-right">
                        <a href="{{ asset($next) }}">
                          Next <i class="glyphicon glyphicon-chevron-right"></i>
                        </a>
                      </h3>
                    @endif
                  </div>
                </div>
                {{-- End Of Next and Prev Button --}}
                <div style="padding: 20px 30px;" id="topic_content">
                  {{-- Topic Description --}}
                  {!!$selected_topic->description!!}
                </div>
              </div>
            </div>
            {{-- Start Of Add Page Button --}}
            {{-- href="{{ url('admin/online-class-topic-page/create?course_code=' . request()->course_code . '&module_id=' . request()->module_id . '&topic_id=' . request()->topic_id) }}" --}}
            <a href="#" class="btn btn-primary pull-right m-b-10" data-toggle="modal" data-target="#addPageModal"><i class="fa fa-plus"></i> Add Page</a>
            {{-- End of Add Page Button --}}
             
          </div>
         
        </div>
      </div>
      <!-- END SECTION CLASS POSTS -->
      <div id="page_container">
      @if(count($selected_topic_pages) > 0)
        @foreach($selected_topic_pages as $page_number => $topic_page)
          @if($topic_page->type == 'discussion')
          <!-- START DISCUSSION PAGE -->
          <div class="box shadow br-15">
            <div class="text-center">
              {!! $selected_topic_pages->appends(['course_code' => $selected_topic->module->course->code, 'module_id' => $selected_topic->module->id, 'topic_id' => $selected_topic->id])->links()!!}
            </div>
            <div class="box-body with-border" style="padding: 20px !important;">
              <div class="row">
                <div style="padding: 10px 20px;">
                  @if(backpack_user()->hasRole('School Head') || backpack_auth()->user()->employee_id == $course->teacher_id)
                    <div class="dropdown" style="">
                      <a href="#" class="dropdown-toggle pull-right" data-toggle="dropdown"><i class="fa fa-ellipsis-h fa-2x"></i></a>
                      <ul class="dropdown-menu pull-right" style="top: 25px; right: -18px;">

                        <li>
                          <a href="{{ url('admin/online-class-topic-page/' . $topic_page->id . '/edit?course_code=' . $selected_topic->module->course->code . '&module_id=' . $selected_topic->module->id . '&topic_id=' . $selected_topic->id) }}">Edit Discussion</a>
                        </li>
                        <li>
                          <a id="deletePage" onclick="deletePage({{$topic_page->id}})" href="javascript:void(0)">Delete Discussion</a>
                        </li>
                      </ul>
                    </div>
                  @endif
                  <h4 style="padding: 20px 0 0 30px;">Page {{ $selected_topic_pages->currentPage() }}: <strong>{{ $topic_page->title }}</strong></h4>
                  <div style="padding: 20px 30px;" class="row ckeditor">
                    {!! $topic_page->description !!}
                  </div>
                  <div class="text-center" style="padding: 20px 30px;">
                    @if($topic_page->files)
                      @if(count($selected_topic->files)>0)

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
                  </div>
                  {{-- @if(strlen($topic_page->description)>500)
                   <div style="padding: 20px 30px;">
                    <a href="{{ url($crud->route) . '?course_code=' . request()->course_code . '&module_id=' . request()->module_id . '&topic_id=' . $selected_topic->id .'&topic_page=' . $topic_page->id }}" class="btn btn-success pull-right m-b-10"><i class="fa fa-plus"></i> View more</a>
                  </div>
                  @endif --}}
                </div>
              </div>
            </div>
          </div>
          <!-- END DISCUSSION PAGE -->
          @elseif($topic_page->type == 'video')
            <!-- START VIDEO PAGE -->
            <div class="col-xs-12 col-md-12 col-lg-12" style="padding-left: 0px; padding-right: 0px;">
              <div class="box shadow">
                <div class="box-body with-border" style="padding: 20px !important;">
                  <div class="text-center">
                    {!! $selected_topic_pages->appends(['course_code' => $selected_topic->module->course->code, 'module_id' => $selected_topic->module->id, 'topic_id' => $selected_topic->id])->links()!!}
                  </div>
                  <div style="padding: 10px 20px;">
                    @if(backpack_user()->hasRole('School Head') || backpack_auth()->user()->employee_id == $course->teacher_id)
                      <div class="dropdown" style="">
                        <a href="#" class="dropdown-toggle pull-right" data-toggle="dropdown"><i class="fa fa-ellipsis-h fa-2x"></i></a>
                        <ul class="dropdown-menu pull-right" style="top: 25px; right: -18px;">

                          <li>
                            <a href="{{ url('admin/online-class-topic-page/' . $topic_page->id . '/edit?course_code=' . $selected_topic->module->course->code . '&module_id=' . $selected_topic->module->id . '&topic_id=' . $selected_topic->id) }}">Edit Discussion</a>
                          </li>
                          <li>
                            <a id="deletePage" onclick="deletePage({{$topic_page->id}})" href="javascript:void(0)">Delete Discussion</a>
                          </li>
                        </ul>
                      </div>
                    @endif
                    <h4 style="padding: 20px 0 0 30px;">Page {{ $selected_topic_pages->currentPage() }}: <strong>{{ $topic_page->title }}</strong></h4>
                    <div style="padding: 20px 0 0 30px;" class="row ckeditor">
                       <div style="padding: 20px 30px;">
                      {!! $topic_page->description !!}
                    </div>
                    </div>
                    <div style="padding: 20px 30px;">
                                    
                      @if($topic_page->video)

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
                      @endif
                    </div>

                    <div style="padding: 20px 30px;">
                                <!-- Carousel row -->
                                    
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
                    </div>
                  </div>

                </div>
              </div>
            </div>
            <!-- END VIDEO PAGE -->
          @endif
        @endforeach
      @endif
      <div class="text-center">
         {!! $selected_topic_pages->appends(['course_code' => $selected_topic->module->course->code, 'module_id' => $selected_topic->module->id, 'topic_id' => $selected_topic->id])->links()!!}
      </div>
      </div>
    </div>

    
    {{-- RIGHT SIDE --}}
    <div class="col-lg-4 col-md-4 col-two">
      <!-- START RIGHT SIDE BAR -->
      <div class="box br-15">
        @if(!Request::get('class_code'))
          <div class="box-footer with-border br-15 m-b-10" style="padding: 10px;">
              <h5 class="" style="padding: 0px !important; margin: 0px !important">
                <b> My Courses </b>
              </h5>
          </div>
          @if(count($my_courses)>0)
            @foreach($my_courses as $my_course)
              <div class="box-body with-border" style="padding: 0px 10px 10px 10px;">
                <div class="">
                  <div class="col-md-1 col-xs-1">
                    <span class="circle-span" style="background-color:{{ $my_course->color }};"></span>
                  </div>
                  <div class="col-md-10 col-xs-10">
                    <h5 class="" style="padding: 0px !important; margin: 0px !important">
                     <a href="{{ asset('admin/online-course?course_code='.$my_course->code) }}">{{ $my_course->name }}</a>
                    </h5>
                  </div>
                </div>
              </div>
            @endforeach
          @endif
        @endif
        <div class="box-footer with-border m-b-10" style="padding: 10px; @if(Request::get('class_code')) border-radius: 5px; @endif">
            <h5 class="" style="padding: 0px !important; margin: 0px !important">
              <b> Topics </b>
            </h5>
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
            <div class="box-body with-border" style="padding: 0px 10px 10px 10px;">

              <div class="col-md-1 col-xs-1">
                <span class="circle-span" style="background-color:{{ $module_topic->course->color }};"></span>
              </div>
              <div class="col-md-10 col-xs-10">
                <h5 class="" style="padding: 0px !important; margin: 0px !important">
                  <a href="{{ asset($showTopic) }}">{{ $module_topic->title }}</a>
                </h5>
              </div>

            </div>
          @endforeach
        @endif
      </div>
      <!-- END RIGHT SIDE BAR -->
    </div>

    {{-- Page Type Modal --}}
    <div class="modal fade" id="addPageModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" style="border-radius: 5px;">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="box" style="border-radius: 5px;">
              <div class="box-body text-center" style="padding: 20px !important;">
               <a href="{{ url('admin/online-class-topic-page/create?course_code=' . request()->course_code . '&module_id=' . request()->module_id . '&topic_id=' . request()->topic_id . '&page_type=' . 'discussion') }}" class="btn btn-lg btn-primary w-100"> <i class="fa fa-comments"></i> Discussion </a>
              </div>
            </div>
            <div class="box" style="border-radius: 5px;">
              <div class="box-body text-center" style="padding: 20px !important;">
               <a href="{{ url('admin/online-class-topic-page/create?course_code=' . request()->course_code . '&module_id=' . request()->module_id . '&topic_id=' . request()->topic_id . '&page_type=' . 'video') }}" class="btn btn-lg btn-primary w-100"> <i class="fa fa-video-camera"></i> Video</a>
              </div>
            </div>
            <div class="box" style="border-radius: 5px;">
              <div class="box-body text-center" style="padding: 20px !important;">
               <a id="assessment" href="#" class="btn btn-lg btn-primary w-100"> <i class="fa fa-question-circle"></i> Assessment</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    {{-- User's Quizzes --}}
    <div class="modal fade" id="quizModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" style="border-radius: 5px;">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="box" style="border-radius: 5px;">
              <div class="box-body text-center" style="padding: 20px !important;">
                <table class='table table-striped table-bordered'>
                  <thead>
                      <th>Title</th>
                      <th>Description</th>
                      <th>Action</th>
                  </thead>
                  <tbody id="tbody">

                  </tbody>
              </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    
  </div>
@endsection

@section('after_styles')

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
  <link rel="stylesheet" href="{{ asset('css/onlineclass/class.css') }}">
  
@endsection

@section('after_scripts')
  
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.7/MathJax.js?config=TeX-MML-AM_CHTML"></script>

  <script type="text/javascript">
    document.getElementById("nav-courses").classList.add("active");

    $(document).ready(function()
    {
        $(document).on('click', '.pagination a',function(event)
        {
            event.preventDefault();
  
            $('li').removeClass('active');
            $(this).parent('li').addClass('active');

            var myurl = $(this).attr('href');
            var page=$(this).attr('href').split('page=')[1];
            var url = 'online-class-topic/api/get-page?course_code={{$selected_topic->module->course->code}}&module_id={{$selected_topic->module->id}}&topic_id={{$selected_topic->id}}&page='+page;

            // Selecting Another Page Ajax
            $.ajax(
            {
                url: url,
                type: "get",
                datatype: "html",
            }).done(function(data){
              $('#page_container').empty();
              $('#page_container').append(data.html);

              // Call setMathjax() function.
              setMathjax();
              // Set Course Navbar Active
              document.getElementById("nav-courses").classList.add("active");
                
            }).fail(function(jqXHR, ajaxOptions, thrownError){
                  alert('No response from server');
            });
        });

        // Display Mathjax / Math Equation
        function setMathjax() {
           MathJax.Hub.Queue(["Typeset",MathJax.Hub]);
        }

        $( "#assessment" ).click(function() {
          $.ajax(
            {
                url: 'online-class-topic/api/get-quiz',
                type: "get",
                datatype: "html",
            }).done(function(data){
              if(data.length > 0)
              {
                var options = "";
                $.each(data, function(index, quiz) {
                  options += "<tr>"+
                              "<td>"+ quiz.title + "</td>" +
                              "<td>"+ quiz.description + "</td>" +
                              "<td>"+ 'Action' + "</td>" +
                            "<tr>";
                });
                $('#tbody').empty();
                $('#tbody').append(options);
              }
              
              
              // Call setMathjax() function.
              $('#addPageModal').modal('hide');
              $('#quizModal').modal('show');
              // Set Course Navbar Active
                
            }).fail(function(jqXHR, ajaxOptions, thrownError){
                  alert('No response from server');
            });
          
        });
  
    });
  </script>
  
  <script>
    // Delete Topic
    $('#deleteTopic').click(function (e) {
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
                window.location.href = "{{ url($crud->route) }}/{{ $selected_topic->id }}/delete?course_code={{ $selected_topic->module->course->code }}&module_id={{ $selected_topic->module->id }}&topic_id={{ $selected_topic->id }}";
              }
            }
           
        }
      });
    });

    function deletePage(id){
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
                window.location.href = "{{ url('admin/online-class-topic-page') }}/"+id+"/delete?course_code={{ $selected_topic->module->course->code }}&module_id={{ $selected_topic->module->id }}&topic_id={{ $selected_topic->id }}&page="+id;
              }
            }
           
        }
      });
    }
    
  </script>



  <!-- CRUD LIST CONTENT - crud_list_scripts stack -->
  @stack('crud_list_scripts')
@endsection
