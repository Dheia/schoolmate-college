@extends('kiosk.layout')

@section('after_styles')
	<style>
		.form-group label {
			color: #ebe965;
		}
		.btn-xs {
		    padding: 1px 5px;
		    font-size: 12px;
		    line-height: 1.5;
	        border-radius: 3px;
		}
	</style>
@endsection

@section('content')

	<form class="login100-form validate-form" role="form" method="POST" action="{{ url()->current() }}" style="width: 100%;">
		<span class="login100-form-title" style="padding-bottom: 5px;">
		Search Student
		</span>
		<p class="login100-form-title" style="font-size: 16px; color: #FFF; font-family: inherit;">S.Y. {{ $schoolYearActive->schoolYear }}</p>

		@csrf            
		
		<div class="row">
			<div class="form-group col-md-12">
				{{-- <label for="studentnumber">Search Student</label> --}}
				<input id="studentnumber" type="text" value="{{  $oldVal ?? null }}" name="studentnumber" class="form-control" placeholder="Enter Student Number (ex. 12345)" required>
			</div>


		</div>

		<div class="container-login100-form-btn" style="padding-top: 0;">
				
			<div class="col-lg-5" style="padding: 0;">
				<button class="login100-form-btn">Find</button>
			</div>
			<div class="col-lg-2"></div>
			<div class="col-lg-5" style="padding: 0;">
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
					{{-- <th style="vertical-align: middle;"><small><b>Status</b></small></th> --}}
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
									<small>
										{{ $nextEnrollment['level']['year'] }} <br>
										{{ $nextEnrollment['schoolYear']['schoolYear'] }} <br>
										{{ $nextEnrollment['term_type'] }}											
									</small>
								</td>
								{{-- <td style="vertical-align: middle;"><small>{{ $student->is_enrolled }}</small></td> --}}
								<td style="vertical-align: middle;">
									@if($nextEnrollment['schoolYear']['schoolYear'] === $enrollment->schoolYear->schoolYear && $nextEnrollment['level']['year'] === $enrollment->level->year )
										@if( strtolower($enrollment->term_type) == "first")
											<form action="{{ url('kiosk/enlisting/old/' . $student->studentnumber) }}" method="POST">
												@csrf()
												<button class="btn btn-xs btn-primary">Select</button>
											</form>
										@else
											<a href="javascript:void(0)" class="btn btn-xs btn-success disabled" disabled>Unavailable</a>
										@endif
									@else
										<form action="{{ url('kiosk/enlisting/old/' . $student->studentnumber) }}" method="POST">
											@csrf()
											<button class="btn btn-xs btn-primary">Select</button>
										</form>
									@endif
								</td>
							</tr>
					@else
						<td colspan="6"><h3 class="text-center">No Previous Enrollment Found</h3></td>
					@endif
				</tbody>
			</table>
		@endif

@endsection


@section('after_scripts')
	<script type="text/javascript">
		if( !/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
			$('table').removeClass('table-responsive')
		}
	</script>
@endsection