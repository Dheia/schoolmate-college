@extends('backpack::layout')

@push('after_styles')
  <link rel="stylesheet" href="{{ asset('css/onlineclass/class.css') }}">
  <link rel="stylesheet" href="{{ asset('vendor/adminlte/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker3.css') }}">
  <link rel="stylesheet" href="{{ asset('vendor/adminlte/bower_components/bootstrap-daterangepicker/daterangepicker.css') }}">
@endpush

@section('content')
  
  <!-- START NAVBAR -->
  <div class="row p-l-20 p-r-20">
    @include('onlineClass/partials/navbar')
  </div>
  <!-- END NAVBAR -->
  
  <div class="row p-l-20 p-r-20">

    <!-- START RIGHT SIDEBAR -->
    @include('onlineClass/partials/right_sidebar')
    <!-- END RIGHT SIDEBAR -->

    <div class="col-md-8 col-lg-8 col-one" style="border-radius: 10px;">
      <!-- START STUDENT LIST -->
      <div>
        <div class="box shadow" style="border-radius: 10px;">
          <div class="box-body with-border" style="padding: 20px !important;">
            <div class="row">
              <div style="padding: 10px 20px;">
                <h4 style="padding-bottom: 10px;">Class Attendance</h4>

                <!-- DATE PICKER -->
                <div style="padding: 0 30px;">
                  <div class="row">
                    <div class='col-sm-6'>
                       <div class="form-group">
                          {{-- <input type="text" name="daterange" id="daterange" /> --}}
                          <div class='input-group date'>
                             <input type='text' class="form-control" name="daterange" id="daterange"/>
                             <span class="input-group-addon">
                             <span class="fa fa-calendar"></span>
                             </span>
                          </div>
                       </div>
                    </div>
                  </div>
                </div>
                <div style="padding: 20px 30px;">
                  <img id="loading-gif" class="img-responsive" src="{{asset('/vendor/backpack/crud/img/ajax-loader.gif')}}" alt="Loading..." style="margin: auto; display: block;">
                  @if($student_list)
                    @if(count($student_list)>0)
                      <div id="students-container" style="display: none;"></div>
                    @else
                      <div class="box" style="border-radius: 10px;">
                        <div class="box-body" style="padding: 10px; padding-bottom: 0; margin-bottom: 0;">
                          <h4 class="text-center" style="padding: 0px !important; margin: 0px !important">
                            No Student Enrolled In This Class
                          </h4>
                        </div>
                      </div>
                    @endif
                  @else
                    <div class="box" style="border-radius: 10px;">
                      <div class="box-body" style="padding: 10px; padding-bottom: 0; margin-bottom: 0;">
                        <h4 class="text-center" style="padding: 0px !important; margin: 0px !important">
                          No Student Enrolled In This Class
                        </h4>
                      </div>
                    </div>
                  @endif
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- END STUDENT LIST -->
    </div>

  </div>
@endsection

@section('after_scripts')
  <script type="text/javascript" src="{{ asset('vendor/adminlte/bower_components/moment/moment.js') }}"></script>
  <script src="{{ asset('vendor/adminlte/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
  <script src="{{ asset('vendor/adminlte/bower_components/bootstrap-daterangepicker/daterangepicker.js') }}"></script>


  <script>
    document.getElementById("nav-classes").classList.add("active");
  </script>

  <script type="text/javascript">

    // $('#daterange').daterangepicker();
    // $('#daterange').daterangepicker("startDate", '{{Carbon\Carbon::now()}}');
    // $('#daterange').daterangepicker("endDate", '{{Carbon\Carbon::now()}}');
    
    $('#daterange').daterangepicker({
      ranges: {
        'Today': [moment(), moment()],
        'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
        'Last 7 Days': [moment().subtract(6, 'days'), moment()],
        'Last 30 Days': [moment().subtract(29, 'days'), moment()],
        'This Month': [moment().startOf('month'), moment().endOf('month')],
        'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
      } 
    });

    var startDate = null;
    var endDate   = null;

    startLoading();
    getClassAttendance();

    $('#daterange').on('apply.daterangepicker', function(ev, picker) {
      startDate = moment(picker.startDate).format('YYYY-MM-DD');
      endDate   = moment(picker.endDate).format('YYYY-MM-DD');
      startLoading();
      getClassAttendance();
    });

    // Get Class Attendance Function
    function getClassAttendance() {
      // Get Selected Date
      try {
        startDate = startDate ? startDate : moment().format('YYYY-MM-DD');
        endDate   = endDate   ? endDate   : moment().format('YYYY-MM-DD');
      } catch(err) {
        pNotifyError(err);
        stopLoading();
        startDate = null;
        endDate   = null;
        return false;
      }

      // Get Class Students With Attendance of Selected Date
      $.ajax({
        url: 'api/students-attendance-logs',
        data: {
          startDate: startDate,
          endDate: endDate
        },
        success: function (response) {
          if(response.error == true){
            new PNotify({
                title: response.title,
                text: response.message,
                type: 'error'
            });
          } else {
            if(response.data != null) {
              if(response.data.length > 0) {

                var tableRow = '';
                var tables = '';

                $.each(response.data, function (key, student) {
                  var no = key+1;
                  var time_in = '-';
                  var time_out = '-';

                  var thead = '<thead class="thead-dark">\
                              <tr>\
                                <th colspan="2" style="width: 50%;">' + student.studentnumber +'</th>\
                                <th colspan="2" style="width: 50%;">' + student.fullname +'</th>\
                              </tr>\
                            </thead>';
                  var tbody = '';
                  var trow  = ''

                  // Get Student Attendance
                  $.each(student.filteredAttendance, function (index, attendance) {
                    time_in   = attendance.time_in ?  convertTime(attendance.time_in) : '-';
                    time_out  = attendance.time_out ?  convertTime(attendance.time_out) : '-';

                    trow += '<tr>\
                        <td scope="row" colspan="2">' + moment(attendance.created_at).format('MMMM DD, YYYY') + '</td>\
                        <td scope="row" colspan="1">' + time_in + '</td>\
                        <td scope="row" colspan="1">' + time_out + '</td>\
                      </tr>';

                  });

                  tbody = trow ? '<tr>\
                                    <th scope="col" colspan="2"> Date </th>\
                                    <th scope="col" colspan="1"> Time In </th>\
                                    <th scope="col" colspan="1"> Time Out </th>\
                                  </tr>' + trow: 
                                  '<tr><td colspan="5" class="text-center">No Time In / Time Out.</td></tr>';

                  tables += '<table class="table table-striped table-bordered" id="' + student.studentnumber +'">' +
                              thead +
                              '<tbody>' +
                                tbody +
                              '</tbody>'+
                            '</table>';
                });
                $('#students-container').html(tables);
              } else {
                $('#students-container').html('<tr><td colspan="5" class="text-center">No student found.</td></tr>');
              }
            } else {
              $('#students-container').html('<tr><td colspan="5" class="text-center">No student found.</td></tr>');
            }
          }
          stopLoading();
          startDate = null;
          endDate   = null;
        },
        error: function() {
          pNotifyError();
          stopLoading();
          startDate = null;
          endDate   = null;
        }
      });
    }

    // Convert 24hr Format to 12hr format
    function convertTime (timeString) {
      // Check correct time format and split into components
      var timeString = timeString.toString ().match (/^([01]\d|2[0-3])(:)([0-5]\d)(:[0-5]\d)?$/) || [timeString];

      if (timeString.length > 1) { // If time format correct
        timeString = timeString.slice (1);  // Remove full string match value
        timeString[5] = +timeString[0] < 12 ? ' AM' : ' PM'; // Set AM/PM
        timeString[0] = +timeString[0] % 12 || 12; // Adjust hours
      }
      return timeString.join (''); // return adjusted time or original string
    }

    // Show Loading and Hide Student Table
    function startLoading() {
      $('#students-container').css("display", "none");
      $('#loading-gif').css("display", "block");
    }

    // Hide Loading and Show Student Table
    function stopLoading() {
      $('#loading-gif').css("display", "none");
      $('#students-container').css("display", "block");
    }

    // PNotify Error
    function pNotifyError(error = null) {
      $('#students-container').html('<tr><td colspan="5" class="text-center">Something Went Wrong, Please Try To Reload The Page.</td></tr>');
      new PNotify({
        title: "Error",
        text: error ? error : 'Something Went Wrong, Please Try Again.',
        type: 'warning'
      });
    }

  </script>
@endsection