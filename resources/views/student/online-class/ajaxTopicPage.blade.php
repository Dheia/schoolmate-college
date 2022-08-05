 <div class="row p-l-30 p-r-30">
      <div class="col-md-8 col-lg-8 col-one" style="border-radius: 5px;">
        
        <!-- START SECTION CLASS POSTS -->
        <div class="">
          <div class="box shadow">
            <div class="box-body" style="padding: 20px !important;">
              <div class="row">
                <div style="padding: 10px 20px;">
                  <h4 style="padding-bottom: 10px;">Topic: <strong>{!!$topic->title!!}</strong></h4>
                  @if($topic_pages->currentPage()==1)
                    {{-- <div class="row">
                      <div class="col-md-12">
                        @if($prev_topic)
                        <h3 class="pull-left">
                          <a href="{{ asset('student/online-class-topic/'.$class->code).'/'.$prev_topic->module->id.'/'.$prev_topic->id }}">
                            <i class="glyphicon glyphicon-chevron-left"></i> Prev
                          </a>
                        </h3>
                        @endif
                        @if(!count($topic_pages)>0)
                          @if($next_topic)
                            <h3 class="pull-right">
                              <a href="{{ asset('student/online-class-topic/'.$class->code).'/'.$next_topic->module->id.'/'.$next_topic->id }}">
                                Next <i class="glyphicon glyphicon-chevron-right"></i>
                              </a>
                            </h3>
                          @endif
                        @endif
                      </div>
                    </div> --}}
                    <div style="padding: 20px 30px;">
                      {!!$topic->description!!}
                    </div>
                  @endif
                  @if($topic_pages->currentPage() <= $topic_pages->lastPage() && $topic_pages->currentPage() != 1)
                    {{-- <div class="slidecontainer text-center">
                      <input type="range" min="1" max="{{ $topic_pages->total() }}" value="{{ $topic_pages->currentPage() }}" class="slider slider-primary" data-slider-tooltip="show">
                      <label class="text-center">{{ $topic_pages->currentPage() }} / {{ $topic_pages->total() }}</label>
                    </div> --}}
                    <div class="progress-group">
                      <span class="progress-text">Topic Completion</span>
                      <span class="progress-number"><b>{{ $topic_pages->currentPage() }}</b>/{{ $topic_pages->total() }}</span>

                      <div class="progress sm">
                        <div class="progress-bar progress-bar-aqua" style="width: {{ ($topic_pages->currentPage()/$topic_pages->total())*100 }}%"></div>
                      </div>
                    </div>
                  @endif
                  
                </div>
              </div>
              
            </div>
          </div>
          
          @if(!count($topic_pages)>0)
            @if($topic_pages->currentPage() == $topic_pages->lastPage())
              <div class="box">
                <div class="box-body">
                  <div class="row p-r-20">
                    <form method="POST" action="{{ url('student/online-class-topic/'.$class->code).'/'.$topic->module->id.'/'.$topic->id.'/submit-progress' }}">
                    @csrf
                      <h3 class="text-center">
                        You have reached the end of this topic click
                          <button type="submit" class="btn btn-link">
                            <i class="glyphicon glyphicon-check"></i> Finish
                          </button>
                        to continue
                      </h3>
                    </form>
                  </div>
                </div>
              </div>
            @endif
          @endif
        </div>
        <!-- END SECTION CLASS POSTS -->

        @if( count($topic_pages) > 0)
          @foreach($topic_pages as $page_number => $topic_page)
            <div class="box shadow br-t-15 no-padding no-margin">
              <div class="box-body with-border" style="padding: 0 !important; margin: 0;">
                <div class="text-center">
                   {!! $topic_pages->links()!!}
                </div>
              </div>
            </div>
            <div class="box shadow br-b-15">
              <div class="box-body" style="padding: 20px !important;">

                <h4 style="padding-bottom: 10px;">Page {{ request()->page ?? '1' }}: <strong>{{ $topic_page->title }}</strong></h4>
                <div class="row ckeditor" style="padding: 20px 30px;">
                  {!! $topic_page->description !!}
                </div>
                
                @if($topic_page->type == 'discussion')
                  <!-- START DISCUSSION PAGE -->
                  <div class="text-center" style="padding: 20px 30px;">
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
                  </div>
                  <!-- END DISCUSSION PAGE -->
                @elseif($topic_page->type == 'video')
                  <!-- START VIDEO PAGE -->
                  @if($topic_page->video)
                    <div style="padding: 20px 30px;">

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
                      <div style="padding: 20px 30px;">
                        @foreach($topic_page->files as $file)
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
                  <!-- END VIDEO PAGE -->
                @endif
                
                {{-- @if($topic_pages->currentPage() == $topic_pages->lastPage())
                  @if($next_topic)
                    <h3 class="pull-right">
                      <a href="{{ asset('student/online-class-topic/'.$class->code).'/'.$next_topic->module->id.'/'.$next_topic->id }}">
                        Next Topic <i class="glyphicon glyphicon-chevron-right"></i>
                      </a>
                    </h3>
                  @endif
                @endif --}}
              </div>
            </div>
            
            @if($topic_pages->currentPage() == $topic_pages->lastPage())
              <div class="box">
                <div class="box-body">
                  <div class="row p-r-20">
                    <form method="POST" action="{{ url('student/online-class-topic/'.$class->code).'/'.$topic->module->id.'/'.$topic->id.'/submit-progress' }}">
                      @csrf
                      <h3 class="text-center">
                        You have reached the end of this topic click
                        <button type="submit" class="btn btn-link">
                          <i class="glyphicon glyphicon-check"></i> Finish
                        </button>
                        to continue
                      </h3>
                    </form>
                  </div>
                </div>          
              </div>
            @endif
    
          @endforeach
        @endif
        <div class="text-center">
          {!! $topic_pages->links()!!}
         {{--  {{dd($topic_pages->nextPageUrl())}} --}}
        </div>
       
      </div>

      <div class="col-md-4 col-lg-4 col-two">
        <!-- START RIGHT SIDE BAR -->
        <div class="box shadow">
          <div class="box-footer br-t-15" style="padding: 10px;">
              <h4 class="no-padding">
                My Classes
              </h4>
          </div>
           @if(count($my_classes)>0)
            @foreach($my_classes as $my_class)
              @if($my_class->course)
                <div class="box-body" style="padding: 10px;">
                  <div class="container">
                    <h4 class="" style="padding: 0px !important; margin: 0px !important">
                      <span class="circle-span" style="background-color:{{ $my_class->color }};"></span> <a href="{{ asset('student/online-class/course/'.$my_class->code) }}">{{ $my_class->name }}</a>
                    </h4>
                  </div>
                </div>
              @endif
            @endforeach
          @endif
          <div class="box-body" style="padding: 10px;">
            <a href="{{ asset('student/online-class') }}">View all classes</a>
          </div>
          <div class="box-footer" style="padding: 10px;">
              <h4 class="" style="padding: 0px !important; margin: 0px !important">
                Topics
              </h4>
          </div>
          @if(count($topics)>0)
            @foreach($topics as $module_topic)
              <div class="box-body" style="padding: 10px;">
                <div class="container">
                  <h4 class="" style="padding: 0px !important; margin: 0px !important">
                    <a href="{{ asset('student/online-class-topic/'.$class->code).'/'.$module_topic->module->id.'/'.$module_topic->id }}">
                      @if($module_topic->finished) <i class="fa fa-check" ></i> @endif{{ $module_topic->title }}
                    </a>
                  </h4>
                </div>
              </div>
            @endforeach
          @endif
        </div>
        <!-- END RIGHT SIDE BAR -->
      </div>
      
    </div>