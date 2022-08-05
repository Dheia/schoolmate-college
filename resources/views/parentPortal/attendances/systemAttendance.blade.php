

@extends('backpack::layout_parent')

@section('header')
@endsection

@section('after_styles')
  	<link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css">
	  <link href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.min.css" rel="stylesheet"/>
	  <style>
		  .slick-slide img{
	margin: 0 auto;
	max-width:120px;
	border: 3px solid #fff;
	border-radius:50%;
	margin-bottom:25px;
}
.item{
	
	padding: 5px;
	margin-bottom: 15px;
	outline:none;
}
.item-inner{
	background:linear-gradient(45deg,#3C8DBC,#2FA9A9);
	border-radius: 18px;
	text-align: center;
	padding: 25px;
}
.item-inner h4{
	font-size: 30px;
	color: #fff;
	text-transform: capitalize;
}
.item-inner p{
	font-size:16px;
	color:#fff;
	letter-spacing: 1px;
}
.social{
	margin-top: 50px;
	
}
.social a{
	display: inline-block;
	width: 45px;
	height: 45px;
	line-height: 45px;
	background-color: #fff;
	color:#ff6c00;
	border-radius: 50%;
	text-align: center;
	font-size: 20px;
	margin: 0 5px;
	transition:all ease 0.5s;
}
.social a:hover{
	transform: translateY(-10px);
}
.slick-slide{
	transform: scale(0.8);
	opacity: 0.3;
	transition: all ease 0.5s;

}
.slick-slide.slick-current{
	transform: scale(1);
	opacity: 1;	
}
.slick-arrow{
	position: absolute;
	bottom:0;
	z-index:99;
	background: #3B91BA;
	border:none;
	padding: 8px 15px;
	border-radius: 50px;
	width: 100px;
	outline: #000000;
	color: #fff;
}


@media only screen and (min-width: 768px) {
        
        .content-wrapper{
      border-top-left-radius: 60px;
      }
      .sidebar-toggle{
        margin-left:40px;
      }
     
    }
    
   
    
    .main-footer{
      border-bottom-left-radius: 60px;
    
    }
	  </style>
@endsection

@section('content')
<body style="background: #3c8dbc;">
<div class="container">

    <!-- HEADER -->
    <div class="row" style="padding: 15px;">
      	<div class="col-lg-6 col-md-6 col-sm-12 col-md-12 smo-search-group"> 
	        <section class="content-header">
	          	<ol class="breadcrumb">
		            <li><a href="{{ url('student/dashboard') }}">Dashboard</a></li>
		            <li><a class="text-capitalize active">System Attendance</a></li>
	          	</ol>
	        </section>
        	<h1 class="smo-content-title">
          		<span class="text-capitalize">System Attendance</span>
          		<small>All system login and logout</small>
	        </h1>
      	</div>
    </div>
	<b style="font-size: 30px">Student</b>
    <!-- END OF HEADER -->

	<div class="slider multiple-items">
		@if( count($students) > 0 )
			@php $index = 0; @endphp
			@foreach($students as $student)
			@if($index % 1 == 0)
			
			@endif
				@php
				$avatar = $student ? $student->photo : 'images/headshot-default.png';
				@endphp
				<div class="item" onclick="clickFunction('{{$student->full_name}}','{{$student->studentnumber }}')" onmouseover="hoverFunction('{{$student->full_name}}','{{$student->studentnumber }}')">
					<div class="item-inner">
						<img class="profile-user-img img-responsive img-circle" src="{{ url($avatar) }}" alt="User profile picture" id="profileImg">
						<h4>{{  Str::limit($student->fullname,30) }}</h4>
						@if ($student->current_level == '-')
							<p>Level: -</p>
						@else
							<p>{{$student->current_level}}</p>
						@endif

						</div>
					</div>
		
		@php $index++; @endphp
			@if($index%1 == 0)
		@endif
		@endforeach
		@endif
	
	</div>

    <div class="row">

        <div class="col-md-12">

            <div class="box col-md-12 padding-10 p-t-20">
            	<!-- Student Select -->
            	<div class="form-group col-md-12">
                  	<div class="row">
	                    <div class="col-md-12">
							<label id="name"for="" style="font-size: 20px">Select a Student</label>   
							<input type="text" id="studentNumber" hidden>          
	                      		{{-- @if( count($students) > 0 )
	                      			@foreach($students as $student)
	                      				<option value="{{$student->studentnumber}}">{{ $student->full_name }} </option>
	                      			@endforeach
	                      		@endif
	                      	</select> --}}
	                    </div>
                  	</div>
              	</div>
              	<!-- Attendance Period -->
              	<div class="form-group col-md-12">
                  	<div class="row">
	                    <div class="col-md-4">
	                      	<label for="">Attendance Period</label>                    
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
            	<div class="form-group col-md-12">
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
	  <script src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
  	<script>
		  
		function clickFunction(value,studentid) {
		document.getElementById("name").innerHTML = value;
		document.getElementById("studentNumber").value = studentid;
		}
		function hoverFunction(value,studentid) {
		document.getElementById("name").innerHTML = value;
		document.getElementById("studentNumber").value = studentid;
		}

	    $(document).ready(function() {
	      	$('.start_date, .end_date').css('display', 'none');
	      	$('#loading-gif').css('display', 'none');
	      	$('#attendance').DataTable();

	      	$('#reportPeriod').on('change', function () {
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
	      	$('#attendance-box').css('display', 'none');
	      	$('#loading-gif').css('display', 'block');
    	}

    	function done () {
	      	$('#loading-gif').css('display', 'none');
	      	$('#attendance-box').css('display', 'block');
	      	$('#btn-run').text('Run').removeAttr('disabled');
    	}

    	$('#btn-run').click(function () {

	      	busy();

			var date_from 	= $('#date_from').val();
	      	var date_to   	= $('#date_to').val();
	      	var period    	= $('#reportPeriod option:selected').val();
        	var studentnumber 	= $('#studentNumber').val();


	      	if(studentnumber == "") {
		        alert("Please Select Student");
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
		        url:  'system-attendance/logs?studentnumber='+ studentnumber +'&date_from=' + date_from + '&date_to=' + date_to + '&period=' + period,
		        success: function (response) {
		        	if(response.status == "ERROR") {
		        		$('#loading-gif').css('display', 'none');
				      	$('#attendance-box').css('display', 'none');
				      	$('#btn-run').text('Run').removeAttr('disabled');
		        		new PNotify({
	                      	title: 'Error',
	                      	text: response.message ? response.message : 'Error, Something Went Wrong, Please Try To Reload The Page.',
	                      	type: "error"
	                  	});
		        		return;
		          	}

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
		$('.slider').slick({
			infinite: true,
			slidesToShow: 1,
			arrows:false,
			centerMode:false,
			responsive: [
			{
				breakpoint: 480,
				settings: {
				slidesToShow: 1,
				}
			}
			]
		});

		$('.slider').on('beforeChange', function() {
			var dataId = $('.slick-current').attr("data-slick-index");    
			console.log(dataId);
		});
  	</script>
@endsection
