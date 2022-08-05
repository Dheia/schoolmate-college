@extends('backpack::layout')

@section('header')
	{{-- <section class="content-header">
	  <h1>
        <span class="text-capitalize">{!! $crud->getHeading() ?? $crud->entity_name_plural !!}</span>
	  </h1>
	  <ol class="breadcrumb">
	    <li><a href="{{ url(config('backpack.base.route_prefix'), 'dashboard') }}">{{ trans('backpack::crud.admin') }}</a></li>
	    <li class="active"><a href="javascript:void(0)" class="text-capitalize">{{ $crud->entity_name_plural }}</a></li>
	  </ol>
	</section> --}}
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
			
			<div class="box attendance-table-logs col-md-10 padding-10 p-t-20">
				
				<h5><b>Date Period:</b> <span id="date-period-text"></span></h5>
				
				<table id="attendance-table" class="table table-striped table-bordered">
			      	<thead>
			      		<th>DATE</th>
			      		<th>TIMEIN</th>
			      		<th>TIMEOUT</th>
			      		<th>REMARKS</th>
			      		<th>DURATION</th>
			      	</thead>
			      	<tbody></tbody>
		        </table>
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
	</style>
@endsection

@section('after_scripts')

	<script>
		$('#attendance_period').change(function () {
			var selected = $(this).find('option:selected').val();

			if(selected == 'custom') 			{
				$('.date_from, .date_to').css('display', 'block');
			} else {
				$('.date_from, .date_to').css('display', 'none');
			}
		})
	</script>

	<script>
		
		function busy () {
			$('#btn-run').text('...').attr('disabled', true);
			$('#btn-full-run-report').text('...').attr('disabled', true);
		}

		function done () {
			$('#btn-run').text('Run').removeAttr('disabled');
			$('#btn-full-run-report').text('Full Run Report').removeAttr('disabled');
		}

		$('#btn-run').click(function () {

			busy();

			var id 		  = $('#studentNumber').val();
			var date_from = $('#date_from').val();
			var date_to   = $('#date_to').val();
			var period    = $('#attendance_period option:selected').val();

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
				url: 'api/employee-attendance/' + id + '/attendance-logs?date_from=' + date_from + '&date_to=' + date_to + '&period=' + period,
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
					$.each(response['attendance_logs'], function (key, val) {
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

						tableRow += '<tr>\
										<td>' + val.date_format 		  + ' (' + val.week_day + ')</td>\
										<td>' + val.start_time_formatted  + '</td>\
										<td>' + val.end_time_formatted 	  + '</td>\
										<td>' + val.remarks 			  + '</td>\
										<td>' + duration			  	  + '</td>\
									</tr>'
					});

					$('#attendance-table tbody').html(tableRow);
					done();
				}
			})
		});
		
		$("#btn-download").click(function() {
			var id 		  = $('#studentNumber').val();
			var date_from = $('#date_from').val();
			var date_to   = $('#date_to').val();
			var period    = $('#attendance_period option:selected').val();
			if(id == '') {
				alert("Please Select Employee");
				return;
			}
			var url 	=	'{{url("admin/employee-attendance")}}' + '/' +  id + '/download?date_from=' + date_from+'&date_to=' + date_to + '&period=' + period+'';
			var form 	= 	'<form id="download-form" method="POST" action="'+ url +'">' +
								'@csrf' +
								'<input type="hidden" name="studentNumber" value="'+  id + '">' +
								'<input type="hidden" name="date_from" value="'+  date_from + '">' +
								'<input type="hidden" name="date_to" value="'+  date_to + '">' +
							'</form>';
			$('body').append(form);
			// console.log(form);
			$('#download-form').submit();
		});

		$('#btn-full-run-report').click(function (e) {
			e.preventDefault();
			busy();

			var id 		  = $('#studentNumber').val();
			var date_from = $('#date_from').val();
			var date_to   = $('#date_to').val();
			var period    = $('#attendance_period option:selected').val();

			if(period == 'custom') {
				if(date_from == "" || date_to == "") {
					alert("Please Enter A Date");
					done();
					return;
				}
			}

			window.open(
				'/admin/employee-attendance/full-run-report?date_from=' + date_from + '&date_to=' + date_to + '&period=' + period,
				'_blank'
			);
			done();
		});
	</script>
@endsection