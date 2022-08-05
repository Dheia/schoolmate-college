<!DOCTYPE html>	
<head>
	<title>{{Config::get('settings.schoolname')}} | Enrolment Form</title>
	<meta charset="utf-8" />
    <meta name="viewport" content="width=device-width">


	<style>
		@include('bootstrap4')
		
		table td, table th {
			border: 0 !important;
			padding: 3px !important;
		}

		table.profile tr td{font-size: 11px;}
		table.profile tr td:first-child{font-weight: 900;}
		table.profile tr td:nth-child(4){font-weight: 900;}


		table.profile tr td {width: 25%;}
		table.profile {margin-bottom: 0px;}
		/*table.profile {border: 0.5px solid #ddd;}*/


		.profilediv {
			border: 1px solid #ddd;
			border-radius: 5px;
			margin-bottom: 10px;
			padding: 5px;
		}

		.signature-over-printed-name p {
			font-size: 9px;
			/*font-weight: 700;*/
		}

		footer { 
			position: fixed;
			/*border: 1px solid black; */
			bottom: -0px; 
			/*right: 0px; */
			height: 50px;
			font-size: 10px;

		}

	
	</style>


</head>
<body>


	<div class="col-12 m-0 p-0">
		<center>	
			<img width="50" src="{{ Config::get('settings.schoollogo') }}" alt="IMG" align="center" style="">
			<p class="text-uppercase mb-0"><b>{{ Config::get('settings.schoolname') }}</b></p>
			<p><small>{{ Config::get('settings.schooladdress') }}</small></p>

			<p class="text-uppercase mt-5 mb-4"><b>Enrollment Form</b></p>
		</center>

		
		<div class = "profilediv">
				<table class="profile table">
					<tbody>
						<tr>
							<td>School Year:</td>
							<td>{{ $student->schoolYear->schoolYear }}</td>
							<td>Application Date:</td>
							<td>{{ \Carbon\Carbon::parse($student->application)->format('F d, Y') }}</td>
						</tr>
						<tr>
							<td>Level:</td>
							<td>{{ $student->yearManagement->year }}</td>
							<td>Status:</td>
							<td class="text-capitalize">{{ $student->is_enrolled }}</td>
						</tr>
						@if($student->track !== null)
							<tr>
								<td>Track</td>
								<td>{{ $student->track->code }}</td>
							</tr>
						@endif
					</tbody>
				</table>
		</div> <!-- .profilediv -->
	
		 {{-- STUDENT INFORMATION }} --}}
		<div class = "profilediv">
			<table class="profile table">
				<tbody>
					<tr>
						<td colspan="4" 
							class="text-uppercase text-center"
							style="background-color: #CCC">
							Student Information
						</td>
					</tr>
					<tr>
						<td>LRN:</td>
						<td class="text-capitalize" colspan="3">{{ strtolower($student->lrn) }}</td>
					</tr>
					
					<tr>
						<td>Last Name:</td>
						<td class="text-capitalize">{{ strtolower($student->lastname) }}</td>
						<td>Middle Name:</td>
						<td class="text-capitalize">{{ strtolower($student->middlename) }}</td>
					</tr>
					<tr>
						<td>First Name:</td>
						<td class="text-capitalize">{{ strtolower($student->firstname) }}</td>
						<td>Gender:</td>
						<td class="text-capitalize">{{ $student->gender }}</td>
					</tr>
					<tr>
						<td>Date Of Birth:</td>
						<td>{{ \Carbon\Carbon::parse($student->birthdate)->format('F d, Y') }}</td>
						<td>Age:</td>
						<td>{{ $student->age }}</td>
					</tr>
					<tr>
						<td>Place Of Birth:</td>
						<td class="text-capitalize">{{ strtolower($student->birthplace) }}</td>
						<td>Citizenship:</td>
						<td class="text-capitalize">{{ strtolower($student->citizenship) }}</td>
					</tr>
					<tr>
						<td>Religion:</td>
						<td class="text-capitalize">{{ strtolower($student->religion) }}</td>
						<td>Email Address:</td>
						<td>{{ $student->email }}</td>
					</tr>
					<tr>
						<td>Residential Address:</td>
						<td class="text-capitalize" colspan="3">{{ strtolower($student->street_number) . " " . strtolower($student->barangay). " " . strtolower($student->city_municipality . " " .strtolower($student->province))}}</td>
						
					</tr>
					

				</tbody>
			</table>
		</div> <!-- .profilediv -->
		 {{-- END OF STUDENT INFORMATION }} --}}


		 {{-- FATHER'S INFORMATION }} --}}
		<div class = "profilediv">
			<table class="profile table">
				<tbody>
					<tr>
						<td colspan="4" class="text-uppercase text-center" style="background-color: #CCC">
							Father's Information
						</td>
					</tr>

					<tr>
						<td>Last Name:</td>
						<td class="text-capitalize">{{ strtolower($student->fatherlastname) }}</td>
						<td>Middle Name:</td>
						<td class="text-capitalize">{{ strtolower($student->fathermiddlename) }}</td>
					</tr>
					<tr>
						<td>First Name:</td>
						<td class="text-capitalize">{{ strtolower($student->fatherfirstname) }}</td>
						<td>Occupation:</td>
						<td class="text-capitalize">{{ strtolower($student->father_occupation) }}</td>
					</tr>
					<tr>
						<td>Mobile No.:</td>
						<td>{{ $student->fatherMobileNumber }}</td>
						<td>Deceased?</td>
						<td>
							{{ $student->father_living_deceased === 'deceased' ? 'Yes' : 'No' }}
						</td>
					</tr>

				</tbody>
			</table>
		</div> <!-- .profilediv -->
		 {{-- END OF FATHER'S INFORMATION }} --}}
		

		
		 {{-- MOTHER'S INFORMATION }} --}}
		<div class = "profilediv">
			<table class="profile table">
				<tbody>


					<tr>
						<td colspan="4" class="text-uppercase text-center" style="background-color: #CCC">
							Mother's Information
						</td>
					</tr>

					<tr>
						<td>Last Name:</td>
						<td class="text-capitalize">{{ strtolower($student->motherlastname) }}</td>
						<td>Middle Name:</td>
						<td class="text-capitalize">{{ strtolower($student->mothermiddlename) }}</td>
					</tr>
					<tr>
						<td>First Name:</td>
						<td class="text-capitalize">{{ strtolower($student->motherfirstname) }}</td>
						<td>Occupation:</td>
						<td class="text-capitalize">{{ strtolower($student->mother_occupation) }}</td>
					</tr>
					<tr>
						<td>Mobile No.:</td>
						<td >{{ $student->mothernumber }}</td>
						<td>Deceased?</td>
						<td>
							{{ $student->mother_living_deceased === 'deceased' ? 'Yes' : 'No' }}
						</td>
					</tr>

				</tbody>
			</table>
		</div> <!-- .profilediv -->
		 {{-- END OF MOTHER'S INFORMATION }} --}}



		 {{-- EMERGENCY CONTACT INFORMATION }} --}}
		<div class = "profilediv">
			<table class="profile table">
				<tbody>

					<tr>
						<td colspan="4" class="text-uppercase text-center" style="background-color: #CCC">Emergency Contact Information</td>
					</tr>

					<tr>
						<td>Full Name:</td>
						<td class="text-capitalize">{{ strtolower($student->emergencycontactname) }}</td>
					</tr>
					<tr>
						<td>Relationship To The Student:</td>
						<td class="text-capitalize">{{ strtolower($student->emergencyRelationshipToChild) }}</td>
					</tr>
					<tr>
						<td>Mobile No.</td>
						<td>{{ $student->emergencymobilenumber }}</td>
					</tr>

				</tbody>
			</table>
		</div> <!-- .profilediv -->
		 {{-- END OF EMERGENCY CONTACT INFORMATION }} --}}



		 {{-- FOR TRANSFEREES }} --}}
		@if($student->is_transferee)
		<div class = "profilediv">

			<table class="profile table">
				<tbody>

					<tr>
						<td colspan="4" class="text-uppercase text-center" style="background-color: #CCC">For Transferees</td>
					</tr>

					<tr>
						<td>Last School Attended:</td>
						<td class="text-capitalize">{{ strtolower($student->previousschool) }}</td>
					</tr>
					<tr>
						<td>Inclusive Dates:</td>
						<td>{{ $student->inclusive_dates }}</td>
					</tr>

				</tbody>
			</table>
			@endif

		</div> <!-- .profilediv -->
		 {{-- END OF FOR TRANSFEREES }} --}}
		
		 <div class="signature-over-printed-name mt-5 float-right" style="position: relative;">
		 	<p class="mb-0 mt-0 pb-0 mt-0 text-capitalize" style="">{{ strtolower($student->fullname) }}</p>
		 	<p class="mb-0 mt-0 pb-0 mt-0"><b style="border-top: 1px solid #000;">Signature Over Printed Name</b></p>
		 </div>

    </div>          
	
	
	<footer>
		<center>
			<img width="40" src="images/schoolmate_logo.jpg" alt="schoolmate_logo">
		</center>
		<center>
			<p class="mb-0">Copyright &copy; 2019</p>
			<p class="pt-0">Powered by: Tigernet Hosting and IT Services</p>
		</center>
	</footer>	
	
</body>
</html>