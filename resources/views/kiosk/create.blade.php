@extends('kiosk.layout')

@section('after_styles')
	
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
	<style>
		.form-group label {
			color: #ebe965;
		}
		.wrap-login100 {
			position: relative;
			margin-top: 50px;
			overflow: unset;
			padding: 60px;
		}
		.form-control {
			padding: .390rem .75rem !important;
    		font-size: 14px !important;
		}
		.emergency-contact {
			display: none;
		}
	</style>
@endsection

@section('content')

	

	<span class="login100-form-title">
		New Student
	</span>
	{{-- {{ dd(get_defined_vars()) }} --}}
	@if ($errors->any())
		<div class="alert alert-danger" role="alert" style="width: 100%;">
			 @foreach ($errors->all() as $error)
				<p><i class="fa fa-info-circle"></i>&nbsp; {{ $error }}</p>
			 @endforeach
		</div>
	@endif

	<form id="registrationForm" class="login100-form validate-form" role="form" method="POST" action="{{ url()->current() }}/student" aria-label="{{ __('Login') }}" style="width: 100%;">

		@csrf            
	
		<div class="row">

			{{-- SCHOOL YEAR --}}
			<div class="form-group col-md-4">
				<label for="schoolyear">School Year*</label>
				<select id="schoolyear" name="schoolyear" class="form-control" readonly>
					@foreach($schoolYears as $sy)
						<option value="{{ $sy->id }}">{{ $sy->schoolYear }}</option>
					@endforeach
				</select>
			</div>

			{{-- DEPARTMENT --}}
			<div class="form-group col-md-4">
				<label for="department_id">Department*</label>
				<select id="department_id"type="text" name="department_id" class="form-control" required>
					@if(old('department_id') !== null)
						<option value="" disabled>Please Select Department</option>
						@foreach($departments as $department)
							@if (old('department_id') == $sy->id)
					      		<option value="{{ $department->id }}" selected>{{ $department->name }}</option>
							@else
						     	<option value="{{ $department->id }}" >{{ $department->name }}</option>
							@endif
						@endforeach
					@else
						<option value="" selected disabled>Please Select Department</option>
						@foreach($departments as $department)					
					     	<option value="{{ $department->id }}" >{{ $department->name }}</option>
						@endforeach
					@endif
				</select>
			</div>

			{{-- LEVEl --}}
			<div class="form-group col-md-4">
				<label for="level_id">Level*</label>
				<select id="level_id" name="level_id" class="form-control" required></select>
			</div>

			{{-- TRACK --}}
			<div class="form-group col-md-6" id="track" style="display: none;">
				<label for="track_id">Track*</label>
				<select id="track_id" name="track_id" class="form-control"></select>
			</div>

			{{-- TERM --}}
			<div class="form-group col-md-6" id="term">
				<label for="term">Term*</label>
				<select id="term_type" name="term" class="form-control"></select>
			</div>

			{{-- STRAND SUBJECT LIST --}}
			<div id="subject-strand-list" class="form-group col-md-12">
				<ul class="list-group"></ul>
			</div>
			
		</div>

		{{-- STUDENT INFORMATION --}}
		@include('kiosk.newStudent.student_information')
		{{-- // STUDENT INFORMATION --}}


		{{-- FATHER'S INFORMATION --}}
		@include('kiosk.newStudent.fathers_information')
		{{-- //FATHER'S INFORMATION --}}


		{{-- MOTHER'S INFORMATION --}}
		@include('kiosk.newStudent.mothers_information')
		{{-- // MOTHER'S INFORMATION --}}


		{{-- LEGAL GUARDIAN INFORMATION --}}
		@include('kiosk.newStudent.legal_guardian')
		{{-- // MOTHER'S INFORMATION --}}


		{{-- ENERGENCY CONTACT INFORMATION --}}
		@include('kiosk.newStudent.emergency_contact_information')
		{{-- // ENERGENCY CONTACT INFORMATION --}}

		<div class="container-login100-form-btn">
			<button class="login100-form-btn" id="btnSubmit">Submit</button>
			<div class="col-md-12">&nbsp;</div>
			<a href="/kiosk/enlisting" class="login100-form-btn btn-primary" style="background-color: #007bff;">Go Back</a>
		</div>

	</form>
		
@endsection


@section('after_scripts')
	<script src="{{ asset('js/jquery.min.js') }}"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>

	<script>
		$(document).ready(function () {

			var department_id = $('#department_id');
			var level_id 	  = $('#level_id');
			var track_id 	  = $('#track_id');
			var term 	  	  = $('#term_type');
			window.departments   = null;
			
	        function capitalize (s) {
	            if (typeof s !== 'string') return ''
	            return s.charAt(0).toUpperCase() + s.slice(1)
	        }

			function resetSelect () {
				level_id.html('<option value="" selected disabled>Please Select Level</option>');
				track_id.html('<option value="" selected>-</option>');
				term.html('<option value="" selected>-</option>');
				$('#track').css('display', 'none');
			}

			function disableForm () { $('input, select, textarea').attr('disabled', true); }
			function enableForm () { $('input, select, textarea').removeAttr('disabled'); }

			{{-- DEPARTMENT --}}
			function watchDepartment () {

				$('#subject-strand-list').html('');

				resetSelect();
				// setTimeout(function () {
				disableForm();
				// }, 100);

				// FIND DEPARTMENT AND GET THE LEVEL ACCORDING TO THE SELECTED DEPARTMENT 
				if(department_id.val() !== null) {
					var old_level_id = {{ old('level_id') }}
					$.ajax({
						url: 'api/department/' + department_id.val(),
						success: function (response) {
							departments = response;
							// EACH LEVEL
							var level_options = '<option value="" selected disabled>Please Select Level</option>';
							$.each(response.levels, function(key, val) {
								if(old_level_id !== undefined && old_level_id == val.id)
								{
									level_options += '<option value="'+ val.id +'" data-index="' + key + '" selected>' + val.year + '</option>';
								} else 
								{
									level_options += '<option value="'+ val.id +'" data-index="' + key + '">' + val.year + '</option>';
								}
							});

							level_id.html(level_options);

							if(response.term) {
								if(response.department_term_type === "FullTerm") {
									term.html('<option value="Full">Full</option>')
								} else {
									var options = '';
									$.each(response.term.ordinal_terms, function (key, val) {
										options += '<option value="' + capitalize(val) + '">' + capitalize(val) + '</option>';
									});

									term.html(options);
								}
							}
						}
					});
				}
				setTimeout(function () {
					enableForm();
				}, 1100);
			} // WATCH DEPARTMENT
			watchDepartment();

			// ON CHANGE DEPARTMENT
			department_id.change(function () { watchDepartment(); });

			function watchLevel (level) {
				console.log("ASD");
				$('#subject-strand-list').html('');

				var index 	      = level.find('option:selected').attr('data-index');
				var track_options = '<option value="" selected>-</option>';
				
				if(departments.levels[index].tracks.length > 0) {
					console.log('if');
					var track_options = '<option value="" selected disabled>Please Select Level</option>';
					$.each(departments.levels[index].tracks, function(key, val) {
						track_options += '<option value="' + val.id + '"  level-index="' + index + '" data-index="' + key + '">' + val.code + '</option>';
					});

					track_id.attr('required');
					$('#track').css('display', 'block');


				} else {
					console.log('else');
					$('#track').css('display', 'none');
					track_id.html('<option value="" selected>-</option>');
					track_id.removeAttr('required');

				}
				track_id.html(track_options);

			} // WATCH LEVEL

			// ON CHANGE LEVEL
			level_id.change(function () { watchLevel($(this)); });


			var old_track_id = "{{ old('track_id') ?? null  }}";


			// TRACK
			function watchTrack () {
				track_id.attr('required');
				$('#track').css('display', 'block');
				console.log(window.departments.levels[level_id.find('option:selected').attr('data-index')].tracks);
				var tracks 		 = window.departments.levels[level_id.find('option:selected').attr('data-index')].tracks;
				var trackOptions = '<option disabled>Please Select Track</option> ';

				$.each(tracks, function (key, val) {
					if(old_track_id !== "" && old_track_id == val.id)
					{
						trackOptions += '<option value="' + val.id + '" data-index="' + key + '" selected>' + val.code + '</option>';
					}
					else 
					{
						trackOptions += '<option value="' + val.id + '" data-index="' + key + '">' + val.code + '</option>';
					}
				});

				track_id.html(trackOptions);
			}
			if(old_track_id !== "")
			{
				setTimeout(function () {
					watchTrack();
				}, 1000);
			}
			// ON CHANGE LEVEL
			// track_id.change(function () { watchTrack(); });


		});
	</script>

	<script>
		$('#legal-guardian-other-relative').change(function () {
			if(this.checked) {
				$('#other-relative').css('visibility', 'visible').attr('required', 'required');
			} else {
				$('#other-relative').css('visibility', 'hidden').val('').removeAttr('required');
			}
		});

		$('#legal-guardian-other-relative').change(function () {
			if(this.checked) {
				$('#other-relative').css('visibility', 'visible').attr('required', 'required');
			} else {
				$('#other-relative').css('visibility', 'hidden').val('').removeAttr('required');
			}
		});



		// Is Transferee
		function transferee ()
		{
			var boolean = $('input[name="is_transferee"]:checked').val();
			if (!parseInt(boolean)) {
		    	$(".transferee-group").css('display', 'none');
		    }
		    else {
		    	$(".transferee-group").css('display', 'block');
		    }
		}
		transferee(); 
		$('input[type=radio][name=is_transferee]').change(function() { transferee(); });


		// emergencyRelationshipToChild
		function emergencyRelationshipToChild ()
		{
			var relationToChild = $('#emergency-relationship-to-child option:selected').val();
			if(relationToChild === 'Other') {
				$('.emergency-contact').css('display', 'block').find('input').attr('required', true);
				$('#emergency_middlename').removeAttr('required');
			} else {
				$('.emergency-contact').css('display', 'none').find('input').removeAttr('required');
			}
		}
		emergencyRelationshipToChild();
		$('#emergency-relationship-to-child').change(function () { emergencyRelationshipToChild(); });

		function validateEmail (email) {
		    var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
		    return re.test(String(email).toLowerCase());
		}
		var submitForm = false;
		$("#registrationForm").submit(function(e){
			e.preventDefault();

			if(submitForm) { 
				$('#registrationForm')[0].submit(); 
				return;
			}

			$.confirm({
			    title: 'Proceed',
			    content: 'Make sure your informations are correct. Do you want to proceed?',
			    buttons: {
			        yes: function () {
				        $.confirm({
						    title: 'Enter Email',
						    content: '' +
						    '<form action="" class="formName">' +
							    '<div class="form-group">' +
							    	'<label style="color: #4d4d4e; font-size: 13px;">Please enter your email, we will send you an email after you submit this form.</label>' +
							    	'<input type="email" name="email_input" placeholder="Email" class="email_input form-control" value="{{ old('email_input') }}" required />' +
							    '</div>' +
						    '</form>',
						    buttons: {
						        formSubmit: {
						            text: 'Submit',
						            btnClass: 'btn-blue',
						            action: function () {
						                var email = this.$content.find('.email_input').val();
						                if(!email) {
						                    $.alert('Provide a valid email');
						                    return false;
						                }

						                if(!validateEmail(email)) {
						                    $.alert('Provide a valid email ex. abc@mail.com');
						                    return false;
						                }
						                submitForm = true;
						                // If No Error
						                $("<input />").attr("type", "hidden").attr("name", "email_input").attr("value", email).appendTo("#registrationForm");
						                $('#registrationForm').submit();
						            }
						        },
						        cancel: function () {
						            //close
						        },
						    }
						});
			        },
			        cancel: function () {},
			    }
			});
			      
	    });
	</script>
@endsection