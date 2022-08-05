@extends('backpack::layout')

@section('header')
@endsection


@section('content')
    <!-- HEADER -->
    <div class="row" style="padding: 15px;">
      <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 smo-search-group"> 
        <section class="content-header">
          <ol class="breadcrumb">
            <li><a href="{{ url('student/dashboard') }}">Dashboard</a></li>
            <li><a class="text-capitalize active">System Attendance Report</a></li>
          </ol>
        </section>
        <h1 class="smo-content-title">
          <span class="text-capitalize">System Attendance Report</span>
          {{-- <small>All turnstile tap in and tap out</small> --}}
        </h1>
      </div>
    </div>
    <!-- END OF HEADER -->

    <div class="row">
      <form role="form" method="POST" action="{{ url('admin/system-attendance-report/download') }}" id="attendanceForm">
        {!! csrf_field() !!}

        <input type='hidden' class="form-control" name="date_from" id="date_from"/>
        <input type='hidden' class="form-control" name="date_to" id="date_to"/>

        <div class="col-md-12">
          {{-- <form action="" method="GET" class="mb-3"> --}}
            <div class="box col-md-12 padding-10 p-t-20">
              <div class="form-group col-xs-12">
                  <div class="row">
                    <div class="col-md-4">
                      <label for="">Date Period</label>                    
                      <div class='input-group date'>
                        <input type='text' class="form-control" name="daterange" id="daterange"/>
                        <span class="input-group-addon">
                        <span class="fa fa-calendar"></span>
                        </span>
                     </div>
                    </div>
                   
                    <div class="col-md-4">
                      <label for="">&nbsp;</label><br>
                      <a href="javascript:void(0)" id="btn-run" class="btn btn-success w-100"><i class="fa fa-eye" aria-hidden="true"></i> Run</a>
                    </div>
                    <div class="col-md-4">
                      <label for="">&nbsp;</label><br>
                      <button id="btn-download" type="submit" class="btn btn-default w-100" >
                       <i class="fa fa-download" aria-hidden="true"></i> Download
                    </button>
                    </div>
                 
                  
                  </div>
              </div>
            </div>
          
            
          {{-- </form> --}}
          
          <img id="loading-gif" class="img-responsive" src="{{asset('/vendor/backpack/crud/img/ajax-loader.gif')}}" alt="Loading..." style="display: none; margin: auto; padding-top: 20px; padding-bottom: 20px;">

          <div class="box col-md-12 padding-10 p-t-20" style="display: none;" id="attendance-table">
            <div class="form-group col-xs-12">
              <h5><b>Date Period:</b> <span id="date-period-text"></span></h5>
              <table id="attendance-table" class="table table-striped table-bordered">
                <thead>
                  <th>USER</th>
                  <th>USER TYPE</th>
                  <th>TIME IN</th>
                  <th>TIME OUT</th>
                  <th>DATE</th>
                </thead>
                <tbody></tbody>
              </table>
            </div>
          </div>
          
            
        </div>
      </form>
    </div>

  
@endsection

@section('after_scripts')
  <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
  <script type="text/javascript" src="{{ asset('js/moment.min.js') }}"></script>
  <script>
    
   $(".applyBtn").click();
		
		var response_data 	= null;
		var date_from 		= moment().format('YYYY-MM-DD');
		var date_to			= moment().format('YYYY-MM-DD');
		var id 				= null;

		$('#btn-run').click(function () {
	
			var selected 	= $(this).find('option:selected').val();

			if(selected == 'custom') 			{
				$('.date_from, .date_to').css('display', 'block');
			} else {
				$('.date_from, .date_to').css('display', 'none');
			}

			runReport();
		});

		$("#btn-download").click(function() {
			$(".applyBtn").click();
			
      $("#date_from").val(date_from);
      $("#date_to").val(date_to);
		
      
			// console.log(form);
			$('#download-form').submit();
		});
  </script>

  <script>

    $('#daterange').on('apply.daterangepicker', function(ev, picker) {
      // alert($('#daterange').daterangepicker().startDate);
      date_from = moment(picker.startDate).format('YYYY-MM-DD');
      date_to   = moment(picker.endDate).format('YYYY-MM-DD');
     
    });

    function runReport () {
        $(".applyBtn").click();
      

        startLoading();

        date_period = moment(date_from).format('MMMM DD, YYYY') + ' - ' + moment(date_to).format('MMMM DD, YYYY');
        $('#date-period-text').text(date_period);

          $.ajax({
            url: 'system-attendance-report/attendance-logs',
            data: {
                    date_from: date_from,
                    date_to: date_to
                },
            success: function (response) {
              console.log(response);
              if(response.error == true) {
                pNotifyError(response.message);
                stopLoading();
              } else {
                var tableRow = '';
                var no_data = '<tr><td colspan="5" class="text-center">No Time In / Time Out</td></tr>';
                if(response.data != null) {

                  response_data = response.data;
                  $.each(response.data, function (key, val) {
                    
                    //if null
                    var timein = val.time_in ? moment(val.time_in,'hh:mm A').format('hh:mm A') : '-';
                    var timeout = val.time_out ? moment(val.time_out,'hh:mm A').format('hh:mm A') : '-';

                      tableRow += '<tr>\
                              <td>' + val.user.full_name + '</td>\
                              <td> Employee</td>\
                              <td>' + timein + '</td>\
                              <td>' + timeout + '</td>\
                              <td>' +  moment(val.created_at).format('MMMM DD, YYYY') + '</td>\
                            </tr>'
                   
                  });
                }

                $('#attendance-table tbody').html(tableRow ? tableRow : no_data);
                stopLoading();
              }
            },
                error: function() {
                    pNotifyError();
                    stopLoading();
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
       
          $('#loading-gif').css("display", "block");
      }

      // Hide Loading and Show Student Table
      function stopLoading() {
          $('#loading-gif').css("display", "none");
          $('#attendance-table').css("display", "block");
      }

      // PNotify Error
      function pNotifyError(error = null) {
          $('#attendance-table tbody').html('<tr><td colspan="5" class="text-center">Something Went Wrong, Please Try To Reload The Page.</td></tr>');
          new PNotify({
            title: "Error",
            text: error ? error : 'Something Went Wrong, Please Try Again.',
            type: 'error'
          });
      }
  </script>

@endsection

@push('after_scripts')
    <script type="text/javascript" src="{{ asset('vendor/adminlte/bower_components/moment/moment.js') }}"></script>
    <script src="{{ asset('vendor/adminlte/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('vendor/adminlte/bower_components/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
    <script>
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
    </script>
    @endpush
@section('after_styles')

  <link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="{{ asset('vendor/adminlte/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker3.css') }}">
  <link rel="stylesheet" href="{{ asset('vendor/adminlte/bower_components/bootstrap-daterangepicker/daterangepicker.css') }}">

  <style>
		.date_from, .date_to {
			display: none;
		}

		@media only screen and (max-width: 600px) 
		{
		  	.btn-form-group {
		    	padding-top: 0 !important;
		    	margin-bottom: 0 !important;
		  	}

		  	#daterange-form-group {
		    	padding-bottom: 0 !important;
		    	margin-bottom: 0 !important;
		  	}
		}
	</style>
@endsection
