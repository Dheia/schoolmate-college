<!--START RIGHT SIDEBAR -->
<div class="col-md-4 col-lg-4 col-two">

  @if(isset($class))

    <!-- START CLASS SUMMARY / CLASS INFORMATION -->
    <div class="box shadow">
      <div class="box-body with-border" style="padding: 20px !important;">

        <span class="dot" style=" position: absolute; height: 35px; width: 10px; background-color:{{ $class->color }};"></span>

        <!-- START SUBJECT CODE -->
        <p style="padding: 0px 15px 0px 15px; margin-top: 0px;  margin-bottom: 0px;">
          {{ $class->subject ? $class->subject->subject_code : 'Unknown Subject Code' }}
        </p>

        <!-- SUBJECT TITLE -->
        <h4 style="padding: 0px 15px 0px 15px; margin: 0px !important">
           {{ $class->subject ? $class->subject->subject_title : 'Unknown Subject Title' }}
           {{ $class->summer ? '(Summer)' : '' }}
        </h4>

        <!-- CLASS TEACHER -->
        <p style="padding: 0px 15px 0px 15px; margin-top: 0px;  margin-bottom: 0px;">
          {{ $class->teacher ? $class->teacher->prefix . '. ' .  $class->teacher->full_name  : 'Unknown Teacher' }}
        </p>

        <!-- CLASS GRADE LEVEL / SECTION -->
        <p style="padding: 0px 15px 0px 15px; margin-top: 0px;  margin-bottom: 0px;">
          {{ $class->section ? $class->section->name_level : 'Unknown Section' }}
        </p>

        <!-- CLASS SCHEDULE -->
        <p style="padding: 0px 15px 0px 15px; margin-top: 0px;  margin-bottom: 0px;">
          Schedule : {{ $class->schedule_day }}  {{ $class->schedule_time }} 
        </p>

        <h4 class="class-btn-set">

          <!-- Student List -->
          <span class="class-btn">
            <a href="{{ url('admin/teacher-online-class/' . $class->code . '/student-list') }}" id="class_code" target="_blank" title="View Student List">
                <img src="{{ asset('images/icons/icons8-bulleted-list-64.png') }}" alt="...">
            </a>
          </span>

          <!-- View Course -->
          @if($class->course)
            <span class="class-btn">
              <a href="{{ url('admin/teacher-online-class/' . $class->code . '/course') }}" id="class_code" target="_blank" title="View Course">
                <img src="{{ asset('images/icons/icons8-literature-64.png') }}" alt="...">
              </a>
            </span>
          @endif

          <!-- Start/End Class -->
          @if(backpack_auth()->user()->hasRole('School Head') || backpack_auth()->user()->employee_id == $class->teacher_id || $class->isTeacherSubstitute)
            <span class="class-btn">
              <form action="{{url('admin/online-class/on-going/set') }}" method="POST" id="onGoingForm">
                @csrf
                <input type="hidden" name="_method" value="POST">
                <input type="hidden" name="class_id" value="{{$class->id}}">
                <input type="hidden" name="class_code" value="{{$class->code}}">
                <button type="submit" class="btn-img" id="btnOnGoing" classcode="{{ $class->code }}" classid="{{$class->id}}">
                  <img src=" {{ $class->ongoing ?  asset('images/icons/end-class.png') : asset('images/icons/start-class.png') }} " alt="..." title="{{ $class->ongoing ?  'End Class': 'Start Class' }} ">
                </button>
              </form>
            </span>
          @endif

          <span class="class-btn">

            @if(backpack_auth()->user()->employee_id == $class->teacher_id || $class->isTeacherSubstitute)

              @if($class->ongoing)
                @if($class->conference_status)
                  <a href="{{ url('admin/teacher-online-class/join-conference/'.$class->code) }}" id="video_conference" target="_blank" title="Join Video Conference">
                    <img src="{{ asset('images/icons/join-video.png') }}" alt="...">
                  </a>
                @else
                  <form action="{{url('admin/teacher-online-class/video_conference') }}" method="GET" target="_blank" id="form{{ $class->code }}">
                    @csrf
                    <input type="hidden" name="_method" value="GET">
                    <input type="hidden" name="classid" value="{{$class->id}}">
                    <input type="hidden" name="class_code" value="{{$class->code}}">
                    <button type="submit" class="btn-img" classcode="{{ $class->code }}" class="startVideoConferencing" title="Start Video Conference">
                      <img src="{{ asset('images/icons/start-video.png') }}" alt="...">
                    </button>
                  </form>
                @endif
              @else
                <img src="{{ asset('images/icons/start-video-disabled.png') }}" alt="...">
              @endif

            @else

              @if($class->ongoing)
                @if($class->conference_status)
                  <a href="{{ url('admin/teacher-online-class/join-conference/'.$class->code) }}" id="video_conference" target="_blank" title="Join Video Conference">
                    <img src="{{ asset('images/icons/join-video.png') }}" alt="...">
                  </a>
                @else
                  <form action="{{url('admin/teacher-online-class/video_conference') }}" method="GET" target="_blank" id="form{{ $class->code }}">
                    @csrf
                    <input type="hidden" name="_method" value="GET">
                    <input type="hidden" name="classid" value="{{$class->id}}">
                    <input type="hidden" name="class_code" value="{{$class->code}}">
                    <button type="submit" class="btn-img" classcode="{{ $class->code }}" class="startVideoConferencing" title="Start Video Conference">
                      <img src="{{ asset('images/icons/start-video.png') }}" alt="...">
                    </button>
                  </form>
                @endif
              @else
                <img src="{{ asset('images/icons/join-video-disabled.png') }}" alt="...">
              @endif

            @endif

          </span>

          <!-- Class Recordings -->
          @if(backpack_auth()->user()->hasRole('School Head') || backpack_auth()->user()->employee_id == $class->teacher_id || $class->isTeacherSubstitute)
            <span class="class-btn">
              <a href="{{ url('admin/teacher-online-class/' . $class->code . '/recordings') }}" classcode="{{ $class->code }}" target="_blank" title="View Recordings">
                  <img src="{{ asset('images/icons/recordings.png') }}" alt="..."> 
              </a>
            </span>
          @endif

          <!-- Class Settings -->
          @if(backpack_auth()->user()->hasRole('Administrator') || backpack_auth()->user()->employee_id == $class->teacher_id || $class->isTeacherSubstitute)
            <span class="class-btn">
              <a href="{{ url('admin/teacher-online-class/' . $class->id . '/edit?class_code=' . $class->code) }}"  class="btn btn-primary btn-xs" id="class_code" target="_blank" title="Edit Course">
                  <img src="{{ asset('images/icons/icons8-gear-64.png') }}" alt="...">
              </a>
            </span>
          @endif
        </h4>
        
      </div>

      <!-- Class Information Footer -->
      <div class="box-footer class-status" style="border-bottom-left-radius: 15px;border-bottom-right-radius: 15px;">
        <div class="col-md-6 col-lg-6 col-sm-6 col-xs-6 {{ $class->ongoing ?  'status-on' : 'status-off' }}">
          <span>
            {{ $class->ongoing ?  'Class is on going' : 'No On-going Class' }}
          </span>
        </div>

        <div class="col-md-6 col-lg-6 col-sm-6 col-xs-6 {{ $class->conference_status ?  'status-on' : 'status-off' }}">
          <span >
            {{ $class->conference_status ?  'Conference is on going' : 'No On-going Conference' }}
          </span>
        </div>
            
      </div>
    </div>
    <!-- END CLASS SUMMARY / CLASS INFORMATION -->

    <!-- Attendance / QR Code  -->
    @if(isset($class_attendance))
      @if(env('ENABLE_SMART_ID'))
        @if(isset($user))
          @if($user->employee)
            @if($user->employee->qr_code)
              <div class="info-box shadow">
                <a id="tap_in_btn" href="javascript:void(0)" onclick="submitAttendance()" title="{{ $class_attendance->time_in ? 'Tap Out' : 'Tap In' }}">
                  <span id="systemTapIcon" class="info-box-icon" style="padding: 10px;">
                    <img style="vertical-align: baseline;" src="{{ $class_attendance->time_in ? asset('images/icons/logout-01.png') : asset('images/icons/login-01.png') }}" alt="...">
                  </span>
                </a>        
                <div class="info-box-content">
                  <span class="info-box-text text-warning">TIME IN</span>
                  <span class="info-box-number" id="system_time_in"> {{ $class_attendance->time_in ? date("h:i:s A", strtotime($class_attendance->time_in)) : '-' }} <small></small></span>
                  <span class="info-box-text text-warning">TIME OUT</span>
                  <span class="info-box-number" id="system_time_out"> {{ $class_attendance->time_out ? date("h:i:s A", strtotime($class_attendance->time_out)) : '-' }} <small></small></span>
                </div>
              </div>
            @endif
          @endif
        @endif
      @else
        <div class="info-box shadow">
          <a id="tap_in_btn" href="javascript:void(0)" onclick="submitAttendance()" title="{{ $class_attendance->time_in ? 'Tap Out' : 'Tap In' }}">
            <span id="systemTapIcon" class="info-box-icon" style="padding: 10px;">
              <img style="vertical-align: baseline;" src="{{ $class_attendance->time_in ? asset('images/icons/logout-01.png') : asset('images/icons/login-01.png') }}" alt="...">
            </span>
          </a>        
          <div class="info-box-content">
            <span class="info-box-text text-warning">TIME IN</span>
            <span class="info-box-number" id="system_time_in"> {{ $class_attendance->time_in ? date("h:i:s A", strtotime($class_attendance->time_in)) : '-' }} <small></small></span>
            <span class="info-box-text text-warning">TIME OUT</span>
            <span class="info-box-number" id="system_time_out"> {{ $class_attendance->time_out ? date("h:i:s A", strtotime($class_attendance->time_out)) : '-' }} <small></small></span>
          </div>
        </div>
      @endif
    @endif
    
    {{-- @if($class->link_to_quipper)
      <!-- Start Quipper Account -->
      <div class="box shadow">
        <div class="box-body with-border" style="padding: 20px !important;">
          <!-- Quipper Logo -->
          <h4 style="padding: 0px 15px 0px 15px; margin: 0px !important; text-align: center;">
             <img src="{{asset('images/quipper.png')}}" style="width: 100px;">
          </h4>

          <!-- Quipper Link -->
          <h4 style="padding: 10px 15px 0px 15px; margin: 0px !important">
            <a class="btn btn-primary btn-block" href="https://link.quipper.com/en/login" target="_blank">
              <i class="fa fa-video"></i> 
              Proceed to Quipper
            </a>
          </h4>
        </div>
      </div>
      <!-- END Quipper Account -->
    @endif --}}

  @endif
  
</div>
<!-- END RIGHT SIDEBAR -->

@if(env('ENABLE_SMART_ID'))
  @if(isset($user))
    @if($user->employee)
      @if($user->employee->qr_code)

        <!-- QR CODE Modal -->
        <div id="myModal" class="modal fade" role="dialog">
          <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Attendance</h4>
              </div>
              <div class="modal-body text-center">
                <div style="width: 500px; margin-left: auto; margin-right: auto;" id="reader"></div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              </div>
            </div>

          </div>
        </div>

        @push('after_scripts')
          <!-- HTML5 QR SCRIPT -->
          <script src="{{ asset('js/html5-qrcode.min.js') }}"></script>

          <script type="text/javascript">
            var submitCount = 0;
            function submitAttendance() {

              $('#myModal').modal({
                show: true,
                backdrop: 'static',
                keyboard: false
              });

              setTimeout(function(){ 
                var html5QrcodeScanner = new Html5QrcodeScanner(
                "reader", { fps: 10, qrbox: 200 });
                html5QrcodeScanner.render(onScanSuccess);
              }, 100);
            }

            function onScanSuccess(qrCodeMessage) {
              // handle on success condition with the decoded message
              // alert(1);
              var employee_qrcode = '';

              employee_qrcode = '{{ $user->employee->qr_code }}';

              if(employee_qrcode == qrCodeMessage) {
                if(submitCount == 0) { 
                  var qrCodeForm  = '<form action="' + '{{ url("admin/online-class/qr-code") }}' + '/' + qrCodeMessage + '" method="POST" id="subjectAttendanceForm">' +
                                      '@csrf' + 
                                      '<input type="hidden" name="class_code" value="' + '{{ $class->code }}' + '">' +
                                      '<input type="hidden" name="employee_id" value="' + '{{ $user->employee->id }}' + '">' +
                                    '</form>';
                  $( "body" ).append(qrCodeForm);
                  $('#subjectAttendanceForm').submit();
                  submitCount++;
                }
                html5QrcodeScanner.clear();
              }
              // alert(qrCodeMessage);
            }
          </script>
        @endpush

      @endif
    @endif
  @endif

@else
  @push('after_scripts')
    <script>
      var submitCount = 0;
      function submitAttendance() {
        if(submitCount == 0) { 
          var attendanceForm  = '<form action="' + '{{ url("admin/online-class/attendance/employee") }}' + '/' + '{{$class->code}}' + '/' + '{{$user->employee->id}}' + '" method="POST" id="subjectAttendanceForm">' +
                                '@csrf' +
                                '<input type="hidden" name="class_code" value="' + '{{ $class->code }}' + '">' +
                                '<input type="hidden" name="employee_id" value="' + '{{ $user->employee->id }}' + '">' +
                              '</form>';
          $( "body" ).append(attendanceForm);
          $('#subjectAttendanceForm').submit();
          submitCount++;
        }
      }  
    </script>
  @endpush
@endif