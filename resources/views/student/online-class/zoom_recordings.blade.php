@extends('backpack::layout_student')

@section('header')
@endsection

@section('content')
  <link rel="stylesheet" type="text/css" href="{{asset('css/onlineclass/class.css')}}">

  <div class="row p-l-30 p-r-30">
    @include('student/online-class/partials/navbar')
  </div>
  
  <div class="row p-l-30 p-r-30">
    <!-- START RIGHT SIDEBAR -->
    <div class="col-md-4 col-lg-4 col-two">
      <!-- START CLASS INFORMATION -->
      @include('student/online-class/partials/class_information')
      <!-- END CLASS INFORMATION -->

      <!-- Start Quipper Account -->
      {{-- @include('student/online-class/partials/quipperAccount') --}}
      <!-- END Quipper Account -->
    </div>
    <!-- END RIGHT SIDEBAR -->

    <div class="col-md-8 col-lg-8 col-one" style="border-radius: 5px;">

      <!-- START STUDENT LIST -->
      <div class="box shadow" style="border-radius: 10px;">
          <div class="box-body with-border" style="padding: 20px !important;">
            <div class="row">
              <div style="padding: 10px 20px;">
                <h4 style="padding-bottom: 10px;">Class Recordings</h4>
                <div style="padding: 20px 30px;">
                  <table class="table table-striped table-bordered">
                    <thead>
                        <th>Date</th>
                        <th>Start Time</th>
                        <th>End Time</th>
                        <th>Password</th>
                        <th>Audio</th>
                        <th>Share Screen</th>
                        <th>Action</th>
                    </thead>
                    <tbody>
                        @if(count($recordings) > 0)
                            @foreach ($recordings as $record)
                                @php
                                    $meeting      = $record->meetingable;
                                    $zoom_meeting = $record->zoomMeeting;
                                @endphp
                                <tr>
                                    <td>{{ date("F d, Y", strtotime($zoom_meeting->created_at)) }}</td>
                                    <td>
                                        {{ date("h:i:s A", strtotime($zoom_meeting->start_time)) }}
                                    </td>
                                    <td>
                                        {{ date("h:i:s A", strtotime($zoom_meeting->end_time)) }}
                                    </td>
                                    <td>{{ $record->password }}</td>
                                    <td>
                                        @if($record->audio_only)
                                            <a href="{{ $record->audio_only->play_url }}" target="_blank" class="btn btn-xs btn-info" title="Play">
                                                <i class="fa fa-play"></i>
                                            </a>
                                            <a href="{{ $record->audio_only->download_url }}" target="_blank" class="btn btn-xs btn-info" title="Download">
                                                <i class="fa fa-download"></i>
                                            </a>
                                        @endif
                                    </td>
                                    <td>
                                        @if($record->shared_screen_with_speaker_view)
                                            <a href="{{ $record->shared_screen_with_speaker_view->play_url }}" target="_blank" class="btn btn-xs btn-info" title="Play">
                                                <i class="fa fa-play"></i>
                                            </a>
                                            <a href="{{ $record->shared_screen_with_speaker_view->download_url }}" target="_blank" class="btn btn-xs btn-info" title="Download">
                                                <i class="fa fa-download"></i>
                                            </a>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ url($record->share_url) }}" class="btn btn-xs btn-info" target="_blank" title="Play">
                                            <i class="fa fa-play"></i> Play
                                        </a>
                                    </td>
                                </tr>    
                            @endforeach
                        @else
                          <tr>
                            <td colspan="4" class="text-center">
                              No Recordings Found
                            </td>
                          </tr>
                        @endif
                      </tbody>
                      <tfoot>
                        <tr>
                            <th>Date</th>
                            <th>Start Time</th>
                            <th>End Time</th>
                            <th>Password</th>
                            <th>Audio</th>
                            <th>Share Screen</th>
                            <th>Action</th>
                        </tr>
                      </tfoot>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      <!-- END STUDENT LIST -->

    </div>
  
  </div>

  <script type="text/javascript">
    document.getElementById("nav-explore").classList.add("active");
  </script>

@endsection

@section('after_styles')
@endsection

@section('after_scripts')
@endsection
