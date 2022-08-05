@extends('backpack::layout_student')

@push('after_styles')
  <link rel="stylesheet" type="text/css" href="{{ asset('css/onlineclass/class.css') }}">
@endpush

@section('content')
  <div class="row">
    <div class="col-md-12 col-lg-12 oc pt-0">
      <div class="row">
        {{-- MAIN PANEL START --}}
        <div class="col-md-9 col-lg-9 col-xs-12 oc pt-0">
          <div class="col-md-12 col-lg-12">
            @include('student/online-class/partials/navbar')
          </div>
  
          <div class="col-md-12 col-lg-12">
             <h2 class="oc-header-title">My Classes</h2>
          </div> 
          <div class="col-md-12 col-lg-12 oc">
            <div class="" style="">
              <div class="" style="">
                @if($my_classes)
                  @if(count($my_classes)>0)
                    @php
                      $class_count = 0;
                    @endphp
                    @foreach($my_classes as $my_class)

                      @if($class_count%2 == 0)
                        <div class="row">
                      @endif

                        <div class="col-xs-12 col-md-6 col-lg-6">
                            <div class="box shadow">
                              <div class="box-body" style="padding: 20px !important;">
                                <span class="dot" style="position: absolute; z-index: 999; height: 55%; background-color:{{ $my_class->color }};"></span>
                                <div class="row">
                                  <div class="">
                                    
                                    <!-- Right Circle -->
                                    <span class="dot" style="right: 10px; width: 10px; height: 10px; background-color: {{$my_class->ongoing ? '#1cc88a' : '#e1e1e1'}};"></span>

                                    <!-- Subject Code and Class Code -->
                                    <h6 class = "class-desc"> 
                                        {{ $my_class->subject_code }}
                                    </h6>

                                    <!-- Class Name -->
                                    <a href="{{ asset('student/online-post?class_code='.$my_class->code) }}">
                                      @if($my_class->subject)
                                        <h4 class="class-header">
                                          {{ $my_class->subject->subject_title ? $my_class->subject->subject_title : '-' }}
                                          {{ $my_class->summer ? '(Summer)' : '' }}
                                        </h4>
                                      @else
                                        <h4>Unknown Subject {{ $my_class->summer ? '(Summer)' : '' }} </h4>
                                      @endif
                                    </a>

                                    <!-- Class Teacher -->
                                    <h6 class = "class-desc">
                                      {{ $my_class->teacher ? $my_class->teacher->prefix . '. ' .  $my_class->teacher->full_name  : 'Unknown Teacher' }}
                                    </h6>

                                    <!-- Grade and Section -->
                                    @if($my_class->section)
                                      <h6 class = "class-desc">
                                          {{ $my_class->section->name_level }} | {{ $my_class->section->track_code }}
                                      </h6>
                                    @endif

                                    <!-- Video Conference Status -->
                                    {{-- <h5 style="padding: 5px 15px 0px 30px; margin-top: 0px; margin-bottom: 0px;">
                                      <span id="video_conference" class="badge {{ $my_class->conference_status ?  'label-success' : 'label-default' }} smo-vc">
                                        <i class="fa fa-video-camera"></i> 
                                        {{ $my_class->conference_status ?  'Video Conference On-going' : 'No On-going Video Conference' }}  
                                      </span>
                                    </h5> --}}
                                    <h5 style="padding: 5px 15px 0px 30px; margin-top: 0px; margin-bottom: 0px;">
                                      <span id="video_conference" class="badge {{ $my_class->ongoing ?  'label-success' : 'label-default' }} smo-vc">
                                        <i class="fa fa-video-camera"></i> 
                                        {{ $my_class->ongoing ?  'Class is on going' : 'No On-going Class' }}  
                                      </span>
                                    </h5>

                                  </div>
                                </div>
                              </div>
                            </div>
                        </div>

                      @if($class_count%2 != 0)   
                        </div>
                      @elseif($loop->last)
                        </div>
                      @endif
                      
                      @php
                        $class_count =  $class_count + 1;
                      @endphp
                    @endforeach
                  @else
                    <div>
                      <div class="col-xs-12 col-md-12 col-lg-12">
                        <div class="box" style="border-radius: 5px;">
                          <div class="box-body" style="padding: 10px; height: 120px;">
                            <div class="text-center" style="margin-top: 40px !important;">
                              <h3 class="text-center">
                                No available class
                              </h3>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  @endif
                @else
                  <div>
                    <div class="col-xs-12 col-md-12 col-lg-12">
                      <div class="box" style="border-radius: 5px;">
                        <div class="box-body" style="padding: 10px; height: 120px;">
                          <div class="text-center" style="margin-top: 40px !important;">
                            <h3 class="text-center">
                              No available class
                            </h3>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                @endif
                <!-- END SECTION CLASS POSTS -->
              </div>
            </div>
          </div>

        </div>
        {{-- MAIN PANEL END --}}

        {{-- LEFT PANEL START --}}
        <div class="col-md-3 col-lg-3 col-xs-12 oc pt-0">
          {{-- USER ACCOUNT PANEL --}}
          <div class="col-md-12 col-lg-12 col-xs-12 oc-box shadow">
            {{-- <i class="oc-icon oc-icon-profile-male"></i> --}}
            @include('backpack::inc.sidebar_student_panel')
          </div>
          <div class="col-md-12 col-lg-12 col-xs-12 oc oc-box shadow">
            {{-- <i class="oc-icon oc-icon-desktop"></i> --}}
            <h5 class="oc-user text-center">Content</h5>
          </div>
        </div>
        {{-- LEFT PANEL END --}}
      </div>
    </div>
  </div>
@endsection

@section('after_scripts')

<script>
  document.getElementById("nav-classes").classList.add("active");

  // function getMeetingInfo(meetingId) {
   
  //   var meetingId = meetingId;

  //     $.get('/admin/teacher-online-class/video_conference_status',{meetingId: meetingId}, function( data ) {
  //       if(data.video_conference_info.original.returncode == "SUCCESS"){
  //         var info = data.video_conference_info.original;
          
  //         if(info.returncode == "SUCCESS"){
  //           $('#video_conference').removeClass('btn-default');
  //           $('#video_conference').addClass('btn-success');
  //           $('#video_conference').html('Video Conference On-Going');
  //         }
  //       }
        
  //     });
  // }

</script>

@endsection
