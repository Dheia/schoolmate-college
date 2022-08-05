@extends('backpack::layout')

@section('after_styles')
  <style type="text/css">
    .padding-left-15 {
      padding-left: 15px;
    }
    .pad-top {
      margin-top: 5px;
      padding-top: 5px;
    }

    .control-labels{
        margin: 0px;
        padding: 0px;
    }
    .nav-pills>li {
      margin-top: 5px;
    }
    .nav-pills>li>a {
      border-radius: 10px;
    }
    .nav-pills>li.active>a {
      border-top-color: #007bff !important;
      color: #ffffff;
      background-color: #007bff !important;
    }
    .box-primary {
      border-top-color: #007bff !important;
    }

    .tab-content {
	    box-shadow:  none !important;
	}
  </style>
@endsection

@section('header')
	{{-- <section class="content-header">
	  <h1>
        <span class="text-capitalize">{{ $crud->entity_name_plural }}</span>
        <small>{{ trans('backpack::crud.edit').' '.$crud->entity_name }}.</small>
	  </h1>
	  <ol class="breadcrumb">
	    <li><a href="{{ url(config('backpack.base.route_prefix'),'dashboard') }}">{{ trans('backpack::crud.admin') }}</a></li>
	    <li><a href="{{ url($crud->route) }}" class="text-capitalize">{{ $crud->entity_name_plural }}</a></li>
	    <li class="active">{{ trans('backpack::crud.edit') }}</li>
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
			    <li><a href="{{ url('admin/student') }}" class="text-capitalize">Students</a></li>
			    <li class="active">Record</li>
			  </ol>
			</section>
			{{-- <h1 class="smo-content-title">
		        <span class="text-capitalize">{{ $crud->entity_name_plural }}</span>
		        <small>{{ trans('backpack::crud.edit').' '.$crud->entity_name }}.</small>
		    </h1> --}}
    	</div>
    </div>
    <!-- HEADER END -->
	<div class="row p-l-10 p-r-10">

		<!-- RIGHT SIDEBAR -->
		<div class="col-md-3 col-lg-3 col-two">
			<!-- Profile Image -->
	      	<div class="box box-primary" style="border-radius: 10px; border-top: 3px solid #d2d6de;">
		        <div class="box-body box-profile">
		          	@php
		            	$avatar = $student ? $student->photo : 'images/headshot-default.png';
		          	@endphp
		         	<img class="profile-user-img img-responsive img-circle" src="{{ url($student->photo) }}" alt="User profile picture">

		          	<h3 class="profile-username text-center">{{ $student->fullname }}</h3>

		          	<p class="text-muted text-center bold">{{ $student->prefixed_student_number }}</p>

		          	<ul class="list-group list-group-unbordered mb-3">
			            <li class="list-group-item text-center">
			              	<b>{{ $student->current_level }}</b>
			            </li>
			            @if($student->student_section_name)
			            	<li class="list-group-item text-center">
			              		<b>{{ $student->student_section_name }}</b>
			            	</li>
			            @endif
			            @if($student->track_name)
			              	<li class="list-group-item text-center">
			                	<b>{{ $student->track_name }}</b>
			              	</li>
			            @endif
			            <li class="list-group-item text-center">
			              	<b>{{ $student->department_name }}</b>
			            </li>
		          	</ul>
		        </div>
		        <!-- /.box-body -->
	      	</div>
	      	<!-- /.box -->

			<div class="box box-primary" style="border-radius: 10px; border-top: 3px solid #d2d6de;">
				<div class="box-header">
					<h4>
			            Actions
			        </h4>
				</div>
				<div class="box-body">
					<!-- EDIT BUTTON -->
					@if(backpack_user()->hasRole('School Head'))
						<a href="{{ url('admin/student/' . $student->getKey() . '/edit') }}" class="btn btn-primary w-100" title="Edit">
							<i class="fa fa-edit"></i>
							Edit
						</a>
					@endif

					<a href="{{ url('admin/student/' . $student->id . '/print') }}" target="_blank" class="btn btn-primary w-100" title="Print Application Form">
						<i class="fa fa-print"></i> Print Application Form
					</a>

					@if(!$student->has_student_credential)
						<a href="{{ url('admin/student/' . $student->getKey() . '/portal/enable') }}" class="btn btn-danger w-100" title="Activate Portal">
							<i class="fa fa-key"></i> Activate Portal
						</a>
					@else
						<a href="{{ url('admin/student/' . $student->getKey() . '/portal/disable') }}" class="btn btn-success w-100" title="Revoke Access to Portal">
							<i class="fa fa-key"></i> Revoke Access to Portal
						</a>

						@if($student->studentCredential->is_disabled)
							<a href="{{ url('admin/student/' . $student->getKey() . '/account/enable') }}" class="btn btn-default w-100" title="Enable Account">
								<i class="fa fa-lock-open"></i> Enable Portal Account
							</a>
						@else
							<a href="{{ url('admin/student/' . $student->getKey() . '/account/disable') }}" class="btn btn-default w-100" title="Disable Account">
								<i class="fa fa-lock"></i> Disable Portal Account
							</a>
						@endif
					@endif

					<a href="{{ url('admin/student/' . $student->getKey() . '/create-email') }}" class="btn btn-primary w-100" title="Create webmail account"><i class="fa fa-plus"></i> Create Email Account</a>

					@if($student->qbo_customer_id == null)
						<a href="{{ url('admin/student/' . $student->getKey() . '/register/quickbooks') }}" class="btn btn-success w-100" title="Add student to QB Online">
							<i class="fa fa-user-plus"></i>
							Add Student To QB Online
						</a>
					@endif
				</div>
			</div>
		</div>

		<div class="col-md-9 col-lg-9 col-one">
			<div class="box box-primary" style="border-radius: 10px; border-top: 3px solid #d2d6de;">

	      		<div class="nav-tabs-custom" style="border-radius: 10px;">
	        		<div class="box-header">
	          			<ul class="nav nav-pills">
				            <li class="active">
				            	<a href="#student-information" data-toggle="tab" aria-expanded="true">
				            		<strong>Student Information</strong>
				            	</a>
				            </li>
				            <li class="">
				            	<a href="#family-background" data-toggle="tab" aria-expanded="false">
				            		<strong>Family Background</strong>
				            	</a>
				            </li>
				            <li class="">
				            	<a href="#medical-history" data-toggle="tab" aria-expanded="false">
				            		<strong>Medical History</strong>
				            	</a>
				            </li>
				            <li class="">
				            	<a href="#other-information" data-toggle="tab" aria-expanded="false">
				            		<strong>Other Information</strong>
				            	</a>
				            </li>
				            <li class="">
				            	<a href="#enrollments" data-toggle="tab" aria-expanded="false">
				            		<strong>Enrollments</strong>
				            	</a>
				            </li>
	           			</ul>
	       			</div>
	        		
	        		<div class="tab-content" style="border-radius: 0;">

			          	<!-- START STUDENT INFORMATION -->
			          	<div class="tab-pane active" id="student-information">
			            	<div class="panel panel-default" style="box-shadow: none;">

				            	<!-- Heading -->
					            <div class="panel-heading">
					            	<h3><strong>Student Information</strong></h3>
					            </div>

			              		<div class="panel-body">
					              	<!-- Photo -->
					              	<div class="form-group col-md-4 col-lg-4">
						              	<div data-preview="#photo" data-aspectratio="1" data-crop="1" class="image text-center">
						    				<div>
										        <label>Photo</label>
										    </div>
										    <img id="mainImage" src="{{asset($student->photo)}}" style="width: 220px;">
							      		</div>
							      	</div>

							      	<div class="form-group col-md-4 col-lg-4">
									    <label>School Year Entered</label>
									    <input type="text" name="school_year_entered" value="{{ $student->school_year_name }}" readonly="1" class="form-control">
								    </div>

								    <div class="form-group col-md-4 col-lg-4">
									    <label>Application Date</label>
									    <input type="text" name="application" value="{{ date("F d, Y", strtotime($student->application)) }}" readonly="1" class="form-control">
								    </div>

					              	<div class="form-group col-md-4 col-lg-4">
									    <label>Studentnumber</label>
									    <input type="text" name="studentnumber" value="{{ $student->studentnumber }}" readonly="1" class="form-control">
								    </div>

								    <div class="form-group col-md-4 col-lg-4">
									    <label>LRN</label>
									    <input type="text" name="lrn" value="{{ $student->lrn }}" readonly="1" class="form-control">
								    </div>

								    <div class="form-group col-md-4 col-lg-4">
									    <label>Current Department</label>
									    <input type="text" name="current_department" value="{{ $student->current_department }}" readonly="1" class="form-control">
								    </div>

								    <div class="form-group col-md-4 col-lg-4">
									    <label>Current Level</label>
									    <input type="text" name="current_level" value="{{ $student->current_level }}" readonly="1" class="form-control">
								    </div>

								    <div class="form-group col-md-4 col-lg-4">
									    <label>Contact Number</label>
									    <input type="text" name="contactnumber" value="{{ $student->contactnumber }}" readonly="1" class="form-control">
								    </div>

								    <div class="form-group col-md-4 col-lg-4">
									    <label>Email</label>
									    <input type="text" name="email" value="{{ $student->email }}" readonly="1" class="form-control">
								    </div>

							      	<!-- Student's Fullname -->
					              	<div class="form-group col-md-3 col-lg-3">
									    <label>Lastname</label>
									    <input type="text" name="lastname" value="{{ $student->lastname }}" readonly="1" class="form-control">
								    </div>

								    <div class="form-group col-md-3 col-lg-3">
									    <label>Firstname</label>
									    <input type="text" name="firstname" value="{{ $student->firstname }}" readonly="1" class="form-control">
								    </div>

								    <div class="form-group col-md-3 col-lg-3">
									    <label>Middlename</label>
									    <input type="text" name="middlename" value="{{ $student->middlename }}" readonly="1" class="form-control">
								    </div>
								    <!-- End of Student's Fullname -->

								    <div class="form-group col-md-3 col-lg-3">
									    <label>Gender</label>
									    <input type="text" name="gender" value="{{ $student->gender }}" readonly="1" class="form-control">
								    </div>

								    <div class="form-group col-md-3 col-lg-3">
									    <label>Citizenship</label>
									    <input type="text" name="citizenship" value="{{ $student->citizenship }}" readonly="1" class="form-control">
								    </div>

								    <div class="form-group col-md-3 col-lg-3">
									    <label>Religion</label>
									    <input type="text" name="religion" value="{{ $student->religion }}" readonly="1" class="form-control">
								    </div>

								    <div class="form-group col-md-3 col-lg-3">
									    <label>Place of Birth</label>
									    <input type="text" name="birthplace" value="{{ $student->birthplace }}" readonly="1" class="form-control">
								    </div>

								    <div class="form-group col-md-3 col-lg-3">
									    <label>Date of Birth</label>
									    <input type="text" name="birthdate" value="{{ date("F d, Y", strtotime($student->birthdate)) }}" readonly="1" class="form-control">
								    </div>
								    
								    <!-- Residential Address -->
								    <div class="form-group col-md-12 col-lg-12" style="margin-bottom: 0;">
										<h5 style="margin-bottom: 0;"><strong>Residential Address In The Philippines</strong></h5>
									</div>

									<div class="form-group col-md-3 col-lg-3">
									    <label>Province</label>
									    <input type="text" name="province" value="{{ $student->province }}" readonly="1" class="form-control">
								    </div>

								    <div class="form-group col-md-3 col-lg-3">
									    <label>City/Municipality</label>
									    <input type="text" name="city_municipality" value="{{ $student->city_municipality }}" readonly="1" class="form-control">
								    </div>

								    <div class="form-group col-md-3 col-lg-3">
									    <label>Barangay</label>
									    <input type="text" name="barangay" value="{{ $student->barangay }}" readonly="1" class="form-control">
								    </div>

								    <div class="form-group col-md-3 col-lg-3">
									    <label>Street No.</label>
									    <input type="text" name="street_number" value="{{ $student->street_number }}" readonly="1" class="form-control">
								    </div>
								    <!-- End of Residential Address -->
			          			</div>

			        		</div>
			          	</div>
			          	<!-- END STUDENT INFORMATION -->


			          	<!-- START FAMILY BACKGROUND -->
			          	<div class="tab-pane" id="family-background">

				            <!-- Start Father Information -->
				            <div class="panel panel-default" style="box-shadow: none;">

			              		<!-- Heading -->
					            <div class="panel-heading">
					            	<h3><strong>Father Information</strong></h3>
					            </div>

				              	<div class="panel-body">
				              		<div class="form-group col-md-12 col-lg-12">
									    <label>Living or Deceased</label>
									    <input type="text" name="father_living_deceased" value="{{ $student->father_living_deceased }}" readonly="1" class="form-control text-capitalize">
								    </div>
				              		
				              		<!-- Father's Fullname -->
					            	<div class="form-group col-md-4 col-lg-4">
									    <label>Lastname</label>
									    <input type="text" name="fatherlastname" value="{{ $student->fatherlastname }}" readonly="1" class="form-control">
								    </div>

								    <div class="form-group col-md-4 col-lg-4">
									    <label>Firstname</label>
									    <input type="text" name="fatherfirstname" value="{{ $student->fatherfirstname }}" readonly="1" class="form-control">
								    </div> 

								    <div class="form-group col-md-4 col-lg-4">
									    <label>Middlename</label>
									    <input type="text" name="fathermiddlename" value="{{ $student->fathermiddlename }}" readonly="1" class="form-control">
								    </div> 
								    <!-- End Of Father's Fullname -->

								    <div class="form-group col-md-4 col-lg-4">
									    <label>Citizenship</label>
									    <input type="text" name="fathercitizenship" value="{{ $student->fathercitizenship }}" readonly="1" class="form-control">
								    </div> 

								    <div class="form-group col-md-4 col-lg-4">
									    <label>Philippine Visa Status</label>
									    <input type="text" name="fathervisastatus" value="{{ $student->fathervisastatus }}" readonly="1" class="form-control">
								    </div> 

								    <div class="form-group col-md-4 col-lg-4">
									    <label>Mobile Number</label>
									    <input type="text" name="fatherMobileNumber" value="{{ $student->fatherMobileNumber }}" readonly="1" class="form-control">
								    </div> 

								    <div class="form-group col-md-4 col-lg-4">
									    <label>Father Occupation</label>
									    <input type="text" name="father_occupation" value="{{ $student->father_occupation }}" readonly="1" class="form-control">
								    </div> 

								    <div class="form-group col-md-4 col-lg-4">
									    <label>Employer/Organization</label>
									    <input type="text" name="fatheremployer" value="{{ $student->fatheremployer }}" readonly="1" class="form-control">
								    </div> 

								    <div class="form-group col-md-4 col-lg-4">
									    <label>Office Number</label>
									    <input type="text" name="fatherofficenumber" value="{{ $student->fatherofficenumber }}" readonly="1" class="form-control">
								    </div> 

								    <div class="form-group col-md-6 col-lg-6">
									    <label>Graduate Degree</label>
									    <textarea name="fatherdegree" readonly="1" class="form-control">{{ $student->fatherdegree }}</textarea>
								    </div>

								    <div class="form-group col-md-6 col-lg-6">
									    <label>School</label>
									    <textarea name="fatherschool" readonly="1" class="form-control">{{ $student->fatherschool }}</textarea>
								    </div>
				              	</div>

				            </div>
				            <!-- End Father Information -->

				            <!-- Start Mother Information -->
				            <div class="panel panel-default" style="box-shadow: none;">
				              	<!-- Heading -->
					            <div class="panel-heading">
					            	<h3><strong>Mother Information</strong></h3>
					            </div>

				              	<div class="panel-body">
				              		<div class="form-group col-md-12 col-lg-12">
									    <label>Living or Deceased</label>
									    <input type="text" name="mother_living_deceased" value="{{ $student->mother_living_deceased }}" readonly="1" class="form-control text-capitalize">
								    </div>
				              		
				              		<!-- Mother's Fullname -->
					            	<div class="form-group col-md-4 col-lg-4">
									    <label>Lastname</label>
									    <input type="text" name="motherlastname" value="{{ $student->motherlastname }}" readonly="1" class="form-control">
								    </div>

								    <div class="form-group col-md-4 col-lg-4">
									    <label>Firstname</label>
									    <input type="text" name="motherfirstname" value="{{ $student->motherfirstname }}" readonly="1" class="form-control">
								    </div> 

								    <div class="form-group col-md-4 col-lg-4">
									    <label>Middlename</label>
									    <input type="text" name="mothermiddlename" value="{{ $student->mothermiddlename }}" readonly="1" class="form-control">
								    </div> 
								    <!-- End Of Father's Fullname -->

								    <div class="form-group col-md-4 col-lg-4">
									    <label>Citizenship</label>
									    <input type="text" name="mothercitizenship" value="{{ $student->mothercitizenship }}" readonly="1" class="form-control">
								    </div> 

								    <div class="form-group col-md-4 col-lg-4">
									    <label>Philippine Visa Status</label>
									    <input type="text" name="mothervisastatus" value="{{ $student->mothervisastatus }}" readonly="1" class="form-control">
								    </div> 

								    <div class="form-group col-md-4 col-lg-4">
									    <label>Mobile Number</label>
									    <input type="text" name="motherMobileNumber" value="{{ $student->motherMobileNumber }}" readonly="1" class="form-control">
								    </div> 

								    <div class="form-group col-md-4 col-lg-4">
									    <label>Father Occupation</label>
									    <input type="text" name="mother_occupation" value="{{ $student->mother_occupation }}" readonly="1" class="form-control">
								    </div> 

								    <div class="form-group col-md-4 col-lg-4">
									    <label>Employer/Organization</label>
									    <input type="text" name="motheremployer" value="{{ $student->motheremployer }}" readonly="1" class="form-control">
								    </div> 

								    <div class="form-group col-md-4 col-lg-4">
									    <label>Office Number</label>
									    <input type="text" name="motherofficenumber" value="{{ $student->motherofficenumber }}" readonly="1" class="form-control">
								    </div> 

								    <div class="form-group col-md-6 col-lg-6">
									    <label>Graduate Degree</label>
									    <textarea name="motherdegree" readonly="1" class="form-control">{{ $student->motherdegree }}</textarea>
								    </div>

								    <div class="form-group col-md-6 col-lg-6">
									    <label>School</label>
									    <textarea name="motherschool" readonly="1" class="form-control">{{ $student->motherschool }}</textarea>
								    </div>
				              	</div>

				            </div>
				            <!-- End Mother Information -->

				            <!-- Start Legal Guardian Information -->
				            <div class="panel panel-default" style="box-shadow: none;">
				              
				              	<!-- Heading -->
					            <div class="panel-heading">
					            	<h3><strong>Legal Guardian Information</strong></h3>
					            </div>

				              	<div class="panel-body">
				              		<!-- Legal Guardian's Fullname -->
					            	<div class="form-group col-md-4 col-lg-4">
									    <label>Lastname</label>
									    <input type="text" name="legal_guardian_lastname" value="{{ $student->legal_guardian_lastname }}" readonly="1" class="form-control">
								    </div>

								    <div class="form-group col-md-4 col-lg-4">
									    <label>Firstname</label>
									    <input type="text" name="legal_guardian_firstname" value="{{ $student->legal_guardian_firstname }}" readonly="1" class="form-control">
								    </div> 

								    <div class="form-group col-md-4 col-lg-4">
									    <label>Middlename</label>
									    <input type="text" name="legal_guardian_middlename" value="{{ $student->legal_guardian_middlename }}" readonly="1" class="form-control">
								    </div> 
								    <!-- End Of Legal Guardian's Fullname -->

								    <div class="form-group col-md-4 col-lg-4">
									    <label>Citizenship</label>
									    <input type="text" name="legal_guardian_citizenship" value="{{ $student->legal_guardian_citizenship }}" readonly="1" class="form-control">
								    </div>

								    <div class="form-group col-md-4 col-lg-4">
									    <label>Occupation</label>
									    <input type="text" name="legal_guardian_occupation" value="{{ $student->legal_guardian_occupation }}" readonly="1" class="form-control">
								    </div>

								    <div class="form-group col-md-4 col-lg-4">
									    <label>Mobile Number</label>
									    <input type="text" name="legal_guardian_contact_number" value="{{ $student->legal_guardian_contact_number }}" readonly="1" class="form-control">
								    </div>
				              	</div>

				            </div>
				            <!-- End Legal Guardian Information -->

				            <!-- Start Emergency Contact Information -->
				            <div class="panel panel-default" style="box-shadow: none;">

				              	<!-- Heading -->
					            <div class="panel-heading">
					            	<h3><strong>Legal Guardian Information</strong></h3>
					            </div>
					            
					            <div class="panel-body">
					           		<!-- Emergency Contact Fullname -->
					            	<div class="form-group col-md-4 col-lg-4">
									    <label>Fullname</label>
									    <input type="text" name="emergency_contact_name_on_record" value="{{ $student->emergency_contact_name_on_record }}" readonly="1" class="form-control">
								    </div>

								    <div class="form-group col-md-4 col-lg-4">
									    <label>Mobile Number</label>
									    <input type="text" name="emergency_contact_number_on_record" value="{{ $student->emergency_contact_number_on_record }}" readonly="1" class="form-control">
								    </div>

								    <div class="form-group col-md-4 col-lg-4">
									    <label>Home Phone</label>
									    <input type="text" name="emergency_contact_home_number_on_record" value="{{ $student->emergency_contact_home_number_on_record }}" readonly="1" class="form-control">
								    </div>

								    <div class="form-group col-md-6 col-lg-6">
									    <label>Address</label>
									    <textarea name="emergency_contact_address_on_record" readonly="1" class="form-control">{{ $student->emergency_contact_address_on_record }}</textarea>
								    </div>
					            </div>

				            </div>
				            <!-- End Emergency Contact Information -->
				        </div>
				        <!-- END FAMILY BACKGROUND -->

				        <!-- START MEDICAL HISTORY -->
			          	<div class="tab-pane" id="medical-history">
			            	<div class="panel panel-default" style="box-shadow: none;">

				            	<!-- Heading -->
					            <div class="panel-heading">
					            	<h3><strong>Medical History</strong></h3>
					            </div>

			              		<div class="panel-body">
			              			<!-- First Question -->
								    <div class="form-group col-md-12 col-lg-12" style="margin-bottom: 0;">
										<label style="margin-bottom: 0;"><strong>1. Does your child have any of the following?</strong></label>
									</div>

							      	<div class="form-group col-md-12 col-lg-12">
									    <label>Asthma</label>
									    <input type="text" name="asthma" value="{{ $student->asthma ? 'Yes' : 'No' }}" readonly="1" class="form-control">
								    </div>

								    <!-- Asthma Inhaler -->
								    @if($student->asthma)
									    <div class="form-group col-md-12 col-lg-12">
									    	<label>Does your child carry an asthma inhaler?</label>
									    	 <input type="text" name="asthmainhaler" value="{{ $student->asthmainhaler ? 'Yes' : 'No' }}" readonly="1" class="form-control">
									    </div>
								    @endif

								    <div class="form-group col-md-12 col-lg-12">
									    <label>Allergies</label>
									    <input type="text" name="allergy" value="{{ $student->allergy ? 'Yes' : 'No' }}" readonly="1" class="form-control">
								    </div>

								    <!-- Specific Allergies -->
								    @if($student->allergy)
									    <div class="form-group col-md-6 col-lg-6">
									    	<label>Specific Allergy(s)</label>
									    	<textarea name="allergies" readonly="1" class="form-control">{!! $student->allergies !!}</textarea>
									    </div>
								    @endif

								    <!-- Allergy Reaction -->
								    @if($student->allergy)
									    <div class="form-group col-md-6 col-lg-6">
									    	<label>Reaction</label>
									    	<textarea name="allergyreaction" readonly="1" class="form-control">{!! $student->allergyreaction !!}</textarea>
									    </div>
								    @endif

								    <div class="form-group col-md-12 col-lg-12">
									    <label>Drug Allergy</label>
									    <input type="text" name="drugallergy" value="{{ $student->drugallergy ? 'Yes' : 'No' }}" readonly="1" class="form-control">
								    </div>

								    <!-- Specific Drug Allergies -->
								    @if($student->drugallergy)
									    <div class="form-group col-md-6 col-lg-6">
									    	<label>Specific Drug Allergy(s)</label>
									    	<textarea name="drugallergies" readonly="1" class="form-control">{!! $student->drugallergies !!}</textarea>
									    </div>
								    @endif

								    <!-- Drug Allergy Reaction -->
								    @if($student->drugallergy)
									    <div class="form-group col-md-6 col-lg-6">
									    	<label>Reaction</label>
									    	<textarea name="allergyreaction" readonly="1" class="form-control">{!! $student->allergyreaction !!}</textarea>
									    </div>
								    @endif

								    <div class="form-group col-md-12 col-lg-12">
									    <label>Eye or vision problems</label>
									    <input type="text" name="visionproblem" value="{{ $student->visionproblem ? 'Yes' : 'No' }}" readonly="1" class="form-control">
								    </div>

								    <!-- Vision Problem Description -->
								    @if($student->visionproblem && $student->visionproblemdescription)
									    <div class="form-group col-md-12 col-lg-12">
									    	<label>Description</label>
									    	<textarea name="visionproblemdescription" readonly="1" class="form-control">{{ $student->visionproblemdescription }}</textarea>
									    </div>
								    @endif

								    <div class="form-group col-md-12 col-lg-12">
									    <label>Ear or hearing problems</label>
									    <input type="text" name="hearingproblem" value="{{ $student->hearingproblem ? 'Yes' : 'No' }}" readonly="1" class="form-control">
								    </div>

								    <!-- Hearing Problem Description -->
								    @if($student->hearingproblem && $student->hearingproblemdescription)
									    <div class="form-group col-md-12 col-lg-12">
									    	<label>Description</label>
									    	<textarea name="hearingproblemdescription" readonly="1" class="form-control">{{ $student->hearingproblemdescription }}</textarea>
									    </div>
								    @endif

								    <!-- Second Question -->
								    <div class="form-group col-md-12 col-lg-12">
									    <label>2. Any other health condition that the school should be aware of (e.g epilepsy, diabetes, etc.)</label>
									    <input type="text" name="hashealthcondition" value="{{ $student->hashealthcondition ? 'Yes' : 'No' }}" readonly="1" class="form-control">
								    </div>

								    <!-- Health Condition Summary -->
								    @if($student->hashealthcondition)
									    <div class="form-group col-md-12 col-lg-12">
									    	<label>Summary</label>
									    	<textarea name="healthcondition" readonly="1" class="form-control">{{ $student->healthcondition }}</textarea>
									    </div>
								    @endif

								    <!-- Third Question -->
								    <div class="form-group col-md-12 col-lg-12">
									    <label>3. Has your child recently been hospitalized?</label>
									    <input type="text" name="ishospitalized" value="{{ $student->ishospitalized ? 'Yes' : 'No' }}" readonly="1" class="form-control">
								    </div>

								    <!-- Hospitalized Summary -->
								    @if($student->ishospitalized)
									    <div class="form-group col-md-12 col-lg-12">
									    	<label>Summary</label>
									    	<textarea name="hospitalized" readonly="1" class="form-control">{{ $student->hospitalized }}</textarea>
									    </div>
								    @endif

								    <!-- Fourth Question -->
								    <div class="form-group col-md-12 col-lg-12">
									    <label>4. Has your child recently had any serious injuries?</label>
									    <input type="text" name="hadinjuries" value="{{ $student->hadinjuries ? 'Yes' : 'No' }}" readonly="1" class="form-control">
								    </div>

								    <!-- Injuries Summary -->
								    @if($student->hadinjuries)
									    <div class="form-group col-md-12 col-lg-12">
									    	<label>Summary</label>
									    	<textarea name="injuries" readonly="1" class="form-control">{{ $student->injuries }}</textarea>
									    </div>
								    @endif

								    <!-- Fifth Question -->
								    <div class="form-group col-md-12 col-lg-12">
									    <label>5. Is your child on a regular medication?</label>
									    <input type="text" name="medication" value="{{ $student->medication ? 'Yes' : 'No' }}" readonly="1" class="form-control">
								    </div>

								    <!-- Injuries Summary -->
								    @if($student->medication)
									    <div class="form-group col-md-12 col-lg-12">
									    	<label>Name of medication(s) and frequency</label>
									    	<textarea name="medications" readonly="1" class="form-control">{{ $student->medications }}</textarea>
									    </div>
								    @endif

								    <div class="form-group col-md-12 col-lg-12">
								    	<label>Does your child need to take any medication(s) during school hours?</label>
								    	<input type="text" name="schoolhourmedication" value="{{ $student->schoolhourmedication ? 'Yes' : 'No' }}" readonly="1" class="form-control">
								    </div>

								    <div class="form-group col-md-12 col-lg-12" style="margin-bottom: 0;">
										<label style="margin-bottom: 0;"><strong>I give consent for my child to receive the following:</strong></label>
									</div>

									<div class="form-group col-md-12 col-lg-12">
								    	<label>1. Minor first aid</label>
								    	<input type="text" name="firstaidd" value="{{ $student->firstaidd ? 'Yes' : 'No' }}" readonly="1" class="form-control">
								    </div>

								    <div class="form-group col-md-12 col-lg-12">
								    	<label>2. Emergency care</label>
								    	<input type="text" name="emergencycare" value="{{ $student->emergencycare ? 'Yes' : 'No' }}" readonly="1" class="form-control">
								    </div>

								    <div class="form-group col-md-12 col-lg-12">
								    	<label>3. Emergency care at the nearest hospital</label>
								    	<input type="text" name="hospitalemergencycare" value="{{ $student->hospitalemergencycare ? 'Yes' : 'No' }}" readonly="1" class="form-control">
								    </div>

								    <div class="form-group col-md-12 col-lg-12">
								    	<label>4. Oral non-prescription medication</label>
								    	<input type="text" name="oralmedication" value="{{ $student->oralmedication ? 'Yes' : 'No' }}" readonly="1" class="form-control">
								    </div>
			          			</div>

			        		</div>
			          	</div>
			          	<!-- /.END OF MEDICAL HISTORY -->

			          	<!-- START OTHER INFORMATION -->
			          	<div class="tab-pane" id="other-information">
			            	<div class="panel panel-default" style="box-shadow: none;">

				            	<!-- Heading -->
					            <div class="panel-heading">
					            	<h3><strong>Other Information</strong></h3>
					            </div>

			              		<div class="panel-body">

			              			<!-- Previous School -->
							      	<div class="form-group col-md-12 col-lg-12">
									    <label>Previous School</label>
									    <textarea name="previousschool" readonly="1" class="form-control">{!! $student->previousschool !!}</textarea>
								    </div>

								    <!-- Previous School Address -->
								    <div class="form-group col-md-12 col-lg-12">
									    <label>Complete address of the above School (including zip code)</label>
									    <textarea name="previousschooladdress" readonly="1" class="form-control">{!! $student->previousschooladdress !!}</textarea>
								    </div>

								    <!-- School(s) Attended Table -->
								    <div class="form-group col-md-12 col-lg-12">
									    <label>School(s) attended</label>
									    <table class="table table-striped" id="school_attended_table">
											<thead>
											    <tr>
											      	<th scope="col">Grade/Level (From)</th>
											      	<th scope="col">Grade/Level (Until)</th>
											      	<th scope="col">Name of School</th>
											      	<th scope="col">Year Attended</th>
											    </tr>
											</thead>
										  	<tbody>
										  		@if($student->schooltable)
										  			@if( count(json_decode($student->schooltable)) > 0 )
										  				@foreach(json_decode($student->schooltable) as $schooltable)
														    <tr>
														      	<td>{{ $schooltable->grade_level_from }}</td>
														      	<td>{{ $schooltable->grade_level_until }}</td>
														      	<td>{{ $schooltable->school_name }}</td>
														      	<td>{{ $schooltable->year_attended }}</td>
														    </tr>
													   	@endforeach
													@endif
											    @endif
										  	</tbody>
										</table>
								    </div>
								    <!-- End Of School(s) Attended Table -->

								    <!-- Reading and Writing Proficiency -->
								    <div class="form-group col-md-12 col-lg-12">
									    <label>Reading and Writing Proficiency</label>
									    <input type="text" name="readingwriting" value="{{ $student->readingwriting }}" readonly="1" class="form-control">
								    </div>

								    <!-- Verbal Proficiency -->
								    <div class="form-group col-md-12 col-lg-12">
									    <label>Verbal Proficiency</label>
									    <input type="text" name="verbalproficiency" value="{{ $student->verbalproficiency }}" readonly="1" class="form-control">
								    </div>

								    <!-- Major Language Used at Home -->
								    <div class="form-group col-md-12 col-lg-12">
									    <label>Major language used at home</label>
									    <input type="text" name="majorLanguage" value="{{ $student->majorLanguage }}" readonly="1" class="form-control">
								    </div>

								    <!-- Major Language Used at Home -->
								    <div class="form-group col-md-12 col-lg-12">
									    <label>Specify Other Language:</label>
									    <input type="text" name="other_language_specify" value="{{ $student->other_language_specify }}" readonly="1" class="form-control">
								    </div>

								    <!-- Specify Other Language -->
								    <div class="form-group col-md-12 col-lg-12" style="margin-bottom: 0;">
										<h5 style="margin-bottom: 0;"><strong>Other languages/dialects spoken</strong></h5>
									</div>

									<div class="form-group col-md-12 col-lg-12">
										<table class="table table-striped">
											<thead>
											    <tr>
											      	<th scope="col">List below</th>
											    </tr>
											</thead>
										  	<tbody>
										  		@if( $student->otherlanguages )
										  			@if( count($student->otherlanguages) > 0 )
										  				@foreach($student->otherlanguages as $language)
										  					@if( count($language) > 0 )
															    <tr>
															      	<td>{{ $language['languages'] }}</td>
															    </tr>
														    @endif
													   	@endforeach
													@endif
												@endif
										  	</tbody>
										</table>
									</div>

									<!-- Remedial Help Explanation -->
									<div class="form-group col-md-12 col-lg-12">
									    <label>Latest Testing Result</label>
									    <textarea name="remedialhelpexplanation" readonly="1" class="form-control">{!! $student->remedialhelpexplanation !!}</textarea>
								    </div>

								    <!-- Special Talent -->
								    <div class="form-group col-md-12 col-lg-12" style="margin-bottom: 0;">
										<h5 style="margin-bottom: 0;"><strong>Does your child have any special talent or interest in:</strong></h5>
									</div>

								    @if($student->specialtalent)
								    	@foreach(json_decode($student->specialtalent) as $specialtalentKey => $specialtalent)

								    		@if(isset($specialtalent->isChecked))
								    			@if(isset($specialtalent->sports))
								    				<div class="form-group col-md-12 col-lg-12">
											    		<div class="form-check">
															<input class="form-check-input" type="checkbox" value="" id="{{ $specialtalentKey }}" {{$specialtalent->isChecked ? 'checked' : ''}} disabled>
														  	<label class="form-check-label text-capitalize" for="defaultCheck1">
														    	{{ $specialtalentKey }}
														  	</label>
														</div>
														<textarea name="sports" readonly="1" class="form-control">{!! $specialtalent->sports !!}</textarea>
												    </div>

								    			@elseif(isset($specialtalent->instrument))
								    				<div class="form-group col-md-12 col-lg-12">
											    		<div class="form-check">
															<input class="form-check-input" type="checkbox" value="" id="{{ $specialtalentKey }}" {{$specialtalent->isChecked ? 'checked' : ''}} disabled>
														  	<label class="form-check-label text-capitalize" for="defaultCheck1">
														    	{{ $specialtalentKey }}
														  	</label>
														</div>
														<textarea name="sports" readonly="1" class="form-control">{!! $specialtalent->instrument !!}</textarea>
												    </div>
								    			@endif

								    		@else
								    			<div class="form-group col-md-12 col-lg-12">
										    		<div class="form-check">
														<input class="form-check-input" type="checkbox" value="" id="{{ $specialtalentKey }}" {{$specialtalent ? 'checked' : ''}} disabled>
													  	<label class="form-check-label text-capitalize" for="defaultCheck1">
													    	{{ $specialtalentKey }}
													  	</label>
													</div>
											    </div>
								    		@endif
								    	@endforeach
								    @endif
								    <!-- End Of Special Talent -->

								    <!-- Other Information -->
								    <div class="form-group col-md-12 col-lg-12">
									    <label>Are there any other information you think the teacher should know about your child?</label>
									    <input type="text" name="otherinfo" value="{{ $student->otherinfo ? 'Yes' : 'None' }}" readonly="1" class="form-control">
								    </div>

								    @if($student->otherinfo && $student->otherinfofield)
								    	<div class="form-group col-md-12 col-lg-12">
									    	<label>Other Information</label>
								    		<textarea name="sports" readonly="1" class="form-control">{!! $specialtalent->instrument !!}</textarea>
								    	</div>
								    @endif

								    <!-- Disiplinary Problem -->
								    <div class="form-group col-md-12 col-lg-12">
									    <label>Has your child ever been asked to leave school because of any behavioral/disciplinary problems?</label>
									    <input type="text" name="otherinfo" value="{{ $student->disciplinaryproblem ? 'Yes' : 'No' }}" readonly="1" class="form-control">
								    </div>

								    @if($student->disciplinaryproblem && $student->disciplinaryproblemexplanation)
								    	<div class="form-group col-md-12 col-lg-12">
									    	<label>Explanation</label>
								    		<textarea name="sports" readonly="1" class="form-control">{!! $specialtalent->disciplinaryproblemexplanation !!}</textarea>
								    	</div>
								    @endif

								   
			          			</div>

			        		</div>
			          	</div>
			          	<!-- /.END OF OTHER INFORMATION -->

			          	<!-- START ENROLLMENTS -->
			          	<div class="tab-pane" id="enrollments">
			            	<div class="panel panel-default" style="box-shadow: none;">

				            	<!-- Heading -->
					            <div class="panel-heading">
					            	<h3><strong>Enrollments</strong></h3>
					            </div>

			              		<div class="panel-body">
					              	<div class="box">
							          	<div class="box-body">
								            <table class="table table-sm table-bordered">
								              	<thead>
									                <th>School Year</th>
									                <th>Department</th>
									                <th>Year Level</th>
									                <th>Track</th>
									                <th>Term</th>
									                <th>Tuition</th>
									                <th>Commitment Payment</th>
									                <th>Action</th>
								              	</thead>
								              	<tbody>
								              		@if($student->enrollments)
										                @foreach($student->enrollments as $enrollment)
										                  	<tr>
											                    <td>{{ $enrollment->school_year_name }}</td>
											                    <td>{{ $enrollment->department_name }}</td>
											                    <td>{{ $enrollment->level_name }}</td>
											                    <td>{{ $enrollment->track_name ?? '-' }}</td>
											                    <td>{{ $enrollment->term_type }}</td>
											                    <td>{{ $enrollment->tuition_name }}</td>
											                    <td>{{ $enrollment->payment_method_name }}</td>
											                    <td>
											                    	<div class="dropdown" style="display: initial;">
																		<a href="#" class="btn btn-xs btn-default dropdown-toggle text-primary pl-1 action-btn" data-toggle="dropdown" title="More" id="dropdownMenu{{ $enrollment->id }}" aria-haspopup="true" aria-expanded="false">
																		    {{-- More --}} <i class="fa fa-caret-down"></i>
																		</a>
																		<ul class="dropdown-menu" aria-labelledby="dropdownMenu{{ $enrollment->id }}" style="right: 0 !important; left: auto !important;">
																			<li>
																				<a href="{{ url('admin/enrollment/' . $enrollment->id . '/tuition') }}" class="text-sm" target="_blank">View Tuition</a>
																			</li>
																		</ul>
																	</div>
											                      	{{-- <a href="{{ url()->current() . '/tuition/' . $enrollment->id}}" class="btn btn-sm btn-primary">View Tuition</a>
											                      	<a href="{{ url()->current() . '/' . $enrollment->id . '/grade' }}" class="btn btn-sm btn-primary">View Grades</a> --}}
											                    </td>
										                  	</tr>
										                @endforeach
									                @endif
								             	</tbody>
								            </table>
							    
							          	</div>
							        </div>
			          			</div>

			        		</div>
			          	</div>
			          	<!-- END OF ENROLLMENTS -->
		    		</div>
		        <!-- /.tab-content -->
		      	</div>

	      	</div>
		</div>

	</div>
@endsection

@section('after_styles')
  <link href="https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap.min.css" rel="stylesheet" type="text/css" />
  <link rel="stylesheet" href="https://cdn.datatables.net/fixedheader/3.1.5/css/fixedHeader.dataTables.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.1/css/responsive.bootstrap.min.css">

  <link rel="stylesheet" href="{{ asset('vendor/backpack/crud/css/crud.css') }}">
  <link rel="stylesheet" href="{{ asset('vendor/backpack/crud/css/form.css') }}">
  <link rel="stylesheet" href="{{ asset('vendor/backpack/crud/css/list.css') }}">
@endsection

@push('after_scripts')
	<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
  	<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap.min.js"></script>

  	<!-- <script>
	    $('.table').DataTable({
	      	"processing": true,
	      	"paging": false,
	      	"searching": true,
	    });
	    $(".dataTables_filter").css("float", "right");
	    $('input[type="search"]').css("border-radius", "15px");
  	</script> -->
@endpush
