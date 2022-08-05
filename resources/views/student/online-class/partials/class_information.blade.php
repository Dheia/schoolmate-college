<!-- START CLASS SUMMARY / CLASS INFORMATION -->
<div class="box shadow">
  <div class="box-body with-border" style="padding: 20px !important;">
    <span class="dot" style=" position: absolute; height: 35px; width: 10px; background-color:{{ $class->color }};"></span>

    <!-- START SUBJECT CODE -->
    <p style="padding: 0px 15px 0px 15px; margin-top: 0px;  margin-bottom: 0px;">
      {{ $class->subject ? $class->subject->subject_code : 'Unknown Subject Code' }}
    </p>

    <!-- CLASS NAME -->
    <h4 style="padding: 0px 15px 0px 15px; margin: 0px !important">
       {{ $class->subject ? $class->subject->subject_title : 'Unknown Subject Title' }}
       {{ $class->summer ? '(Summer)' : '' }}
    </h4>

    <!-- CLASS TEACHER | SUBJECT -->
    <p style="padding: 0px 15px 0px 15px; margin-top: 0px;  margin-bottom: 0px;">
      {{ $class->teacher ? $class->teacher->prefix . '. ' .  $class->teacher->full_name  : 'Unknown Teacher' }}
    </p>

    <!-- CLASS GRADE LEVEL -->
    <p style="padding: 0px 15px 0px 15px; margin-top: 0px;  margin-bottom: 0px;">
      {{ $class->section ? $class->section->name_level : 'Unknown Section' }}
    </p>

    <!-- CLASS SCHEDULE -->
    <p style="padding: 0px 15px 0px 15px; margin-top: 0px;  margin-bottom: 0px;">
      Schedule : {{ $class->schedule_day }}  {{ $class->schedule_time }} 
    </p>

    <!-- START CLASS BUTTONS -->
    <h4 class="" style="padding: 10px 15px 0px 15px; margin: 0px !important">

      {{-- @if(isset($enrollment))
        @if($enrollment->qr_code)
          <!-- Attendance / QR Code  -->
          <span class="class-btn">
            <a id="tap_in_btn" href="javascript:void(0)" onclick="submitAttendance()" title="Tap In" tap="in">
              <img src="{{ asset('images/icons/login-01.png') }}" alt="...">
            </a>
          </span>
        @endif
      @endif --}}

      <!-- Student List -->
      <span class="class-btn">
        <a href="{{ url('student/online-class/' . $class->code . '/student-list') }}" id="class_code" target="_blank" title="View Student List">
          <img src="{{ asset('images/icons/icons8-bulleted-list-64.png') }}" alt="...">
        </a>
      </span>

      <!-- Join Video Conference -->
      <span class="class-btn">
        @if($class->ongoing && $class->conference_status)
          <a href="{{ url('student/online-class/video/'.$class->code) }}" id="video_conference" target="_blank" title="Join Video Conference">
            <img src="{{ asset('images/icons/join-video.png') }}" alt="...">
          </a>
        @else
          <img src="{{ asset('images/icons/join-video-disabled.png') }}" alt="...">
        @endif
      </span>
      
      <!-- View Recordings -->
      <span class="class-btn">
        <a href="{{ url('student/online-class/' . $class->code . '/recordings') }}" classcode="{{ $class->code }}" target="_blank" title="View Recordings">
            <img src="{{ asset('images/icons/recordings.png') }}" alt="..."> 
        </a>
      </span>

      <!-- View Course -->
      @if($class->course)
        <span class="class-btn">
          <a href="{{ asset('student/online-class/course/'.$class->code) }}" id="class_code" title="View Course">
            <img src="{{ asset('images/icons/icons8-literature-64.png') }}" alt="...">

          </a>
        </span>
      @endif

    </h4>
    <!-- END CLASS BUTTONS -->
  </div>

  <div class="box-footer class-status" style="border-bottom-left-radius: 15px;border-bottom-right-radius: 15px;">
    <div class="col-md-6 col-lg-6 col-sm-6 col-xs-6 {{ $class->ongoing ?  'status-on' : 'status-off' }}">
      <span>
        {{ $class->ongoing ?  'Class is on going' : 'No On-going Class' }}
      </span>
    </div>

    <div class="col-md-6 col-lg-6 col-sm-6 col-xs-6 {{ $class->conference_status ?  'status-on' : 'status-off' }}">
      @if($class->conference_status)
        <span >
          Conference is on going
        </span>
      @else
        <span >
          No On-going Conference
        </span>
      @endif            

    </div>
        
  </div>
</div>
<!-- END CLASS SUMMARY / CLASS INFORMATION -->

@php
  $showQr  = false;

  if( isset($enrollment) ) {
    if( $enrollment ) {
      if( $enrollment->qr_code ) {
        $showQr  = true;
      }
    }
  }

  if(! env('ENABLE_SMART_ID')) {
    $showQr  = false;
  }
@endphp

<!-- Attendance / QR Code  -->
@if($showQr)
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

@push('after_scripts')
  @if($showQr)
    <script type="text/javascript">
      var submitCount = 0;
      function submitAttendance() {
        // if($('#tap_in_btn').attr("tap") == 'in' || $('#tap_in_btn').attr("tap") == 'out')
        // {
        //   alert('asd');
        //   $('#subjectAttendanceForm').submit();
        // }

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
        var enrollment_qrcode = '';

        enrollment_qrcode = '{{ $enrollment ? $enrollment->qr_code : ''}}';

        if(enrollment_qrcode == qrCodeMessage) {
          if(submitCount == 0) { 
            var qrCodeForm  = '<form action="' + '{{ url("student/online-class/qr-code") }}' + '/' + qrCodeMessage + '" method="POST" id="subjectAttendanceForm">' +
                                '@csrf' + 
                                '<input type="hidden" name="class_code" value="' + '{{ $class->code }}' + '">' +
                                '<input type="hidden" name="enrollment_id" value="' + '{{ $enrollment->id }}' + '">' +
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
  @else
    <script>
      var submitCount = 0;
      function submitAttendance() {
        if(submitCount == 0) { 
          var attendanceForm  = '<form action="' + '{{ url("student/online-class/submit-attendance") }}' + '/' + '{{$class->code}}' + '" method="POST" id="subjectAttendanceForm">' +
                                '@csrf' +
                                '<input type="hidden" name="class_code" value="' + '{{ $class->code }}' + '">' +
                                '<input type="hidden" name="student_id" value="' + '{{ $user->id }}' + '">' +
                              '</form>';
          $( "body" ).append(attendanceForm);
          $('#subjectAttendanceForm').submit();
          submitCount++;
        }
      }  
    </script>
  @endif
@endpush