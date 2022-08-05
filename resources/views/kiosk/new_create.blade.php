@extends('kiosk.new_layout')

@section('after_styles')
	
  	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
  	<link href="{{ asset('vendor/adminlte/bower_components/select2/dist/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />
	<style>
		.login100-form-title {
			color: #000;
		}
		.form-group label {
			/*color: #ebe965;*/
			color: #000;
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
		.required {
			color: red;
		}
		.file-cont {
	      width: 100%; 
	      height: 40px; 
	      border: 1px solid #ccc; 
	      border-radius: 5px; 
	      background-color: #ddd; 
	      display: flex; 
	      margin: 10px 0px;
	    }

	    .file-text {
	      padding: 8px;
	    }

	    .file-icon {
	      padding: 5px; 
	      border-radius:  5px; 
	      background-color: #ddd; 
	      text-align: left;
	    }

	    .file-close {
	      float: right !important;
	      position: absolute;
	      right: 5px;
	    }
	</style>
@endsection

@section('content')

	<div class="container">
		<div class="row p-t-50 p-b-50" style="align-items: center;">
			<div class="col-md-12 col-lg-12 p-l-50 p-r-50">
				<span class="login100-form-title">
					New Student Application
					<br>
					{!! $enrollmentStatus->term == 'Summer' ? 'for <br> Summer <br>' : '' !!}
					<p>For School Year {{$enrollmentStatus->enrollment_status->school_year_name}}</p>
				</span>
				{{-- {{ dd(get_defined_vars()) }} --}}
				@if ($errors->any())
					<div class="alert alert-danger" role="alert" style="width: 100%;">
						 @foreach ($errors->all() as $error)
							<p><i class="fa fa-info-circle"></i>&nbsp; {{ $error }}</p>
						 @endforeach
					</div>
				@endif

				<form id="registrationForm" class="login100-form validate-form p-l-10 p-r-10" role="form" method="POST" action="{{ url()->current() }}/student" aria-label="{{ __('Login') }}" style="width: 100%;" enctype="multipart/form-data">

					@csrf

					<!--- OLD OR NEW STUDENT --->
					@if($newSchoolOption->active)
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<label for="">Status<span class="required">*</span></label>
									<br>
									<div class="form-check form-check-inline">
										<input class="form-check-input" id="old_student" type="radio" name="old_or_new" value="old" @if(old('old_or_new')) checked @endif>
										<label class="form-check-label" for="old_student">
											Old Student
										</label>
									</div>
									<div class="form-check form-check-inline">
										<input class="form-check-input" id="new_student" type="radio" name="old_or_new" value="new" @if(!old('old_or_new')) checked @endif>
										<label class="form-check-label" for="new_student">
											New Student
										</label>
									</div>
								</div>
							</div>
						</div>
					@endif
					<!--- // OLD OR NEW STUDENT --->
            
				
					<div class="row">

						<!--- SCHOOL YEAR --->
						{{-- <div class="form-group col-md-4" hidden>
							<label for="schoolyear">School Year<span class="required">*</span></label>
							<select id="schoolyear" name="schoolyear" class="form-control" readonly>
								@foreach($schoolYears as $sy)
									<option value="{{ $sy->id }}">{{ $sy->schoolYear }}</option>
								@endforeach
							</select>
						</div> --}}

						<div class="form-group col-md-3">
							<label for="schoolyear">School Year<span class="required">*</span></label>
							<select id="schoolyear" name="schoolyear" class="form-control" readonly>
								<option value="{{ $enrollmentStatus->enrollment_status->school_year_id }}" selected>{{ $enrollmentStatus->enrollment_status->school_year_name }}</option>
							</select>
						</div>

						<!--- DEPARTMENT --->
						{{-- <div class="form-group col-md-3">
							<label for="department_id">Department<span class="required">*</span></label>
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
						</div> --}}
						<div class="form-group col-md-3">
							<label for="department_id">Department<span class="required">*</span></label>
							<select disabled id="department_id"type="text" name="department_id" class="form-control" required>
								<option value="{{ $enrollmentStatus->enrollment_status->department_id }}" selected>{{ $enrollmentStatus->enrollment_status->department_name }}</option>
							</select>
						</div>

						<!--- LEVEl --->
						<div class="form-group col-md-3">
							<label for="level_id">Level<span class="required">*</span></label>
							<select id="level_id" name="level_id" class="form-control" required></select>
						</div>

						<!--- TRACK --->
						<div class="form-group col-md-3" id="track" style="display: none;">
							<label for="track_id">Track<span class="required">*</span></label>
							<select id="track_id" name="track_id" class="form-control"></select>
						</div>

						<!--- TERM --->
						{{-- <div class="form-group col-md-3" id="term">
							<label for="term">Term<span class="required">*</span></label>
							<select id="term_type" name="term" class="form-control"></select>
						</div> --}}
						<div class="form-group col-md-3" id="term">
							<label for="term">Term<span class="required">*</span></label>
							<select disabled id="term_type" name="term" class="form-control"></select>
						</div>

						<!--- STRAND SUBJECT LIST --->
						<div id="subject-strand-list" class="form-group col-md-12">
							<ul class="list-group"></ul>
						</div>
						
					</div>
					
					<!--- STUDENT INFORMATION --->
					@include('kiosk.newStudent.student_information')
					<!--- // STUDENT INFORMATION --->


					<!--- FATHER'S INFORMATION --->
					@include('kiosk.newStudent.fathers_information')
					<!--- //FATHER'S INFORMATION --->


					<!--- MOTHER'S INFORMATION --->
					@include('kiosk.newStudent.mothers_information')
					<!--- // MOTHER'S INFORMATION --->


					<!--- LEGAL GUARDIAN INFORMATION --->
					@include('kiosk.newStudent.legal_guardian')
					<!--- // LEGAL GUARDIAN INFORMATION --->


					<!--- ENERGENCY CONTACT INFORMATION --->
					@include('kiosk.newStudent.emergency_contact_information')
					<!--- // ENERGENCY CONTACT INFORMATION --->
					
					@if($initialPayment)
						@if($initialPayment->active)
						<!--- UPDATE REQUIREMENTS --->
						<div class="row">
							<div class="form-group col-md-12">
								<label for="firstname">Proof of Payment</label>
								<div id="items-container"></div>
								<div id="preview" class="form-group"></div>
								<div class="input-group mb-3">
									<div class="custom-file">
									    <input type="file" class="custom-file-input" name="payment_upload" id="payment_upload">
									    <label class="custom-file-label" for="payment_upload">Choose file</label>
									</div>
								</div>
							</div>
						</div>
						<!--- // UPDATE REQUIREMENTS --->
						@endif
					@endif

					@if($allowReferral)
						@if($allowReferral->active)
							<div class="row">
								<label class="form-group col-md-12">
							    	How did you know <strong> {{ Config::get('settings.schoolname') }}?</strong>
							    	<span class="required">*</span>
							  	</label>
								<div class="form-group col-md-4">
								  	<select class="form-control" name="referral_type" id="referral_type" width="100%" required>
								  		<option value="social media" {{ old('referral_type') == 'social media' ? 'selected' : ''}}>
								  			Social Media
								  		</option>
								  		<option value="search engine" {{ old('referral_type') == 'search engine' ? 'selected' : ''}}>
								  			Search Engine
								  		</option>
								  		<option value="referred" {{ old('referral_type') == 'referred' ? 'selected' : ''}}>
								  			Referred
								  		</option>
								  		<option value="other" {{ old('referral_type') == 'other' ? 'selected' : ''}}>
								  			Other
								  		</option>
								  	</select>
								</div>
								<div class="form-group col-md-4" id="referred-by">
								</div>
								<div class="form-group col-md-4" id="referrer-contact" style="display: none;">
									<input type="text" name="referrer_contact" id="referrer_contact" class="form-control" placeholder="Contact Number">
								</div>
							</div>
						@endif
					@endif

					@if($termsConditions)
						@if($termsConditions->active)
							<div class="row">
								<div class="form-group col-md-12">
									<div class="form-check">
									  	<input class="form-check-input" type="checkbox" id="terms_conditions" name="terms_conditions">
									  	<label class="form-check-label" for="terms_conditions">
									    	I agree to the <a id="terms_conditions_link" href="{{url('kiosk/enlisting/privacy') }}" target="_blank"><strong>terms, conditions and data privacy.</strong></a>
									  	</label>
									</div>
								</div>
							</div>
						@endif
					@endif

					<div class="container-login100-form-btn">
						<button class="login100-form-btn" id="btnSubmit">Submit</button>
						<div class="col-md-12">&nbsp;</div>
						<a href="/kiosk/enlisting" class="login100-form-btn btn-primary" style="background-color: #007bff;">Go Back</a>
					</div>

				</form>
			</div>
		</div>
		
	</div>	
		
@endsection


@section('after_scripts')
	<script src="{{ asset('js/jquery.min.js') }}"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>

    <script src="{{ asset('vendor/adminlte/bower_components/select2/dist/js/select2.min.js') }}"></script>
    <script>
    	var select2City 	= $('#city_municipality'), 
			select2Barangay = $('#barangay'),
			select2Province = $('#province');
    </script>

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

			/**
             * DEPARTMENT
             */
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
						url: '/kiosk/enlisting/api/department/' + department_id.val(),
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
								term.html('<option value="{{$enrollmentStatus->term}}" selected>{{$enrollmentStatus->term}}</option>')
								// if(response.department_term_type === "FullTerm") {
								// 	term.html('<option value="Full">Full</option>')
								// } else {
								// 	var options = '';
								// 	$.each(response.term.ordinal_terms, function (key, val) {
								// 		options += '<option value="' + capitalize(val) + '">' + capitalize(val) + '</option>';
								// 	});

								// 	term.html(options);
								// }
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

	{{-- FOR ADDRESS --}}
	<script type="text/javascript">
		const capitalize = (str) => {
		    return str.replace(/\w\S*/g, function(txt){
		        return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();
		    });
		}

		var provinces,
			cities, 
			barangays;

		// init();

		$(window).bind("load", function () {
	
			$(document).ready(function () {

					function getProvinces () {
						var options = '<option selected disabled>Please Select Province</option>';

						$.ajax({
							url: '/kiosk/enlisting/api/provinces',
							success: function (response) {
								$.each(response.records, function (key, val) {
									options += '<option value="' + capitalize(val.provDesc.toLowerCase()) + '" province-code="' + val.provCode + '">' + capitalize(val.provDesc.toLowerCase()) + '</option>';
								})
								$('select[name="province"]').html(options);

							}
						})
						
					}

					getProvinces();

					function getCities () {
						var provinceCode = $('#province').find('option:selected').attr('province-code');

						$.ajax({
							url: '/kiosk/enlisting/api/cities',
							data: {
								province_code: $('#province').find('option:selected').attr('province-code'),
							},
							success: function (response) {
								var options = '<option selected disabled>Please Select City/Municipality</option>';
								$.each(response, function (key, val) {
									if(val.provCode == provinceCode) {
										options += '<option value="' + capitalize(val.citymunDesc.toLowerCase()) + '" city-code="' + val.citymunCode + '">' + capitalize(val.citymunDesc.toLowerCase()) + '</option>';
									}
								});

								$('select[name="city_municipality"]').html(options);
								$('#city_municipality').closest('.form-group').find('.select2-chosen').text('-');
							}
						})
						
					}

					function getBarangay () {
						var cityCode = $('#city_municipality').find('option:selected').attr('city-code');

						$.ajax({
							url: '/kiosk/enlisting/api/barangay',
							data: {
								city_code: $('#city_municipality').find('option:selected').attr('city-code')
							},
							success: function (response) {
								var options = '<option selected disabled>Please Select Barangay</option>';
								$.each(response, function (key, val) {
									if(val.citymunCode == cityCode) {
										options += '<option value="' + capitalize(val.brgyDesc.toLowerCase()) + '" barangay-code="' + val.brgyCode + '">' + capitalize(val.brgyDesc.toLowerCase()) + '</option>';
									}
								});

								$('select[name="barangay"]').html(options);
								$('#barangay').closest('.form-group').find('.select2-chosen').text('-');
							}
						});
						
					}

					select2Province.on('change', function(e) {
						$('#city_municipality').html('<option selected disabled>Please Select City/Municipality</option>');
						$('#barangay').html('<option selected disabled>Please Select Barangay</option>');
						
						$('#city_municipality').closest('.form-group').find('.select2-chosen').text('Loading...');
						$('#barangay').closest('.form-group').find('.select2-chosen').text('Loading...');
		                getCities();
		            });

		            select2City.on('change', function (e) {
		            	$('#barangay').html('');
		            	$('#barangay').closest('.form-group').find('.select2-chosen').text('Loading...');
		            	getBarangay();
		            });

			})
		});

	</script>
	
	@if($initialPayment->active)
	{{-- FOR FILE UPLOAD PROOF OF PAYMENT --}}
	<script type="text/javascript">
		$("#items-container").hide();
    	var file_count = 0;
    	// var file_items = [];
    	var file_items = []; 

		$("#payment_upload").change(function() {
			// alert($(this)[0].files.length);
			var outputs = '';
        	var files =  $(this)[0].files;
			if (files.length > 0) {
				 var i;
	        	var outputs;
				for (i = 0; i < $(this)[0].files.length; i++) {
				  outputs += '<div id="file-index-'+file_count+'" class="input-group file-cont">'+
				  				'<i class="file-icon fa fa-files-o fa-2x p-1"></i>' +
				  				'<p class="file-text">'+files.item(i).name+' ('+ Math.round((files.item(i).size/1024))+'KB)'+' </p>' +
				  				'<a data-index="'+file_count+'" href="#" onclick="removeFile('+file_count+')"><i class="file-close fa fa-times float-right pull-right justify-content-end"></i></a>' +
				  			'</div>';
				  
				  file_item = (files.item(i));
				  file_items.push(file_item);
				  // console.log(file_items);
				  
				  $('#items-container').append('<div id="item-'+file_count+'"><input type="file" name="proof_of_payment[]" id="file-'+file_count+'"/></div>');
				  $("#file-"+file_count)[0].files =  $(this)[0].files;
				  file_count++;
				}
				$('#preview').append(outputs);
			}
		});
		 function removeFile(index){
        	file_items.splice(index,1);
        	$("#file-index-"+index).remove();
        	$("#item-"+index).remove();
        	if(file_items.length > 0){
        		$('#btnPost').attr('disabled', false);
        	} else {
        		$('#btnPost').attr('disabled', true);
        	}
        }
	</script>
	@endif

	@if($allowReferral)
		@if($allowReferral->active)
		<script>
			$('#referral_type').change(function () {
				referredType();
			});

			function referredType()
			{
				var referral_type = $('#referral_type option:selected').val();

				var referrer_contact = 	'<input type="text" name="referrer_contact" id="referrer_contact" class="form-control" placeholder="Contact Number">';
				
				if(referral_type == 'social media') {
					var referred_by = 	'<select class="form-control" name="referred_by" id="referred_by" width="100%" required>\
									  		<option value="Facebook">Facebook</option>\
									  		<option value="Instagram">Instagram</option>\
									  		<option value="Twitter">Twitter</option>\
									  	</select>';
					$('#referred-by').empty();
					$('#referred-by').html(referred_by);
					$('#referrer-contact').hide();
				} else if(referral_type == 'search engine') {
					var referred_by  	 = 	'<input type="text" name="referred_by" id="referred_by" class="form-control" placeholder="Please specify">';
					$('#referred-by').empty();
					$('#referred-by').html(referred_by);
					$('#referrer-contact').hide();
				} else if(referral_type == 'referred') {
					var referred_by  	 = 	'<input type="text" name="referred_by" id="referred_by" class="form-control" placeholder="Referred by">';
					$('#referred-by').empty();
					$('#referred-by').html(referred_by);
					$('#referrer-contact').show();
				} else{
					var referred_by  	 = 	'<input type="text" name="referred_by" id="referred_by" class="form-control" placeholder="Please specify">';
					$('#referred-by').empty();
					$('#referred-by').html(referred_by);
					$('#referrer-contact').show();
				}
			}

			referredType();
		</script>
		@endif
	@endif
@endsection