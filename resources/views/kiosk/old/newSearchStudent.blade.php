@extends('kiosk.new_layout')

@section('after_styles')
	<style>
		.login100-form-title {
			color: #000;
		}
		.form-group label {
			/*color: #ebe965;*/
			color: #000;
		}
		.btn-xs {
		    padding: 1px 5px;
		    font-size: 12px;
		    line-height: 1.5;
	        border-radius: 3px;
		}
		@media (max-width: 767px) {

		    .col-search {
		      padding-left: 0px !important;
		      padding-right: 0px !important;
		    }
		}
	</style>
@endsection

@section('content')
	<div class="container">
		<div class="row p-t-50" style="align-items: center;">
			<div class="col-md-12 col-lg-12 p-l-50 p-r-50 col-search">
				<div class="school_info col-md-12 col-lg-12 text-center  p-l-10 p-r-10 pull-right">
		            <img height="150" id="schoolLogo" src="{{ (asset(file_exists(Config::get('settings.schoollogo')) ? Config::get('settings.schoollogo') : 'images/no-logo.png')) }}" alt="School Logo" style="display: block; margin: auto;">

		            {{-- <div class="col-lg-12">
		             	<h2 class="text-center;" id="schoolName">{{ config('settings.schoolname') }}</h2>
		             	<p class="text-center;" id="schoolAddress">{{ config('settings.schooladdress') }}</p>
		            </div> --}}
		            <div class="col-lg-12">
		             	<br>
		             	<br>
		            </div>
		        </div>
				<div class="col-md-12 col-lg-12">
					<form class="login100-form validate-form" role="form" method="POST" action="{{ url()->current() }}" style="width: 100%;">
						<span class="login100-form-title" style="padding-bottom: 5px;">
						Search Student
						</span>
						<p class="login100-form-title" style="font-size: 16px; font-family: inherit;">
							S.Y. {{ $enrollmentStatusItem->enrollment_status->school_year_name }} 
							- 
							{{ $enrollmentStatusItem->term . ' Term'}}
						</p>
						@csrf            
						
						<div class="row">
							<div class="form-group col-md-12">
								{{-- <label for="studentnumber">Search Student</label> --}}
								<input id="studentnumber" type="text" value="{{  $oldVal ?? null }}" name="studentnumber" class="form-control" placeholder="Enter Student Number (ex. 12345)" required>
							</div>


						</div>

						<div class="container-login100-form-btn" style="padding-top: 0;">
								
							<div class="col-lg-5 p-t-10" style="padding: 0;">
								<button class="login100-form-btn">Find</button>
							</div>
							<div class="col-lg-2 p-t-10"></div>
							<div class="col-lg-5 p-t-10" style="padding: 0;">
								<a href="/kiosk/enlisting" class="login100-form-btn btn-primary" style="background-color: #007bff;">Go Back</a>
							</div>
						</div>
						
						
						<br><br>	
					</form>


						@if(request()->getMethod() === 'POST')
							<table class="table table-striped table-responsive" style="background-color: #FFF">
								<thead>
									<th style="vertical-align: middle;"><small><b>Student No.</b></small></th>
									<th style="vertical-align: middle;"><small><b>Full Name</b></small></th>
									<th style="vertical-align: middle;"><small><b>Previous Enrolled In</b></small></th>
									<th style="vertical-align: middle;"><small><b>Next Eligible Enrollment</b></small></th>
									@if($departmentTracks)
										@if(count($departmentTracks)>0)
											<th style="vertical-align: middle;"><small><b>Track</b></small></th>
										@endif
									@endif
									<th style="vertical-align: middle;"><small><b>Action</b></small></th>
								</thead>
								<tbody>
									{{-- {{ dd($enrollment) }} --}}
									@if($enrollment)
											<tr>
												<td style="vertical-align: middle;"><small>{{ config('settings.schoolabbr') }} - {{ $student->studentnumber }}</small></td>
												<td style="vertical-align: middle;"><small>{{ $student->fullname }}</small></td>
												<td style="vertical-align: middle;">
													<small>
														{{ $enrollment->level->year }} <br> 
														{{ $enrollment->schoolYear->schoolYear }} <br>
														{{ $enrollment->term_type }}
													</small>
												</td>
												<td style="vertical-align: middle;">
													@if($nextEnrollment)
														@if($nextEnrollment['allow'])
															<small>
																{{ !$nextEnrollment ? 'No Eligible Enrollment' : '' }}
																{{ $nextEnrollment['level']['year'] }} <br>
																{{ $nextEnrollment['schoolYear']['schoolYear'] }} <br>
																{{ $nextEnrollment['term_type'] }}											
															</small>
														@else
															<small>
																No Eligible Enrollment
															</small>
														@endif
													@else
														<small>
															{{ !$nextEnrollment ? 'No Eligible Enrollment' : '' }}
														</small>
													@endif
												</td>
												@if($departmentTracks)
													@if(count($departmentTracks)>0)
														<td style="vertical-align: middle;">
															<small>
																<select required id="departmentTrack" class="form-control form-control-sm">
																	<option disabled selected>Please Select Track</option>
																	@foreach($departmentTracks as $departmentTrack)
																		<option value="{{$departmentTrack->id}}">{{ $departmentTrack->code }}</option>
																	@endforeach
																</select>
															</small>
														</td>
													@endif
												@endif
												{{-- <td style="vertical-align: middle;"><small>{{ $student->is_enrolled }}</small></td> --}}
												<td style="vertical-align: middle;">
													@if($nextEnrollment)
														@if(!$nextEnrollment['allow'])
																
																<a href="javascript:void(0)" class="btn btn-xs btn-success disabled" disabled>Unavailable</a>

														@else
															<form action="{{ url('kiosk/enlisting/old/' . $enrollmentStatusItem->id . '/' .$student->studentnumber) }}" method="POST">
																@csrf()
																<input type="hidden" name="track" id="track">
																<button class="btn btn-xs btn-primary">Select</button>
															</form>
														@endif
													@endif
												</td>
											</tr>
									@else
										<td colspan="6"><h3 class="text-center">No Previous Enrollment Found</h3></td>
									@endif
								</tbody>
							</table>
						@endif
				</div>
			</div>
		</div>
	</div>

@endsection


@section('after_scripts')
	<script type="text/javascript">
		if( !/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
			$('table').removeClass('table-responsive')
		}
		$("#departmentTrack").change(function() {
			var selectedTrack = $('#departmentTrack').find(":selected").val();
			$("#track").val(selectedTrack);
		});
	</script>

	@if(isset($track_error))
		<script>
			alert('{{$track_error}}');
		</script>
	@endif
@endsection