@extends('backpack::layout')

@section('header')
@endsection

@section('after_styles')
  <style>
    a.buttons-collection {
      margin-left: 1em;
    }

    #upload {
      opacity: 0;
    }

    #upload-label {
      position: absolute;
      top: 50%;
      left: 1rem;
      transform: translateY(-50%);
    }

    .image-area {
      padding: 1rem;
      position: relative;
    }

    .image-area::before {
      content: 'Uploaded image result';
      color: #fff;
      font-weight: bold;
      text-transform: uppercase;
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      font-size: 0.8rem;
      z-index: 1;
    }

    .image-area img {
      z-index: 2;
      position: relative;
      border: 2px dashed #d2d6de;
    }

    .rounded-pill {
      border-radius: 50rem!important;
    }

    .shadow-sm {
      box-shadow: 0 .125rem .25rem rgba(0,0,0,.075)!important;
    }
    
    .image-upload {
      align-items: stretch; 
      width: 100%; 
      border: 1px solid #d2d6de;
      border-radius: 5px;
    }
  </style>

  {{-- SCHOOLMATE CUSTOM CSS --}}
  {{-- <link rel="stylesheet" href="{{ asset('css') }}/schoolmate/schoolmate.css"> --}}
  <!-- <link rel="stylesheet" href="https://cdn.datatables.net/1.10.23/css/jquery.dataTables.min.css"></style> -->
  <!-- <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.6.5/css/buttons.dataTables.min.css"></style> -->

  <link rel="stylesheet" href="{{ asset('css/dashboard/jquery.dataTables.1.10.23.min.css') }}">
  <link rel="stylesheet" href="{{ asset('css/dashboard/buttons.dataTables.1.6.5.min.css') }}">

@endsection

@section('content')   


  <!-- HEADER -->
  <div class="row" style="padding: 15px;">
    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 smo-search-group"> 
      <section class="content-header">
        <ol class="breadcrumb">
          <li><a href="{{ backpack_url() }}">{{ config('backpack.base.project_name') }}</a></li>
          <li class="active">{{ trans('backpack::base.dashboard') }}</li>
        </ol>
      </section>
      <h1 class="smo-content-title">
        <span class="text-capitalize">{{ trans('backpack::base.dashboard') }}
      {{-- <small>{{ trans('backpack::base.first_page_you_see') }}</small> --}}</span>
      </h1>
    </div>
  </div>
  <!-- END OF HEADER -->

  <!-- SEARCH SECTION -->
  <div class="row">
    <!-- SEARCHBAR -->
    <dashboard-search></dashboard-search>
    <!-- END OF SEARCHBAR -->
  </div>
  <!-- End Of SEARCH SECTION -->

    <!-- INFO BOARD -->
    <div class="row">
      <!-- TOTAL ENROLLMENT START -->
      <!-- TOTAL ENROLLMENT START -->
      <div class="col-md-4" style="padding: 0;">
        <div class="col-md-12 col-sm-12 col-xs-12">
          <div class="info-box shadow">
            <span class="info-box-icon bg-blue"><i class="ion ion-ios-people-outline"></i></span>
            <div class="info-box-content">
              <span class="info-box-text text-primary">TOTAL ENROLLMENT</span>
              <span class="info-box-number">{{$enrolment_data["enrollment_total"]}}</span>
              <h6 class="m-0"><b>Full - {{$enrolment_data["enrollment_data_full"]}}</b></h6>
              <h6 class="m-0"><b>First Term - {{$enrolment_data["enrollment_data_first"]}}</b></h6>
              <h6 class="m-0"><b>Second Term - {{$enrolment_data["enrollment_data_second"]}}</b></h6>
            </div>
          </div>
        </div>

      </div>
      <!-- TOTAL ENROLLMENT END -->

      <!-- UPCOMING MEETINGS START -->
      <div class="col-md-4" style="padding: 0;">
        <div class="col-md-12 col-sm-12 col-xs-12">
          <div class="info-box shadow">
            <span class="info-box-icon bg-blue"><i class="ion ion-ios-videocam"></i></span>
            <div class="info-box-content">
              <span class="info-box-text text-primary">UPCOMING MEETINGS</span>
              @if($nearestMeeting)
                <span class="info-box-number">
                  {{ strtoupper($nearestMeeting->name) }} 
                  <br>
                  <small style="font-size:12px; color: #bbb;">
                    {!! date("F j, Y", strtotime($nearestMeeting->start_at)) !!} 
                    AT 
                    {!! date("h:i A", strtotime($nearestMeeting->start_at)) !!}
                  </small>
                </span>
              @else
                <span class="info-box-number">
                  No Meeting
                </span>
              @endif
            </div>
          </div>
        </div>
        
      </div>
      <!-- UPCOMING MEETINGS END -->

      <!-- SCHOOL CALENDAR START -->
      <div class="col-md-4" style="padding: 0;">
        <div class="col-md-12 col-sm-12 col-xs-12">
          <div class="info-box shadow">
            <span class="info-box-icon bg-blue"><i class="ion ion-ios-calendar"></i></span>
            <div class="info-box-content">
              <span class="info-box-text text-primary">SCHOOLMATE CALENDAR</span>
              @if($upcoming_calendar)
                <span class="info-box-number">
                  <a href="javascript:void(0)" data-toggle="modal" data-target="#calendarModal" data-backdrop="static">
                    {{ strtoupper($upcoming_calendar->title) }}
                  </a> 
                  <br>
                  <small style="font-size:12px; color: #bbb;">
                    {!! date("F j, Y", strtotime($upcoming_calendar->start_at)) !!} 
                    - 
                    {!! date("F j, Y", strtotime($upcoming_calendar->end_at)) !!}
                  </small>
                </span>
              @else
                <span class="info-box-number">
                  No Upcoming Event
                </span>
              @endif
              <!-- <span class="info-box-number">
                OPENING OF CLASSES
                <br>
                <small style="font-size:12px; color: #bbb;">
                  AUGUST 24, 2021
                </small>
              </span> -->
            </div>
          </div>
        </div>
        
      </div>
      <!-- SCHOOL CALENDAR END -->

      <!-- START OF CALENDAR MODAL -->
      @if($upcoming_calendar)
      <div class="modal fade" id="calendarModal" tabindex="-1" role="dialog" aria-labelledby="calendarModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content" style="border-radius: 5px;">
            <div class="modal-header">
              <h4 class="modal-title text-primary" id="calendarModalLabel"><b>Upcoming Event</b></h4>
              <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button> -->
            </div>
            <div class="modal-body">
              <h4> <b> {{ strtoupper($upcoming_calendar->title) }} </b> </h4>
              <h5 class="m-l-30"> {{ $upcoming_calendar->description }} </h5>
              <!-- <br> -->
              <small style="font-size:12px;">
                <i class="fa fa-calendar-day text-primary"></i>
                {!! date("F j, Y", strtotime($upcoming_calendar->start_at)) !!} 
                - 
                {!! date("F j, Y", strtotime($upcoming_calendar->end_at)) !!}
              </small>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
      </div>
      @endif
      <!-- END OF CALENDAR MODAL -->
    </div>
    <!-- INFO BOARD -->

    <div class="row">
      
      <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12" style="padding: 0;">
        <!-- ANNOUNCEMENT BOARD START -->
        <div class="col-md-12 col-sm-12 col-xs-12" id="announcement">
          <div class="info-box shadow" style="min-height: auto;">
            <div class="box-header with-border text-center">
                <h3 class="box-title">Announcement Board</h3>
            </div>
            <div class="box-body p-t-5 p-b-5" style="overflow-y: auto;">
                <div class="p-t-5 p-b-5 p-l-5 p-r-5" id="announcement-body" {{-- style="height:200px;" --}} style="max-height: 500px;">
                  @foreach($announcements as $announcement)
                    <div class="d-block announcement-post" style="border-bottom: 5px solid #e7eaf0; background: #fff; border: 0.5px #ccc solid; border-radius: 10px; padding:15px; margin-bottom: 10px;">
                      {!! $announcement->message !!}
                      <br>

                      @if ($announcement->image)
                        <div class="col-md-12 text-center">
                          <img src="{{ asset($announcement->image) }}" alt="" style="max-height: 200px;">
                        </div>
                      @endif

                      @if($announcement->files)
                        @if( count($announcement->files) > 0 )
                          <h5><b>Files:</b></h5>
                          @foreach ( $announcement->files as $file )
                            <a href="{{ url($file) }}" target="_blank" download="{{ url($file) }}"> {{ url($file) }} </a>
                            @if(!$loop->last)
                                <br>
                            @endif
                          @endforeach
                        @endif
                      @endif

                      <span style="display:block; color:#ccc;font-size:8px; ">{{ \Carbon\Carbon::parse($announcement->created_at)->diffForHumans() }}<span>
                    </div>
                  @endforeach
                </div>
            </div>
            <div class="box-footer">
              <div class="col-md-12">
                @if(backpack_auth()->user()->hasRole('Administrator'))
                  <div class="input-group">
                    <!-- <input type="text" id="message" name="message" placeholder="Type Message ..." class="form-control input-sm"> -->
                    <span class="input-group-btn">
                      <button type="button" id="btnCreateAnnouncement" class="btn btn-warning btn-flat btn-sm w-100" style="height: 30px; border-radius: 5px;" data-toggle="modal" data-target="#announcementModal" data-backdrop="static" data-keyboard="false">Create Announcement</button>
                    </span>
                  </div>
                @endif
              </div>
            </div>
          </div>
        </div>
        <!-- ANNOUNCEMENT BOARD END -->
      </div>
      
      <div class="col-md-4 col-sm-12 col-xs-12">

        @php
          // CHECK IF TAP-IN OR TAP-OUT
          if($system_attendance->tap_in_today && $system_attendance->tap_out_today){
            $tap_attr = null;
            $tap_bg   = 'bg-red';
            $tap_icon = 'fa fa-sign-in';
            $tap_text = 'OUT';
          }
          else if (!$system_attendance->tap_in_today){
            $tap_attr = 'in';
            $tap_bg   = 'bg-default';
            $tap_icon = 'fa fa-sign-in';
            $tap_text = 'TAP';
          }
          else if ($system_attendance->tap_in_today && !$system_attendance->tap_out_today){
            $tap_attr = 'out';
            $tap_bg   = 'bg-green';
            $tap_icon = 'fa fa-sign-out';
            $tap_text = 'IN';
          }
        @endphp

        <div class="row-small">
          <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="info-box shadow">
              <form id="systemTapForm" >
                @csrf
                <a id="systemTapButton" href="javascript:void(0)" onclick="submitTap()" tap="{{ $tap_attr }}">
                  <span id="systemTapIcon" class="info-box-icon {{$tap_bg}}">
                    <h1 style="padding-top: 5px;">{{$tap_text}}</h1>
                  </span>
                </a>
                {{-- <span class="info-box-icon bg-yellow"><i class="ion ion-ios-people-outline"></i></span> --}}
              </form>
              <div class="info-box-content">
                <span class="info-box-text text-warning">SYSTEM TIME IN</span>
                <span class="info-box-number" id="system_time_in">{{ $system_attendance->tap_in_today ?? '-' }}<small></small></span>
                <span class="info-box-text text-warning">SYSTEM TIME OUT</span>
                <span class="info-box-number" id="system_time_out">{{ $system_attendance->tap_out_today ?? '-' }}<small></small></span>
              </div>
            </div>
          </div>
        </div>

        <div class="row-small">
          <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="info-box shadow">
              <span class="info-box-icon bg-yellow"><i class="fa fa-id-card"></i></span>
              <div class="info-box-content">
                <span class="info-box-text text-warning">ID TIME IN</span>
                <span class="info-box-number">{{ $my_attendance->tap_in_today ?? '-' }}<small></small></span>
                <span class="info-box-text text-warning">ID TIME OUT</span>
                <span class="info-box-number">{{ $my_attendance->tap_out_today ?? '-' }}<small></small></span>
              </div>
            </div>
          </div>
        </div>

        <div class="row-small ">
          <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="info-box shadow">
              <span class="info-box-icon bg-aqua"><i class="fa fa-calendar"></i></span>
              <div class="info-box-content">
                <span class="info-box-text text-info">Schedule Today</span>
                <span class="info-box-number">{{ $my_attendance->schedule_timein }} - {{ $my_attendance->schedule_timeout }}</span>
              </div>
            </div>
          </div>
        </div>

        
{{--    <div class="row">
          <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="info-box shadow">
              <span class="info-box-icon bg-red"><i class="fa fa-bell-o"></i></span>
              <div class="info-box-content">
                @csrf
                <span class="info-box-text text-danger">Tap in</span>
                <span class="info-box-number">{{ number_format($total_sms) }}</span>
              </div>
            </div>
          </div>
        </div> --}}

        {{-- <form id="systemTapForm" >
          @csrf
          <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="info-box shadow">
                <a id="systemTapButton" href="javascript:void(0)" onclick="submitTap()" tap="{{ $tap_attr }}">
                  <span id="systemTapIcon" class="info-box-icon {{$tap_bg}}"><i class="fa fa-sign-in"></i></span>
                </a>
                <div class="info-box-content">
                  @csrf
                  <span id="systemTapText" class="info-box-text text-center {{$tap_text}}">
                    {!! $tap_attr ? 'Tap-'. $tap_attr : 'OUT' !!}
                  </span>
                </div>
              </div>
            </div>
          </div>
        </form> --}}
        {{-- ORIGINAL CONTENT --}}

        {{-- <div class="col-md-3 col-sm-12 col-xs-12">
        <div class="row">
          <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="info-box shadow">
              <span class="info-box-icon bg-green"><i class="fa fa-id-card"></i></span>
              <div class="info-box-content">
                <span class="info-box-text text-success">Students TAPS IN</span>
                <span class="info-box-number">{{ number_format($entered_student) }}<small></small></span>
              </div>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="info-box shadow">
              <span class="info-box-icon bg-yellow"><i class="ion ion-ios-people-outline"></i></span>
              <div class="info-box-content">
                <span class="info-box-text text-warning">Employees TAPS IN</span>
                <span class="info-box-number">{{ number_format($entered_employee) }}</span>
              </div>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="info-box shadow">
              <span class="info-box-icon bg-aqua"><i class="fa fa-paper-plane"></i></span>
              <div class="info-box-content">
                <span class="info-box-text text-info">SMS Blasted</span>
                <span class="info-box-number">{{ number_format($total_blast_sms) }}</span>
              </div>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="info-box shadow">
              <span class="info-box-icon bg-red"><i class="fa fa-bell-o"></i></span>
              <div class="info-box-content">
                <span class="info-box-text text-danger">SMS Notificatoins Sent</span>
                <span class="info-box-number">{{ number_format($total_sms) }}</span>
              </div>
            </div>
          </div>
        </div> --}}

      </div>
    </div>
    
    {{-- ENROLLMENT DATA --}}
   <!--  @asyncWidget('enrolmentSummary') -->
    
    <!-- ACCOUNTING INFO -->
    @if(backpack_user()->hasRole('Administrator') || backpack_user()->hasRole('Accounting'))
      
        <!-- @asyncWidget('paymentDues') -->
      
    @endif
    <!-- ACCOUNTING INFO -->

    <!-- Modal -->
    @if(backpack_auth()->user()->hasRole('Administrator'))
    <div class="modal fade" id="announcementModal" tabindex="-1" role="dialog" aria-labelledby="announcementModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content" style="border-radius: 10px;">
          <div class="modal-header">
            <h5 class="modal-title" id="announcementModalLabel">Annoucement</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="row">
              <!-- Message Input -->
              <div class="col-md-12 col-xs-12">
                <div class="form-group">
                  <label for="message">Message</label>
                  <textarea style="border-radius: 5px;" class="form-control" rows="3" id="message" name="message">
                  </textarea>
                </div>
              </div>

              {{-- <div class="col-md-12 col-xs-12 mx-auto">
                <div class="container-fluid" style="padding-left: 0; padding-right: 0;">

                  <!-- Upload image input-->
                  <div class="input-group mb-3 px-2 py-2 bg-white image-upload" style="">
                    <div class="col-md-9 col-xs-12">
                      <input id="upload" type="file" onchange="readURL(this);" class="form-control border-0">
                      <label id="upload-label" for="upload" class="font-weight-light text-muted" style="display: inline-block;">Choose file</label>
                    </div>
                    <!-- Choose File Button -->
                    <div class="input-group-append col-md-3 col-xs-12" style="padding-left: 0; padding-right: 0;">
                        <label for="upload" class="btn btn-light m-0 px-4 w-100"> 
                          <i class="fa fa-cloud-upload mr-2 text-muted"></i> 
                          <small class="text-uppercase font-weight-bold text-muted">
                            Choose file
                          </small>
                        </label>
                    </div>
                  </div>

                  <!-- Uploaded image area-->
                  <div class="col-md-12 col-xs-12">
                    <!-- <p class="font-italic text-white text-center">The image uploaded will be rendered inside the box below.</p> -->
                    <div class="image-area mt-4">
                      <img id="imageResult" src="#" alt="" class="img-fluid rounded shadow-sm mx-auto d-block w-100">
                    </div>
                  </div>
                </div>
              </div> --}}

              <div class="col-md-12 col-xs-12">
                <div class="form-group">
                  <label for="audience">Audience</label>
                  <select id="audience" name="audience" class="form-control" style="border-radius: 5px;">
                    <option value="">Choose audience</option>
                    <option value="global">Global</option>
                    <option value="employee">Employee</option>
                    <option value="parent">Parent</option>
                    <option value="student">Student</option>
                  </select>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="form-group">
                <div class="col-md-4 col-xs-12">
                  <label for="">Announcement Period</label>                    
                  <select name="announcementName" id="announcementPeriod" class="form-control" style="border-radius: 5px;">
                    <option value=""    >Select period</option>
                    <option value="today"    >Today</option>
                    <option value="this_week" >This Week</option>
                    <option value="this_month">This Month</option>
                    <option value="custom"   >Custom</option>
                  </select>
                </div>
                <div class="col-md-4 col-xs-12 start_date" >
                    <label for="start_date">Start Date</label>
                    <input id="date_from" type="date" class="form-control" name="date_from" style="border-radius: 5px;">
                </div>
                <div class="col-md-4 col-xs-12 end_date">
                    <label for="end_date">End Date</label>
                    <input id="date_to" type="date" class="form-control" name="date_to" style="border-radius: 5px;">
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-warning" id="btnPostAnnouncement">Post Announcement</button>
          </div>
        </div>
      </div>
    </div>
    @endif

@endsection


@section('after_scripts')
  <script type="text/javascript" src="{{ asset('vendor/adminlte/bower_components/moment/moment.js') }}"></script>
  <!-- Allow Dropdown -->
  {{-- <script>
    $('.dropdown-toggle').dropdown();
  </script> --}}

  <!-- Vue JS Search Mix -->
  <script src="{{ mix('js/search.js') }}"></script>

  <script src="{{ asset('js/chart.min.js') }}"></script>
  
  {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/0.2.0/Chart.min.js"></script> --}}
  <script src="{{ asset('js/palette.js') }}"></script>

  
  {{-- TAP IN / TAP OUT SCRIPT --}}
  <script type="text/javascript">
    // TAP IN
    function submitTap()
    {
      var tap = $('#systemTapButton').attr("tap");
      if($('#systemTapButton').attr("tap") == 'in' || $('#systemTapButton').attr("tap") == 'out')
      {
        
        // $('#systemTapForm').submit();
        submitSystemTapForm();
      }
    }
    function submitSystemTapForm() {

      event.preventDefault();

      var tap = $('#systemTapButton').attr("tap");

      $.ajax({
        url: "system-attendance/tap-"+tap,
        type:"POST",
        data:{
          "_token": "{{ csrf_token() }}"
        },
        success:function(data){
          new PNotify({
              title: data.title,
              text: data.message,
              type: data.error ? 'warning' : 'success'
          });
          if(!data.error)
          {
            if($('#systemTapButton').attr("tap") == 'in')
            {
              $('#system_time_in').text(data.data);
              $('#systemTapIcon').removeClass('bg-red');
              $('#systemTapIcon').addClass('bg-green');
              $('#systemTapButton').attr("tap", "out");
              $('#systemTapIcon').html('<h1 style="padding-top: 5px;">IN</h1>');
            }
            else
            {
              $('#system_time_out').text(data.data);
              $('#systemTapIcon').removeClass('bg-green');
              $('#systemTapIcon').addClass('bg-red');
              $('#systemTapButton').removeAttr('tap');
              $('#systemTapIcon').html('<h1 style="padding-top: 5px;">OUT</h1>');
            }
          }

        },
        error:function(data){
          new PNotify({
              title: "Error",
              text: 'Something Went Wrong, Please Try Again.',
              type: 'warning'
          });
        }
      });
    }
  </script>

  @if(backpack_auth()->user()->hasRole('Administrator'))
    {{-- ANNOUNCEMENTS --}}
    <script>
      const announcementData = []; 

      $(document).ready(function () {
        $('.start_date, .end_date').css('display', 'none');      


        var today = new Date();
        var dd    = today.getDate();
        var mm    = today.getMonth() + 1; //January is 0!
        var yyyy  = today.getFullYear();

        if (dd < 10) {
          dd = '0' + dd;
        }

        if (mm < 10) {
          mm = '0' + mm;
        }

        today = yyyy + "-" + mm;

        $('#btnPostAnnouncement').click(function () {

          var message  = $('#announcementModal #message').val();
          var audience = $('select[name="audience"]').find('option:selected').val();   

          var period    = $('#announcementPeriod option:selected').val();
          var date_from = $('#date_from').val();
          var date_to   = $('#date_to').val(); 

          if(message == '') {
            alert('Please Enter A Message');
            return;
          }

          if(! audience) {
            alert('Please Select an audience');
            return;
          }

          if(! ['global', 'employee', 'parent', 'student'].includes(audience)) {
            alert('Invalid Audience!');
            return;
          }

          announcementData['message']   = message;
          announcementData['audience']  = audience;
          announcementData['period']    = period;
          announcementData['date_from'] = date_from;
          announcementData['date_to']   = date_to;

          validatePeriod();

          postAnnouncement(announcementData);

        });

        $('#announcementPeriod').on('change', function () {
            if(this.value == "custom") {
              $('.start_date, .end_date').css('display', 'block');
            } else {
              $('.start_date, .end_date').css('display', 'none');
            }
        });

        function validatePeriod()
        {
          if(announcementData['period'] == 'custom') {
            if(announcementData['date_from'] == "" || announcementData['date_to'] == "") {
                alert("Please Enter A Date");
                return;
            }
            if(announcementData['date_from'] > announcementData['date_to']) {
                alert("End Date should be Greater than or Equal to Start Date");
                return;
            }
          }
        }

        // POST THE ANNOUNCEMENT
        function postAnnouncement(data)
        {
          $.ajax({
            url: 'api/announcement/post',
            method: 'post',
            data:{
              message: announcementData['message'],
              audience: announcementData['audience'],
              period: announcementData['period'],
              date_from: announcementData['date_from'],
              date_to: announcementData['date_to'],
              _token:"{{csrf_token()}}"
            },
            success: function (response) {
              if(response.status == 'success') {
                var announcement = 
                '<div class="d-block announcement-post bg-blue" style="border-bottom: 5px solid #e7eaf0; background: #fff; border: 0.5px #ccc solid; border-radius: 10px; padding:15px; margin-bottom: 10px;">' +
                    response.data.message + 
                    '<span style="display:block; color:#ccc;font-size:8px; "> 1 seconds ago </span>'+
                '</div>';
                $('#announcement-body').prepend(announcement);

                success();
              }
            },
            error:function(data){
              new PNotify({
                  title: "Error",
                  text: 'Something Went Wrong, Please Try Again.',
                  type: 'warning'
              });
            }
          });
          $('#announcement #message').val('');
        }

        function success()
        {
          $('#announcementModal #message').val('');
          $('#date_from').val();
          $('#date_to').val();

          $('#announcementModal').hide();
          $('.modal-backdrop').remove();
          $("body").css("padding-right","0");
          
        }

        /*  ==========================================
            SHOW UPLOADED IMAGE
        * ========================================== */
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#imageResult')
                        .attr('src', e.target.result);
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        $(function () {
            $('#upload').on('change', function () {
                readURL(input);
            });
        });

        /*  ==========================================
            SHOW UPLOADED IMAGE NAME
        * ========================================== */
        var input = document.getElementById( 'upload' );
        var infoArea = document.getElementById( 'upload-label' );

        // input.addEventListener( 'change', showFileName );
        // function showFileName( event ) {
        //   var input = event.srcElement;
        //   var fileName = input.files[0].name;
        //   infoArea.textContent = 'File name: ' + fileName;
        // }
      });
    </script>
  @endif

  <!-- <script type="text/javascript"src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script> -->

  <!-- <script type="text/javascript"src="https://cdn.datatables.net/buttons/1.6.5/js/dataTables.buttons.min.js"></script> -->
  <!-- <script type="text/javascript"src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.flash.min.js"></script> -->
  <!-- <script type="text/javascript"src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script> -->
  <!-- <script type="text/javascript"src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script> -->
  <!-- <script type="text/javascript"src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script> -->
  <!-- <script type="text/javascript"src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.html5.min.js"></script> -->
  <!-- <script type="text/javascript"src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.print.min.js"></script> -->
  <!-- <script type="text/javascript"src="https://cdn.datatables.net/buttons/1.6.5/js/dataTables.buttons.min.js"></script> -->

  <script type="text/javascript"src="{{ asset('js/dashboard/jquery.dataTables.1.10.23.min.js') }}"></script>
  <script type="text/javascript"src="{{ asset('js/dashboard/dataTables.buttons.1.6.5.min.js') }}"></script>
  <script type="text/javascript"src="{{ asset('js/dashboard/buttons.flash.1.6.5.min.js') }}"></script>
  <script type="text/javascript"src="{{ asset('js/dashboard/jszip.3.1.3.min.js') }}"></script>
  <!-- <script type="text/javascript"src="{{ asset('js/dashboard/pdfmake.0.1.53.min.js') }}"></script> -->
  {{-- <script type="text/javascript"src="{{ asset('js/dashboard/vfs_fonts.0.1.53.js') }}"></script> --}}
  <script type="text/javascript"src="{{ asset('js/dashboard/buttons.html5.1.6.5.min.js') }}"></script>
  <script type="text/javascript"src="{{ asset('js/dashboard/buttons.print.1.6.5.min.js') }}"></script>

  
@endsection