@extends('backpack::layout')

@section('header')
	
@endsection


@section('content')
<div class="row">
	<div class="col-md-12">
		<!-- Default box -->


		  	<div class="box">
				
		  		<h3 class="text-center">{{ \Carbon\Carbon::parse($start_date)->format('M. d, Y') }} - {{ \Carbon\Carbon::parse($end_date)->format('M. d, Y') }}</h3>

		  		<div class="box-header" style="overflow: auto;">
		  			<table id="table_id" class="table table-striped table-bordered nowrap" style="width: 100%;">
		  				<thead>
		  					<th>Employee ID</th>
		  					<th>First Name</th>
		  					<th>Middle Name</th>
		  					<th>Last Name</th>

		  					@php
	  						    $period = Carbon\CarbonPeriod::create(Carbon\Carbon::parse($start_date), Carbon\Carbon::parse($end_date));
		  					@endphp

							@foreach ($period as $date)
							    <th> {{ $date->format('M. d (D)') }} </th>
							@endforeach

		  				</thead>
		  				<tbody>
							@foreach($employees as $employee)
								<tr id="employee-{{ $employee->employee_id }}">
									<td>{{ $employee->employee_id }}</td>
									<td>{{ $employee->firstname }}</td>
									<td>{{ $employee->middlename }}</td>
									<td>{{ $employee->lastname }}</td>
								    <td id="initial-loading" colspan="{{ count($period) }}" class="text-center"> <b>Loading...</b> </td>
								</tr>
							@endforeach
		  				</tbody>
		  			</table>
			  	</div>

		  	</div><!-- /.box -->
	</div>
</div>

@endsection


@section('after_styles')
	<link rel="stylesheet" href="//cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css">
	<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.6.0/css/buttons.dataTables.min.css">

	<style>
		.circle {
			height: 50px;
			width: 50px;
			background: #FFF;
			border-radius: 50%;
		}
		
		.present-without-late-and-undertime { background-color: green !important; }
		.absent { background-color: red !important;  }
		.late { background-color: yellow !important; }
		.undertime { background-color: orange !important; }
	</style>
@endsection

@section('after_scripts')
	<script src="https://code.jquery.com/jquery-3.3.1.js"></script>
	<script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
	<script src="https://cdn.datatables.net/buttons/1.6.0/js/dataTables.buttons.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
	{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script> --}}
	<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
	<script src="https://cdn.datatables.net/buttons/1.6.0/js/buttons.html5.min.js"></script>
	<script src="https://cdn.datatables.net/buttons/1.6.0/js/buttons.print.min.js"></script>
	<script>
		
	</script>

	<script>
		Array.prototype.remove = function() {
		    var what, a = arguments, L = a.length, ax;
		    while (L && this.length) {
		        what = a[--L];
		        while ((ax = this.indexOf(what)) !== -1) {
		            this.splice(ax, 1);
		        }
		    }
		    return this;
		};
		function initDataTables () {
			$(document).ready( function () {
			    $('#table_id').DataTable({
			    	paging: true,
			    	scrollY: 600,
			    	dom: 'Bfrtip',
			    	scrollX:        true,
			        scrollCollapse: true,
			        fixedColumns:   {
			            leftColumns: 2
			        },
			    	buttons: [
			            {
			            	extend: "excel",
			            	title: "Attendance Report {{ Carbon\Carbon::parse($start_date)->format('M. d, Y') }} - {{ Carbon\Carbon::parse($end_date)->format('M. d, Y') }}",
			            },
			            {
			            	extend: "csv",
			            	title: "Attendance Report  {{ Carbon\Carbon::parse($start_date)->format('M. d, Y') }} - {{ Carbon\Carbon::parse($end_date)->format('M. d, Y') }}",
			            }
			            // 'pdfHtml5'
			        ]
			    });
			} );
		}

	</script>

	<script>
		const first = 100;
		const second = 100;
		const employee_ids = {{ json_encode($employee_ids) }};
		const date_from = "{{ $start_date }}";
		const date_to 	= "{{ $end_date }}";
		const period 	= "{{ $period_type }}";
		if (window.Worker) {
			const myWorker = new Worker("/js/fullRunReportWebWorker.js");
			myWorker.postMessage([employee_ids, date_from, date_to, period]);
		
			myWorker.onmessage = function(e) {
				// Remove To Array Of EmployeeID
				employee_ids.remove(e.data.employee_id);
				tdTag = "";
				$.each(e.data.data.attendance_logs, function (key, val) {
					var hours = val.duration !== null ? val.duration.diffInHours : '-'; 
					tdTag += "<td style='padding:0;text-align:center;border: 0px solid #CCC;'>\
									<small>\
										<div class='col-md-6'>" + val.start_time_formatted  + "</div>\
										<div class='col-md-6'>" + val.end_time_formatted + "</div>\
									</small>\
								</td>";

							 // <td style='padding:0;text-align:center;border: 0px solid #CCC;'><small> " + val.end_time_formatted + "</small></td>";
					// if(val.duration !== null) {
						
					// 			// val.duration.diffInHours
					// } else {
					// 	tdTag += "<td></td>";
					// }
				});
				$('#employee-' + e.data.employee_id).find('#initial-loading').remove();
				$('#employee-' + e.data.employee_id).append(tdTag);
				if(employee_ids.length === 0) {
					initDataTables();
				}
			}
		} else {
			console.log('Your browser doesn\'t support web workers.')
		}
	</script>
@endsection