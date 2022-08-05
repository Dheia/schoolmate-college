<!DOCTYPE html>	
<head>
	<title>{{Config::get('settings.schoolname')}} | Student List</title>
	<meta charset="utf-8" />
    <meta name="viewport" content="width=device-width">


	<style>
		@include('bootstrap4')
		table td, table th {
			/*border: 0 !important;*/
			/*padding: 3px !important;*/
			padding: 3px !important;
		}
		body {
			font-size: 9px;
			margin-bottom: 50px !important;
			@if($department->with_track == '1' && $track == null)
			margin-top: 255px !important;
			@else
			margin-top: 230px !important;
			@endif
		}
		header {
			position: fixed;
			top: 0px;
			height: 100px;
		}
		footer { 
			position: fixed;
			bottom: 0px; 
			height: 50px;
			font-size: 10px;

		}
		.thead-1 {
			background-color: #eee;
		}
	</style>


</head>
<body>
	<header>
		<center>	
			<img width="50" src="{{ Config::get('settings.schoollogo') }}" alt="IMG" align="center" style="">
			<p class="text-uppercase mb-0" style="font-size: 12px;"><b>{{ Config::get('settings.schoolname') }}</b></p>
			<p style="font-size: 10px;"><small>{{ Config::get('settings.schooladdress') }}</small></p>
		</center>
		<center class="text-uppercase">
			<b>STUDENT LIST</b>
			<br>
			<p style="font-style: italic;">as of {{ Carbon\Carbon::today()->format('M. d, Y') }}</p>
		</center>
		<div class="d-flex bd-highlight" style="margin-bottom: 10px !important;">
			<div class="col-md-6 bd-highlight text-left mr-auto" style="padding-right: 25% !important;">
				<p class="mr-auto" style="font-size: 11px !important;">
					School Year <span style="padding-left: 5% !important;">: <b style="margin-left: 5% !important;">{{ $schoolYear ?? '-' }}</b></span>
					<br>
					Department <span style="padding-left: 5% !important;">: <b>{{ $department->name ?? '-' }}</b></span>
					<br>
					Level <span style="padding-left: 5% !important;">: <b>{{ $level ?? '*' }}</b></span>
					<br>
					@if($department->with_track)
					Track <span style="padding-left: 5% !important;">: <b>{{ $track ?? '*' }}</b></span>
					<br>
					@endif
					@if($department->department_term_type == 'Semester')
					Term <span style="padding-left: 5% !important;">: <b>{{ $term ?? '*' }}</b></span>
					<br>
					@endif
				</p>
			</div>
			@php
				$num = 2;
			@endphp
			<div class="col-md-6 bd-highlight ml-auto" style="padding-top: 0 !important; margin-top: 0 !important; float-right; 
				padding-left: 50% !important; ">
				<div class="border border-secondary">
					<table class="table table-borderless" style="margin: 10px !important; font-size: 9px;">
						<thead>
						    <tr style="padding: 0px !important; margin: 0px !important;">
						      <th scope="col" style="padding: 0px !important; margin: 0px !important;">Statistics</th>
						    </tr>
						</thead>
						<tbody style="padding: 0px !important; margin: 0px !important;">
							@if($department->with_track == '1' && $track == null)
							    @foreach($total_students_tracks as $key => $value)
								    @php
									    if(($num % 2) == 0){
											echo '<tr style="padding: 0px !important; margin: 0px !important;">';
										}
								    @endphp
							        <td scope="row" style="padding: 0px !important; margin: 0px !important;">{{ $key }}:</td>
							        <th scope="row" style="padding: 0px !important; margin: 0px !important;">: {{ $value }}</th>
								    @php
								    	if(($num % 2) != 0){
								    	 	echo '</tr>';
								    	}
								    	$num++;
								    @endphp
							    @endforeach
						    @endif
						    <tr style="padding: 0px !important; margin: 0px !important;">
						    	<td style="padding: 0px !important; margin: 0px !important;" scope="row">Male</td>
						    	<th style="padding: 0px !important; margin: 0px !important;" scope="row">: {{ $total_male }}</th>
						    	<td style="padding: 0px !important; margin: 0px !important;" scope="row">Applicant</td>
						    	<th style="padding: 0px !important; margin: 0px !important;" scope="row">: {{ $total_applicant }}</th>
						    </tr>
						    <tr style="padding: 0px !important; margin: 0px !important;">
						    	<td style="padding: 0px !important; margin: 0px !important;" scope="row">Female</td>
						    	<th style="padding: 0px !important; margin: 0px !important;" scope="row">: {{ $total_female }}</th>
						    	<td style="padding: 0px !important; margin: 0px !important;" scope="row">Enrolled</td>
						    	<th style="padding: 0px !important; margin: 0px !important;" scope="row">: {{ $total_enrolled }}</th>
						    </tr>
						    <tr style="padding: 0px !important; margin: 0px !important;">
						    	<td style="padding: 0px !important; margin: 0px !important;"></td>
						    	<td style="padding: 0px !important; margin: 0px !important;"></td>
						    	<th style="padding: 0px !important; margin: 0px !important;">Total</th>
						    	<th style="padding: 0px !important; margin: 0px !important;" scope="row">: {{ $total_male + $total_female }}</th>
						    </tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</header>
	<footer>
		<center>
			<img width="40" src="images/schoolmate_logo.jpg" alt="schoolmate_logo">
		</center>
		<center>
			<p class="mb-0">Copyright &copy; 2019</p>
			<p class="pt-0">Powered by: Tigernet Hosting and IT Services</p>
		</center>
	</footer>	
	<main>
		<div class="col-md-12">
		    <div class="row display-flex-wrap">
				<div class="box attendance-table-logs col-md-10 padding-10 p-t-20 p-b-20">
						<table class="table table-bordered table-sm">
							<thead>
								<tr>
									<th>No.</th>
									<th>LRN</th>
									<th>Student No.</th>
									<th>Last Name</th>
									<th>First Name</th>
									<th>Middle Name</th>
									@if($level??'')
						        	@else
						        		<th>Level</th>
						        	@endif
						        	@if($department->with_track == '1')
							        	@if($track == null)
							        		<th>Track</th>
							        	@endif
						        	@endif
						        	<th>Gender</th>
						        	<th>Status</th>
								</tr>
							</thead>

							<tbody>
								@foreach($students as $key => $student)
									<tr>
										<td>{{ $key + 1 }}</td>
										<td>{{ $student->lrn }}</td>
										<td>{{ $student->studentnumber }}</td>
										<td>{{ $student->lastname }}</td>
										<td>{{ $student->firstname }}</td>
										<td>{{ $student->middlename }}</td>
										@if($level??'')
							        	@else
							        		<td>{{ $student->level->year ?? '-' }}</td>
							        	@endif
							        	@if($department->with_track == '1')
							        		@if($track == null)
											<td>{{ $student->track_name ?? '-' }}</td>
											@endif
							        	@endif
							        	<td>{{ $student->gender }}</td>
							        	<td>{{ $student->is_enrolled }}</td>
									</tr>
								@endforeach
							</tbody>
						</table>
				</div><!-- /.box -->
		  	</div>
		</div>

	</main>
	
</body>
</html>
