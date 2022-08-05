@extends('backpack::layout')

@section('header')
@endsection

@section('content')

	<!-- HEADER START -->
  	<div class="row" style="padding: 15px;">
    	<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 smo-search-group"> 
			<section class="content-header">
			  	<ol class="breadcrumb">
				    <li><a href="{{ url(config('backpack.base.route_prefix'), 'dashboard') }}">{{ trans('backpack::crud.admin') }}</a></li>
				    <li class="active"><a href="javascript:void(0)" class="text-capitalize">{{ $crud->entity_name_plural }}</a></li>
			  	</ol>
			</section>
			<h1 class="smo-content-title">
		        <span class="text-capitalize">{!! $crud->getHeading() ?? $crud->entity_name_plural !!}</span>
		    </h1>		
  		</div>
	</div>
	<!-- HEADER END -->

@if ($crud->hasAccess('list'))
	{{-- <a href="{{ url($crud->route) }}" class="hidden-print"><i class="fa fa-angle-double-left"></i> {{ trans('backpack::crud.back_to_all') }} <span>{{ $crud->entity_name_plural }}</span></a> --}}
@endif

<div class="row m-t-20">
	<div class="{{ $crud->getCreateContentClass() }}">
		<!-- Default box -->

		@include('crud::inc.grouped_errors')

		  <div class="col-md-12">

		    <div class="row display-flex-wrap">
		 
		      	<input type="hidden" name="http_referrer" value={{ old('http_referrer') ?? \URL::previous() ?? url($crud->route) }}>

				@if ($crud->model->translationEnabled())
				<input type="hidden" name="locale" value={{ $crud->request->input('locale')?$crud->request->input('locale'):App::getLocale() }}>
				@endif

				{{-- See if we're using tabs --}}
				@if ($crud->tabsEnabled() && count($crud->getTabs()))
				    @include('crud::inc.show_tabbed_fields')
				    <input type="hidden" name="current_tab" value="{{ str_slug($crud->getTabs()[0], "") }}" />
				@else
				    <div class="box col-md-12 padding-10 p-t-20">
				    @include('crud::inc.show_fields', ['fields' => $crud->getFields('create')])
				    </div>
				@endif
			
				<img id="loading-gif" class="img-responsive" src="{{asset('/vendor/backpack/crud/img/ajax-loader.gif')}}" alt="Loading..." style="margin: auto; display: none;">

				<div class="box attendance-table-logs col-md-10 padding-10 p-t-20" style="display: none;">
					<div class="col-md-12 col-xs-12">
						<h5><b>Date Period:</b> <span id="date-period-text"></span></h5>
						<div class="table-responsive">
							<table id="attendance-table" class="table table-striped table-bordered">
						      	<thead>
						      		<th>DATE</th>
						      		<th>Level & Section</th>
						      		<th>Subject</th>
						      		<th>TIMEIN</th>
						      		<th>TIMEOUT</th>
						      	</thead>
						      	<tbody>
						      	</tbody>
					        </table>
						</div>
					</div>
				</div>

				<div class="clearfix"></div>
		    </div><!-- /.box-body -->
		    <div class="">
	
                {{-- @include('crud::inc.form_save_buttons') --}}

		    </div><!-- /.box-footer-->

		  </div><!-- /.box -->
		  {{-- </form> --}}

	</div>
</div>

@endsection

@section('after_styles')
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

@section('after_scripts')

	<script type="text/javascript" src="{{ asset('js/moment.min.js') }}"></script>

	<script>
		$(".applyBtn").click();
		
		var response_data 	= null;
		var date_from 		= moment().format('YYYY-MM-DD');
		var date_to			= moment().format('YYYY-MM-DD');
		var id 				= null;

		$('#btn-run').click(function () {
			id 		  		= emp_id;
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
			if(emp_id == '') {
				alert("Please Select Employee");
				return;
			}
			var url 	=	'{{url("admin/online-class/attendance/employee")}}' + '/' +  emp_id + '/download';
			var form 	= 	'<form id="download-form" method="POST" action="'+ url +'">' +
								'@csrf' +
								'<input type="hidden" name="employee_id" value="'+  emp_id + '">' +
								'<input type="hidden" name="date_from" value="'+  date_from + '">' +
								'<input type="hidden" name="date_to" value="'+  date_to + '">' +
							'</form>';
			$('body').append(form);
			// console.log(form);
			$('#download-form').submit();
		});
	</script>

	<script>

		$('#daterange').on('apply.daterangepicker', function(ev, picker) {
			// alert($('#daterange').daterangepicker().startDate);
			id 		  = emp_id;
			date_from = moment(picker.startDate).format('YYYY-MM-DD');
			date_to   = moment(picker.endDate).format('YYYY-MM-DD');

		});

		function runReport () {
			$(".applyBtn").click();
			id 		  		= emp_id;
			if(id == "") {
				alert("Please Select Employee");
				return;
			}

			startLoading();

			date_period = moment(date_from).format('MMMM DD, YYYY') + ' - ' + moment(date_to).format('MMMM DD, YYYY');
			$('#date-period-text').text(date_period);

			$.ajax({
				url: '/{{ $crud->route }}' + '/api/employee/' + id + '/attendance-logs',
				data: {
		          	date_from: date_from,
		          	date_to: date_to
		        },
				success: function (response) {
					if(response.error == true) {
						pNotifyError(response.message);
						stopLoading();
						$('.attendance-table-logs').css("display", "none");
					} else {
						var tableRow = '';
						var no_data = '<tr><td colspan="5" class="text-center">No Time In / Time Out</td></tr>';
						if(response.data != null) {

							response_data = response.data;

              				$.each(response.data, function (key, val) {
								if(val.online_class) {
									time_in   = val.time_in ?  convertTime(val.time_in) : '-';
                    				time_out  = val.time_out ?  convertTime(val.time_out) : '-';

									tableRow += '<tr>\
													<td>' + moment(val.created_at).format('MMMM DD, YYYY') + '</td>\
													<td>' + val.online_class.section_level_name + '</td>\
													<td>' + val.online_class.subject_name  + '</td>\
													<td>' + time_in + '</td>\
													<td>' + time_out + '</td>\
												</tr>'
								}
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
	      	$('.attendance-table-logs').css("display", "none");
	      	$('#loading-gif').css("display", "block");
	    }

	    // Hide Loading and Show Student Table
	    function stopLoading() {
      		$('#loading-gif').css("display", "none");
	      	$('.attendance-table-logs').css("display", "block");
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