<!DOCTYPE html>	
<head>
	<title>{{Config::get('settings.schoolname')}} | Curriculum-Subjects</title>
	<meta charset="utf-8" />
    <meta name="viewport" content="width=device-width">


	<style>
		@include('bootstrap4')
		table td, table th {
			/*border: 0 !important;*/
			padding: 3px !important;
		}
		body {
			font-size: 10px;
			margin-bottom: 50px !important;
			margin-top: 145px !important;
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
			<p><small>{{ Config::get('settings.schooladdress') }}</small></p>
		</center>
		<center class="text-uppercase"><b>Curriculum</b></center>
		<div class="col-md-12 pt-2">
			<p>
				Curriculum Name <span>: <b>{{$curriculum_subjects->curriculum_name}}</b></span>
			</p>
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
					@foreach($curriculum_subjects['subjectMappings'] as $subject_mapping)
						<table class="table table-bordered">
							<thead>
								<tr class="thead-1">
									<th class="text-center" colspan="6" style="vertical-align: middle;">{{$subject_mapping->level->year}}</th>
								</tr>
								@if($subject_mapping->term_type != 'Full')
								<tr>
									<th class="text-center" colspan="6" style="vertical-align: middle;">
										{{ $subject_mapping->term_type.' Term' }}
									</th>
								</tr>
								@endif
								<tr>
									<th class="text-center" rowspan="2" style="vertical-align: middle;">Code</th>
									<th class="text-center" rowspan="2" style="vertical-align: middle;">Subject Title</th>
									<th class="text-center" colspan="2" style="vertical-align: middle;">Number Of Hours</th>
									<th class="text-center" rowspan="2" style="vertical-align: middle;">No. Of Units</th>
									<th class="text-center" rowspan="2" style="vertical-align: middle;">Prerequisites</th>
								</tr>
								<tr>
									<th class="text-center" style="vertical-align: middle;">Lecture</th>
									<th class="text-center" style="vertical-align: middle;">Lab</th>
								</tr>
							</thead>

							<tbody>
								@php
									$total_units = 0;
									$total_lec_hrs = 0;
									$total_lab_hrs = 0;
								@endphp
								@if($subject_mapping['subjects'] ?? '')
									@foreach($subject_mapping['subjects'] as $subject)
										@if( \App\Models\SubjectManagement::find($subject->subject_code) ?? '')
											<tr>
												<td class="text-center">
													{{ \App\Models\SubjectManagement::find($subject->subject_code)->subject_code }}
												</td>
												<td class="text-center">
													{{ \App\Models\SubjectManagement::find($subject->subject_code)->subject_title }}
												</td>
												<td class="text-center">
													@if(isset($subject->lec_min))
														{{$subject->lec_min/60}}
													@else
														-
													@endif
												</td>
												<td class="text-center">
													@if(isset($subject->lab_min))
														{{$subject->lab_min/60}}
													@else
														-
													@endif
												</td>
												<td class="text-center">
													{{ number_format( (float) \App\Models\SubjectManagement::find($subject->subject_code)->no_unit, 1, '.', '') }}
												</td>
												<td class="text-center">
													@if(isset($subject->pre_requisite))
														@if($subject->pre_requisite)
															{{\App\Models\SubjectManagement::find($subject->pre_requisite)->subject_title}}
														@else
															-
														@endif
													@else
														-
													@endif
												</td>
											</tr>
											@php
												$total_units += \App\Models\SubjectManagement::find($subject->subject_code)->no_unit;
											@endphp
										@endif
										@php 
											

											if(isset($subject->lab_min)){
												$total_lec_hrs += $subject->lec_min/60;
											}
											else{
												$total_lec_hrs += 0;
											}
											if(isset($subject->lab_min)){
												$total_lab_hrs += $subject->lab_min/60;
											}
											else{
												$total_lab_hrs += 0;
											}
										@endphp
									@endforeach
								@else

								@endif
									<tr>
										<td></td>
										<td class="text-right"><b>Total</b></td>
										<td class="text-center">{{$total_lec_hrs}}</td>
										<td class="text-center">{{$total_lab_hrs}}</td>
										<td class="text-center">{{  number_format((float) "$total_units", 1) }}</td>
										<td></td>
									</tr>
							</tbody>
						</table>
					@endforeach
				</div><!-- /.box -->

		  	</div>
		</div>

	</main>
	
</body>
</html>
