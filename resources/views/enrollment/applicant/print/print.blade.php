<!DOCTYPE html>	
	<head>
		<title>{{Config::get('settings.schoolname')}} | Student Form</title>
	    <meta name="viewport" content="width=device-width">
	    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		<style>
			@include('bootstrap4')
			
			table td, table th {
				border: 0 !important;
				padding: 0px !important;
			}

			table thead th {
				padding: 5px !important;
			}

			table.profile tr td{font-size: 11px;}
			table.profile tr td:first-child{font-weight: 900;}
			table.profile tr td:nth-child(4){font-weight: 900;}


			table.profile tr td {width: 25%;}
			table.profile {margin-bottom: 0px;}
			/*table.profile {border: 0.5px solid #ddd;}*/
			table.profile thead th {
				padding: 5px !important;
			}

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
			.box {
				display: inline-block;  
				width: 7px; 
				height: 7px; 
				margin-right: 5px; 
				border: 1px solid #ddd;
				padding-top: 0px;
			}


			body {
	            font-size: 10px;
	            margin-bottom: 50px !important;
	            margin-top: 130px !important;
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
		</style>
	</head>

	<body>
		<header>
            <center>    
                <img width="50" src="{{ Config::get('settings.schoollogo') }}" alt="IMG" align="center" style="">
                <p class="text-uppercase mb-0" style="font-size: 12px;"><b>{{ Config::get('settings.schoolname') }}</b></p>
                <p><small>{{ Config::get('settings.schooladdress') }}</small></p>
            </center>
            <center class="text-uppercase">
                <b>Application Form</b>
                <p><small>(System Generated)</small></p>
            </center>
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

		<!-- Content -->
        <main>
			<div class="col-12 m-0 p-0" style="margin-bottom: 0px !important; padding-bottom: 0px !important;">
				<div class = "col-8 m-0 p-0">
						<table class="table">
							<tbody>
								<tr>
									<th>Applicant For:</th>
								</tr>
								<tr>
									<td style="">School Year:</td>
									<th>{{ $enrollment->schoolYear ? $enrollment->schoolYear->schoolYear : '-' }}</th>
								</tr>
								<tr>
									<td>Department:</td>
									<th class="text-capitalize">{{ $enrollment->department ? $enrollment->department->name : '-' }}</th>
								</tr>
								<tr>
									<td>Level:</td>
									<th class="text-capitalize">{{ $enrollment->level ? $enrollment->level->year : '-' }}</th>
								</tr>
								@if($enrollment->track !== null)
									<tr>
										<td>Track</td>
										<th>{{ $enrollment->track->code }}</th>
									</tr>
								@endif
							</tbody>
						</table>
						
				</div> <!-- .profilediv -->
				<div class="profilediv col-4 m-0 p-0 float-right text-center align-middle" style="align-content: center; height: 192px; width: 192px; top: -115px; padding: auto; border-radius: 0;">
					<p style="padding-top: 95px;">Attach 2x2 Picture</p>
				</div>
				
				{{-- STUDENT INFORMATION }} --}}
				@include('enrollment.applicant.print.student_info')

				{{-- PERSONAL INFORMATION }} --}}
				@include('enrollment.applicant.print.student_personal_information')

				{{-- FAMILY BACKGROUND }} --}}
				@include('enrollment.applicant.print.family_background')
				
				{{-- EMERGENCY CONTACT INFORMATION }} --}}
				@include('enrollment.applicant.print.emergency_contact_info')

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
							{{-- <tr>
								<td>Inclusive Dates:</td>
								<td>{{ $student->inclusive_dates }}</td>
							</tr> --}}
						</tbody>
					</table>
					@endif

				</div> <!-- .profilediv -->
				 {{-- END OF FOR TRANSFEREES }} --}}
				
				<div class = "row" style="padding-bottom: 0px !important; margin-bottom: 0px !important;">
					<div class="col-6" style="margin-right: 50%; padding-bottom: 0px !important; margin-bottom: 0px !important;">
						<table class="table" style="padding-bottom: 0px !important; margin-bottom: 0px !important;">
							<thead>
								<tr>
									<th colspan="4" 
									scope="col"
										style="background-color: #CCC">
										To be checked by the Admission Staff
									</th>
								</tr>
								<tr>
									<td style="width: 100%;"> The applicant presented the following documents:</td>
								</tr>
								<tr>
									<td><span class="box"></span> Form 138</td>
								</tr>
								<tr>
									<td><span class="box"></span> Certificate of Good Moral</td>
								</tr>
								<tr>
									<td><span class="box"></span> PSA Birth Certificate</td>
								</tr>
								<tr>
									<td><span class="box"></span> ESC Certificate</td>
								</tr>
								<tr>
									<td><span class="box"></span> Recommendation Letter</td>
								</tr>
								<tr>
									<td><span class="box"></span> Transcript of Records</td>
								</tr>
								<tr class="signature-over-printed-name mt-0" style="position: relative;">
                                    <td class="mt-4 text-capitalize">
                                        Checked by: _____________________________________
                                    </td>
                                </tr>
                                <tr class="signature-over-printed-name mt-0" style="position: relative;">
                                    <td class="text-capitalize">
                                        Date: ___________________________________________
                                    </td>
                                </tr>
                                <tr class="mt-0" style="position: relative;">
                                    <td class="text-capitalize pt-3" style="width: 100%;">
                                    	<div style="border: 1px solid #ddd; height: 50px; padding: 10px; width: 100%;">
                                        Remarks:
                                    	</div>
                                    </td>
                                </tr>
							</thead>
							<tbody>
								

							</tbody>
						</table>
					</div>
					
					<div class="col-6" style="margin-left: 50%; padding-bottom: 0px !important; margin-bottom: 0px !important; height: 0px !important;">
						<table class="table" style="padding-bottom: 0px !important; margin-bottom: 0px !important;">
                            <tbody>
                                <tr>
                                    <td class="text-justify">
                                        I hereby confirm that the information stated above are true
										and correct. I understand that by signing this
										information/application form, I hereby give the school and
										SchoolMATE to collect, record, organize, update, or
										modify, retrieve, consult, utilize, consolidate, block, erase,
										or destruct my personal data as part of my information for
										historical, statistical, research and evaluation purposes
										pursuant to the provisions of the Republic Act No. 10173 of 
										the Philippines, Data Privacy Act of 2012 and its
										corresponding implementing Rules and Regulations.
                                    </td>
                                </tr>
                                <tr class="signature-over-printed-name mt-0" style="position: relative;">
                                    <td class="mt-4 text-capitalize">
                                        <b>{{ strtolower($student->fullname) }}</b>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Signature Over Printed Name
                                    </td>
                                </tr>
                                 <tr>
                                    <td class="mt-4 text-capitalize">
                                        <b>Date:</b>
                                    </td>
                                </tr>
                            </tbody>
                            
                        </table>
					</div>
				</div> <!-- .profilediv -->
		    </div>
	    </main>	
		
	</body>
</html>