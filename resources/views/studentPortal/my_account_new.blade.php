@extends('backpack::layout_student')

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

	.form-control {
		border-radius: .25rem;
	}

	.form-group.required label:not(:empty)::after {
	    content: ' *';
	    color: #ff0000;
	}
  </style>
@endsection

@php
	$paymentError  = false;
	$passwordError = false;

	if($errors->count()) {
		$paymentError  = $errors->has('amount') || $errors->has('email') || $errors->has('description') || $errors->has('payment_method_id');
		$passwordError = $errors->has('old_password') || $errors->has('new_password') || $errors->has('confirm_password');
	}
@endphp

@section('content')
	<!-- HEADER START -->
  	<div class="row" style="padding: 15px;">
    	<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 smo-search-group"> 
			<section class="content-header">
			  <ol class="breadcrumb">
			    <li><a href="{{ url( '/student/dashboard') }}">Student</a></li>
			    <li class="active">Dashboard</li>
			  </ol>
			</section>
    	</div>
    </div>
    <!-- HEADER END -->

    <!-- ENROLLMENTS INFORMATION START -->
    @if(count($enrollments)>0)
	    <div class="row p-l-10 p-r-10">
		    <div class="box">
		    	<div class="box-header">
		    		<h3><strong>Enrollments Information and Balances</strong></h3>
		    	</div>
		        <div class="box-body">
		        	<div class="table-responsive">
			            <table class="table table-sm table-bordered">
			              	<thead>
				                <th>School Year</th>
				                <th>Department</th>
				                <th>Year Level</th>
				                <th>Track</th>
				                <th>Term</th>
				                <th>Tuition</th>
				                <th>Commitment Payment</th>
				                <th>Balance</th>
				                <th>Action</th>
			              	</thead>
			              	<tbody>
				                @foreach($enrollments as $enrollment)
				                  	<tr>
					                    <td>{{ $enrollment->schoolYear->schoolYear }}</td>
					                    <td>{{ $enrollment->department->name }}</td>
					                    <td>{{ $enrollment->level->year }}</td>
					                    <td>{{ $enrollment->track->code ?? '-' }}</td>
					                    <td>{{ $enrollment->term_type }}</td>
					                    <td>{{ $enrollment->tuition->form_name }}</td>
					                    <td>{{ $enrollment->commitmentPayment->name }}</td>
					                    <td>
					                    	<b style="{{ $enrollment->remaining_balance > 0 ? 'color: red;' : '' }}">
						                     	<!-- Peso Sign (&#8369;) -->
						                     	&#8369; {{ number_format((float)$enrollment->remaining_balance, 2) }}
						                     </b>
					                    </td>
					                    <td>
					                    	@if($enrollment->invoice_no)
					                    		<a id="btnPay-{{ $enrollment->id }}" href="javascript:void(0)" data-id="{{ $enrollment->id }}" data-backdrop="static" data-keyboard="false" data-sy="{{ $enrollment->school_year_id }}" data-amount="{{ $enrollment->remaining_balance }}" class="btn btn-success btn-sm" data-toggle="modal" data-target="#paymentModal">Pay Online</a>
					                    	@endif
					                    </td>
				                  	</tr>
				                @endforeach
				                <!--TOTAL BALANCE -->
				        		@php $total_balance = $enrollments->sum('remaining_balance'); @endphp
			                	<tr>
			                		<td colspan="7" class="text-right"><b>Total: </b></td>
			                		<td colspan="2">
			                			<b style="{{ $total_balance > 0 ? 'color: red;' : '' }}">&#8369; {{ number_format((float)$total_balance, 2) }}</b>
			                		</td>
			                	</tr>
			              	</tbody>
			            </table>
			        </div>
		        </div>
		    </div>
		</div>
    @endif
    <!-- ENROLLMENTS INFORMATION END -->

	<div class="row p-l-10 p-r-10">

		<!-- RIGHT SIDEBAR -->
		<div class="col-md-3 col-lg-3 col-two">
			<!-- Profile Image -->
	      	<div class="box box-primary" style="border-radius: 5px; border-top: 3px solid #d2d6de;">
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

	      	<!-- About Me Box -->
	       	<div class="box box-primary" style="border-radius: 5px; border-top: 3px solid #d2d6de;">
		        <div class="box-header with-border">
	          		<h3 class="box-title">About Me</h3>
		        </div>
		        <!-- /.box-header -->
		        <div class="box-body">
	          		<strong><i class="fa fa-mobile margin-r-5"></i> Contact No.</strong>
	          		<p class="text-muted">{{ $student->contactnumber ?? '-' }}</p>
	          		<hr>
	          		<strong><i class="fa fa-map-marker margin-r-5"></i> Address</strong>
	          		<p class="text-muted">{{ $student->residentialaddress ?? '-' }}</p>
	          		<hr>
	          		<strong><i class="fa fa-birthday-cake margin-r-5"></i> Birthday</strong>
	          		<p class="text-muted">{{ date('F j, Y', strtotime($student->birthdate)) }}</p>
		        </div>
		        <!-- /.box-body -->
	      	</div> 

		</div>

		<div class="col-md-9 col-lg-9 col-one">
			<div class="box" style="border-radius: 10px;">

	      		<div class="nav-tabs-custom" style="border-radius: 10px;">
	        		<div class="box-header">
	          			<ul class="nav nav-pills">
				            <li class="@if(!$passwordError) active @endif">
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
				            	<a href="#requirement" data-toggle="tab" aria-expanded="false">
				            		<strong>Requirement</strong>
				            	</a>
				            </li>
            				<li class="@if($passwordError) active @endif">
            					<a href="#change-password" data-toggle="tab" aria-expanded="false">
            						<strong>Change Password</strong>
            					</a>
            				</li>
	           			</ul>
	       			</div>
	        		
	        		<div class="tab-content" style="border-radius: 0;">

			          	<!-- START STUDENT INFORMATION -->
			          	<div class="tab-pane @if(!$passwordError) active @endif" id="student-information">
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

				        <!-- START UPLOAD REQUIREMENTS -->
				        <div class="tab-pane" id="requirement">
				            <div class="panel panel-default" style="box-shadow: none; border-top: none;">
				              <div class="panel-heading"> <h3 class="hh3"><strong>Requirements</strong></h3></div>
				              <div class="panel-body">
				                <form id="requirementForm" role="form" method="POST" action="{{ url('student/upload-requirements') }}"  style="width: 100%;" enctype="multipart/form-data">
				                  {{-- UPDATE REQUIREMENTS --}}
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
				                  {{-- // UPDATE REQUIREMENTS --}}
				                </form>
				              </div>
				            </div>
				        </div>
				        <!-- END UPLOAD REQUIREMENTS -->


				        <!-- START CHANGE PASSWORD -->
				        <div class="tab-pane @if($passwordError) active @endif" id="change-password">
				            <form class="form" action="{{url('student/change-password/submit')}}" method="post">
				                {!! csrf_field() !!}
				              	<div class="panel panel-default" style="box-shadow: none; border-top: none;">
					                <div class="panel-heading"><h3><strong>Change Password</h3></div>
					                <div class="panel-body">
					                    @if ($errors->count() && $passwordError)
			                      		<div class="alert alert-danger">
				                          	<ul>
			                              		@foreach ($errors->all() as $e)
				                              	<li>{{ $e }}</li>
				                              	@endforeach
			                          		</ul>
				                      	</div>
					                    @endif
					                    <div class="form-group">
					                        <label class="required">Old password</label>
					                        <input class="form-control" type="password" name="old_password" id="old_password" value="" placeholder="Old password">
					                    </div>
					                    <div class="form-group">
					                        <label class="required">New password</label>
					                        <input class="form-control" type="password" name="new_password" id="new_password" value="" placeholder="New password">
					                    </div>
					                    <div class="form-group">
					                        <label class="required">Confirm password</label>
					                        <input class="form-control" type="password" name="confirm_password" id="confirm_password" value="" placeholder="Confirm password">
					                    </div>
					                </div>
					                <div class="panel-footer">
					                  <button type="submit" class="btn btn-success"><span class="ladda-label"><i class="fa fa-save"></i> {{ trans('backpack::base.change_password') }}</span></button>
					                      <a href="{{ url('student/dashboard') }}" class="btn btn-default"><span class="ladda-label">{{ trans('backpack::base.cancel') }}</span></a>
					                </div>
				              	</div>
				            </form>
				        </div>
				        <!-- END CHANGE PASSWORD -->
		    		</div>
		        <!-- /.tab-content -->
		      	</div>

	      	</div>
		</div>

	</div>

	<!-- Payment Modal -->
	<div class="modal fade" id="paymentModal" tabindex="-1" role="dialog" aria-labelledby="paymentModalLabel" aria-hidden="true">
	  	<div class="modal-dialog" role="document">
		    <div class="modal-content" style="border-radius: .50rem;">
		      	<div class="modal-header text-center">
			        <h3 class="modal-title" id="paymentModalLabel" style="color: #0e6ea6;">Online Payment</h3>
			       <!--  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
			          <span aria-hidden="true">&times;</span>
			        </button> -->
		      	</div>
		      	<div class="modal-body">
		      		@if ($errors->count() && $paymentError)
                      	<div id="form-error" class="alert alert-danger">
                          	<ul>
                              	@foreach ($errors->all() as $e)
                              		<li>{{ $e }}</li>
                              	@endforeach
                          	</ul>
                      	</div>
                    @endif
		        	<form id="paymentForm" role="form" method="POST" action="{{ url('student/online-payment') }}">
	        			@csrf

	        			<input type="hidden" id="enrollment_id" name="enrollment_id">
	        			<input type="hidden" id="school_year_id" name="school_year_id">
	        			<input type="hidden" id="studentnumber" name="studentnumber" value="{{ $student->studentnumber }}">

	        			<!-- Amount -->
					  	<div class="form-group required">
					  		<label for="amount">Amount</label>
							<input class="form-control" type="number" id="amount" name="amount" value="{{ old('amount') }}" placeholder="Amount" autocomplete="off">
							<span class="fee float-right">Fee: <span class="amount-fee">0</span></span>
						</div>

						<!-- Email -->
						<div class="form-group required">
							<label for="email">Email</label>
							<input class="form-control" type="text" name="email" value="{{ old('email') }}" placeholder="E-mail">
						</div>

						<!-- Description -->
						<div class="form-group required">
							<label for="description">Description</label>
							<textarea class="form-control" type="text" name="description" placeholder="Description">{{ old('description') }}</textarea>
						</div>

						<!-- Payment Method -->
						<div class="form-group required" style="margin-bottom: 0;">
							<label for="payment_method_id">Payment Method</label>
							<select class="payment-method form-control" style="outline: none;" name="payment_method_id" id="payment_method_id">
								@if( (env('PAYMENT_GATEWAY') !== null || env('PAYMENT_GATEWAY') !== "") && strtolower(env('PAYMENT_GATEWAY')) == "paynamics")
									<option selected disabled>Select Payment Method</option>
									@foreach($paymentMethods as $paymentMethod)
										@if(strtolower($paymentMethod->name) === "cash")
										@else
											<option value="{{ $paymentMethod->id }}" {{ $paymentMethod->id == old("payment_method_id") ? 'selected' : '' }} 
													fee="{{ $paymentMethod->fee }}" 
													fixed-amount="{{ $paymentMethod->fixed_amount }}">
													{{ $paymentMethod->name }}
											</option>
										@endif
									@endforeach
								@else
									@foreach($paymentMethods as $paymentMethod)
										@if(strtolower($paymentMethod->name) === "paypal")
											<option value="{{ $paymentMethod->id }}">{{ $paymentMethod->name }}</option>
										@endif
									@endforeach
								@endif
				            </select>
						</div>
						@if(config('settings.paymentnotes') !== '')
							<span style="color: orange; font-size: 11px;">NOTE: 
								<span>{{ config('settings.paymentnotes') }}</span>
							</span>
						@endif
		        	</form>
		      	</div>
		      	<div class="modal-footer">
			        <button id="cancel" type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
			        <button id="submitPayment" type="button" class="btn" style="color: #fff; background-color: #007bff; border-color: #007bff;">
			        	Make Payment
			        </button>
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

  	<script>

  		@if($errors->count())
  			@if($paymentError)
	  			$('#paymentModal').modal('show');
	  			getFee();
	  		@endif
  		@endif

  		$('#paymentModal').on('show.bs.modal', function (event) {
		  	var button 			= $(event.relatedTarget);
		  	var enrollment_id 	= button.data('id');
		  	var school_year_id  = button.data('sy');
		  	var amount 			= button.data('amount');

		  	@if($errors->count())
		  		var old_enrollment_id  = '{{ old('enrollment_id')  }}';
		  		var old_school_year_id = '{{ old('school_year_id')  }}';

		  		if(enrollment_id == old_enrollment_id) {
		  			enrollment_id 	= old_enrollment_id;
	    			school_year_id 	= old_school_year_id;
		  		} else {
		  			$('#form-error').remove();
		  		}

	    	@endif

			$('#enrollment_id').val(enrollment_id);
			$('#school_year_id').val(school_year_id);
			$('#amount').val(amount);
		  	var modal = $(this);
		});

		var paymentMethods  = {!! json_encode($paymentMethods) !!};

		var select_payment_method      = $('select[name="payment_method_id"]');
    	var payment_method_id         = select_payment_method.find('option:selected').val();

    	select_payment_method.change(function () {
	        getFee();
	    });

	    $('input[name="amount"]').keyup(function () { getFee(); });
	    $('#submitPayment').click(function () { submitPayment(); });

	    function submitPayment () {
	    	$('#paymentForm').submit();
	    }

		function getFee () {
	        // payment_method_id = select_payment_method.find('option:selected').val();
	        // $.each(paymentMethods, function (k, val) {
	        //     if(payment_method_id == val.id) {
	        //     	$('.amount-fee').text(val.fee);
	        //     }
	        // });

	        var amount 				= $('input[name="amount"]');
			select_payment_method 	= $('select[name="payment_method_id"]');

			var fee 		= select_payment_method.find('option:selected').attr('fee');
			var fixedAmount = select_payment_method.find('option:selected').attr('fixed-amount');

			fee = typeof fee === "undefined" ? 0 : parseFloat(fee);
			fixedAmount = typeof fixedAmount === "undefined" ? 0 : parseFloat(fixedAmount);

			if(isNaN(amount.val())) {
				$('.amount-fee').text( Intl.NumberFormat('en', {style: 'currency', currency: 'PHP'}).format(fixedAmount));
				return;
			}

			$('.amount-fee').text( Intl.NumberFormat('en', {style: 'currency', currency: 'PHP'}).format(  ( amount.val() * (fee/100) ) +  fixedAmount) );
	    }
  	</script>
@endpush