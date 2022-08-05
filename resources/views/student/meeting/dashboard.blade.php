@extends('backpack::layout_student')

@section('header')

@endsection

@section('content')

<div class="row">
  <div class="col-md-12 col-lg-12">
    <div class="row">
      {{-- MAIN PANEL --}}
      <div class="col-md-9 col-lg-9 col-xs-12 oc">

        <div class="col-md-12 col-lg-12 ">
           <h2 class="oc-header-title">My Meetings<br></h2>
        </div> 
        
        <div class="col-md-12 col-lg-12 oc">
          <div class="" style="">
            <div class="" style="">
            {{--   <br><br><br> --}}
               <!-- START SECTION CLASS POSTS -->
              @if($meetings)
                @if(count($meetings)>0)
                  @php 
                    $meeting_index = 0; 
                  @endphp
                  @foreach($meetings as $meeting)
                    @if($meeting_index % 3 ==0)
                      <div class="row">
                    @endif
                    @php
                      $meeting_index =  $meeting_index + 1;
                    @endphp
                    <div class="course_item">
                      <div class="col-xs-12 col-md-4 col-lg-4" >
                        
                          <div class="box shadow" style="border-radius: 5px; border-color: #eee;">
                            <div class="box-header course_image" style="border-radius: 5px 5px 0 0;  background-color:{{ $meeting->color }};">                           
                            </div>

                            <div class="box-body">
                              <div class="row" style="margin: 0">
                                <div class="course_content">
                                  {{-- Meeting Name --}}
                                  <h3 class="course_content_title" style="">
                                    {{ $meeting->name }}
                                  </h3>
                                  {{-- Meeting Description --}}
                                  <h5>
                                    {!! $meeting->description !!}
                                  </h5>
                                  <br>
                                  {{-- Meeting Employee --}}
                                  <h5>
                                    By: {!! $meeting->employee ? $meeting->employee->fullname : 'Unknown Teacher' !!}
                                  </h5>
                                  <br>
                                  {{-- Meeting Date --}}
                                  <h5>
                                    <strong> Date:</strong> {!! date("F j, Y", strtotime($meeting->date)) !!}
                                  </h5>
                                  {{-- Meeting Time --}}
                                  <h5>
                                    <strong>Time:</strong> {!! date("h:i A", strtotime($meeting->start_time)) !!} - {!! date("h:i A", strtotime($meeting->end_time)) !!}
                                  </h5>
                                  <br>
                                  <h5 style=" margin-top: 0px;  margin-bottom: 0px;">
                                    @if($meeting->conference_status)
                                      <a class="btn btn-success btn-block btn-xs" id="video_conference" >
                                        <i class="fa fa-video"></i> 
                                          Meeting On-going
                                      </a>
                                      <a href="javascript:void(0)" 
                                        class="btn btn-default btn-block btn-xs joinVideoConferencing" 
                                        code="{{ $meeting->code }}">
                                        Join Video Conference
                                      </a>
                                      <form action="{{url('student/meeting/video/'.$meeting->code) }}" method="POST" target="_blank" id="join-form{{ $meeting->code }}">
                                          @csrf
                                          <input type="hidden" name="_method" value="GET">

                                          <input type="hidden" name="meeting_id" value="{{$meeting->id}}">
                                          <input type="hidden" name="code" value="{{$meeting->code}}">
                                      </form>
                                    @else
                                      <a class="btn btn-default btn-block btn-xs" id="video_conference" >
                                        <i class="fa fa-video"></i> 
                                        No On-going Meeting
                                      </a>
                                    @endif
                                  </h5>
                                </div>
                              </div>
                            </div>
                            {{-- <div class="box-footer" style="border-radius: 5px; padding-right: 20px !important; ">
                              <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding: 0">
                                
                              </div>
                            </div> --}}
                          </div>
                      </div>
                    </div>
                    @if($meeting_index%3 == 0)
                      </div>
                    @endif
                  @endforeach
                  @if($meeting_index%3 == 0)
                    <div class="row">
                  @endif
                  @if($meeting_index%3 == 0)
                    </div>
                  @endif
                  @if($meeting_index%3 != 0)
                    </div>
                  @endif
                @endif
              @endif
              <!-- END SECTION CLASS POSTS -->
            </div>
          </div>
        </div>

      </div>


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

@section('after_styles')
   <link rel="stylesheet" href="{{ asset('css/onlineclass/class.css') }}">
@endsection

@push('after_scripts')
  <script type="text/javascript">
    $('.joinVideoConferencing').click(function () {
        var meetingCode = $(this).attr('code');
        console.log(meetingCode);
        $('#join-form' + meetingCode).submit();
      });
  </script>

@endpush
