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