

@extends('backpack::layout_student')

@section('header')
<style>
	@media only screen and (min-width: 768px) {
          /* For desktop phones: */
        .oc-header-title {
          margin-top: 80px;
        }
        .content-wrapper{
            border-top-left-radius: 50px;
            }
        .sidebar-toggle{
          margin-left:30px;
        }
        .main-footer{
        border-bottom-left-radius: 50px;
        padding-left: 80px;
      }
    }
</style>
@endsection

@section('content')
<body style="background: #3c8dbc;">
<div class="container">

    <!-- HEADER -->
    <div class="row" style="padding: 15px;">
      	<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 smo-search-group"> 
	        <section class="content-header">
	          	<ol class="breadcrumb">
		            <li><a href="{{ url('student/dashboards') }}">Dashboard</a></li>
		            <li><a class="text-capitalize active">System Attendance</a></li>
	          	</ol>
	        </section>
        	<h1 class="smo-content-title">
          		<span class="text-capitalize">System Attendance</span>
          		<small>All system login and logout</small>
	        </h1>
      	</div>
    </div>
    <!-- END OF HEADER -->

    <div class="row">

        <div class="col-md-10">

            <div class="box col-md-12 padding-10 p-t-20">
              	<div class="form-group col-md-12 ">
                  	<div class="row">
	                    <div class="col-md-4">
	                      	<label for="">Login Period</label>                    
	                      	<select name="report_name" id="reportPeriod" class="form-control">
		                        <option value="today"    >Today</option>
		                        <option value="this_week" >This Week</option>
		                        <option value="this_month">This Month</option>
		                        <option value="custom"   >Custom</option>
	                      	</select>
	                    </div>
	                    <div class="col-md-4 start_date" >
	                      	<label for="start_date">Start Date</label>
	                      	<input id="date_from" type="date" class="form-control" name="date_from" >
	                    </div>
                    	<div class="col-md-4 end_date">
	                      	<label for="end_date">End Date</label>
	                      	<input id="date_to" type="date" class="form-control" name="date_to">
                    	</div>
                    	<div class="col-md-4">
	                      	<label for="">&nbsp;</label><br>
	                      	<a href="javascript:void(0)" id="btn-run" class="btn btn-primary w-100">Filter</a>
                    	</div>
                  	</div>
              	</div>
            </div>
          
          	<img id="loading-gif" class="img-responsive" src="{{asset('/vendor/backpack/crud/img/ajax-loader.gif')}}" alt="Loading..." style="display: none; margin: auto; padding-top: 20px; padding-bottom: 20px;">

          	<div class="box col-md-12 padding-10 p-t-20" style="display: none;" id="attendance-box">
            	<div class="form-group col-md-12 ">
              		<h5><b>Date Period:</b> <span id="date-period-text"></span></h5>
              		<table id="attendance-table" class="table table-striped table-bordered">
		                <thead>
		                  	<th>DATE</th>
		                  	<th>LOGIN</th>
		                  	<th>LOGOUT</th>
		                  	<th>DURATION</th>
		                </thead>
	                	<tbody></tbody>
	              	</table>
            	</div>
          	</div>
          
        </div>

    </div>
    
</div>

</body>
@endsection


@section('after_scripts')
	<script type="text/javascript" src="{{ asset('vendor/adminlte/bower_components/moment/moment.js') }}"></script>
  	<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
  	<script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
  	<script>
	    $(document).ready(function() {
	      	$('.start_date, .end_date').css('display', 'none');
	      	$('#loading-gif').css('display', 'none');
	      	$('#attendance').DataTable();

	      	$('#reportPeriod').on('change', function () {
	          	// console.log(this.value);
	          	if(this.value == "custom") {
	            	$('.start_date, .end_date').css('display', 'block');
	          	} else {
	            	$('.start_date, .end_date').css('display', 'none');
	          	}
	        });
	      

	      	var today 	= new Date();
	      	var dd 		= today.getDate();
	      	var mm 		= today.getMonth() + 1; //January is 0!
	      	var yyyy 	= today.getFullYear();

	      	if (dd < 10) {
	        	dd = '0' + dd;
	      	}

	      	if (mm < 10) {
	        	mm = '0' + mm;
	      	}

	      	today = yyyy + "-" + mm;
	      	$('#dateselection').val(today)
	    });
     
  	</script>

  	<script>
    
    	function busy () {
	      	$('#btn-run').text('...').attr('disabled', true);
	      	$('#loading-gif').css('display', 'block');
    	}

    	function done () {
	      	$('#loading-gif').css('display', 'none');
	      	$('#attendance-box').css('display', 'block');
	      	$('#btn-run').text('Run').removeAttr('disabled');
    	}

    	$('#btn-run').click(function () {

	      	busy();

	      	var id      = $('#studentNumber').val();
	      	var date_from = $('#date_from').val();
	      	var date_to   = $('#date_to').val();
	      	var period    = $('#reportPeriod option:selected').val();
	      	// alert(date_from, date_to);
	      	if(id == "") {
		        alert("Please Select Employee");
		        done();
		        return;
	      	}

	      	if(period == 'custom') {
		        if(date_from == "" || date_to == "") {
		          	alert("Please Enter A Date");
		          	done();
		          	return;
		        }
	      	}


	      	$.ajax({
		        url:  'system-attendance/logs?date_from=' + date_from + '&date_to=' + date_to + '&period=' + period,
		        success: function (response) {

		          	date_period = '';

		          	if(response.date_from == response.date_to && response.date_period !== 'today') {
		            	date_period = response.date_to;
		          	} 
		          	else if (response.date_period == 'today') {
		            	date_period = response.date_to;
		         	} 
		          	else {
		            	date_period = response.date_from + ' - ' + response.date_to;
		          	}

		          	$('#date-period-text').text(date_period);


		          	var tableRow = '';
		          	console.log(response);
		          	$.each(response.systemAttendance, function (key, val) {
			            var duration = '';

			            if(val.duration !== null)
			            {
			              	if(val.duration.diff.h > 0)
			              	{
		                		duration += val.duration.diff.h + ' hours, ';
		              		}
			              	if(val.duration.diff.i > 0)
			              	{
			                	duration += val.duration.diff.i + ' minutes, ';
			              	}
			              	if(val.duration.diff.s > 0)
			              	{
			                	duration += val.duration.diff.s + ' seconds ';
			              	}
	            		}

		            	var login_at = val.login_at ? moment(val.login_at, 'HH:mm:ss').format('hh:mm:ss A') : '-';
	            		var logout_at = val.logout_at ? moment(val.logout_at, 'HH:mm:ss').format('hh:mm:ss A') : '-';
		            	tableRow += '<tr>\
				                    	<td>' + moment(val.created_at).format('MMMM DD, YYYY') + ' (' + val.week_day + ')</td>\
				                    	<td>' + login_at  + '</td>\
				                    	<td>' + logout_at + '</td>\
				                    	<td>' + duration    + '</td>\
				                  	</tr>'
	          		});

	          		$('#attendance-table tbody').html(tableRow);
	          		done();
	        	}
	      	})
    	});
  	</script>
@endsection

@section('after_styles')
  	<link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css">
@endsection
