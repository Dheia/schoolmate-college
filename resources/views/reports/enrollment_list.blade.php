@php
	// echo json_decode($student[0]->living,true)['step-mother'];
@endphp
<head>

	<style type="text/css">
		body{
			font-family: helvetica;
			font-size: 10px;
		}
		.border-red{
			border: 0px solid red;
		}
		.border-black{
			border: 0px solid grey;
		}
		.width-30p{
			width: 70%;
		}
		.center-align{
			text-align: center;
		}

		td,tr,table{
			border: 0px solid grey;
			margin: 0px;
		}
		div{
			border: 0px solid red;
		}

		th {
			text-align: center;
			color: white;
			border: 0px solid white;
			background-color: gray;
		}

		.border1pxsolid{
			border: 0px solid #2c063f;
		}
		.page-break {
		    page-break-after: always;
		}
		.header {
			
	        /** Extra personal styles **/
	        text-align: center;
		}
		
		.enrollmentlist {
			border: 0px;
			margin-bottom: 10px;
		}
		.department_info {
			margin-top: 15px;
		}
		footer { 
			position: fixed;
			/*border: 1px solid black; */
			bottom: -0px; 
			/*right: 0px; */
			height: 50px;
			font-size: 10px;

		}
		p:last-child { page-break-after: never; }

	</style>
</head>
<body>
	<header>
		<div class="header">
			<img class="schoollogo" src="{{Config::get('settings.schoollogo')}}" alt="{{Config::get('settings.schoolabbr')}}" width="50"><br/>
			{{Config::get('settings.schoolname')}}<br/>
			
			{{Config::get('settings.schooladdress')}}<br/>
			<br/><br/>

			<b>ENROLLMENT LIST</b><br>
			<i>As of {{\Carbon\Carbon::now()->toDateString()}}</i>

		</div>

		<div class="department_info">
			<table width="100%">
				<tr>
					<td width="10%">School Year</td>
					<td><b>{{ $school_year->schoolYear ?? ''}}</b></td>

					<td width="10%">Department</td>
					<td><b>{{ $department->name ?? $department}}</b></td>

				</tr>
				<tr>
					<td width="10%">Grade Level</td>
					<td><b>{{ $level->year ?? $level }}</b></td>

					<td width="10%">Track</td>
					<td><b>{{ $track->code ?? $track}}</b></td>
				</tr>
			</table>
		</div>
	</header>
	
	<hr>

	
	<table class="enrollmentlist" width="100%" style="padding-bottom: 70px;">
				<thead>
					<tr>
						<th>No.</th>
						<th>LRN</th>
						<th>Student No.</th>
						<th>Last Name</th>
						<th>First Name</th>
						<th>Middle Name</th>
						<th>Gender</th>
						<th>Age</th>
						<th>Date Enrolled</th>
						
					</tr>
				</thead>

				<tbody>
					{{dd($enrollment)}}
					@foreach($enrollment as $key => $item)
		
					<tr>
						<td>{{$key + 1 }}</td>
						<td>{{ strtoupper($item->lrn)}}</td>
						<td>{{ strtoupper($item->prefixed_student_number) }}</td>
						<td>{{ strtoupper($item->lastname) }}</td>
						<td>{{ strtoupper($item->firstname) }}</td>
						<td>{{ strtoupper($item->middlename ?? '')}}</td>
						<td>{{ strtoupper($item->gender) ?? '' }}</td>
						<td>{{ strtoupper($item->calculated_age) ?? '' }}</td>
						<td>{{ $item->date_enrolled ?? '' }}</td>
					</tr>

					@endforeach
				</tbody>
			</table>

	<footer>
		<center>
			<img width="40" src="images/schoolmate_logo.jpg" alt="schoolmate_logo">
		</center>
		<center>
			<p style="margin:0px; padding:0px;">Copyright &copy; 2019</p>
			<p style="margin:0px; padding:0px;">Powered by: Tigernet Hosting and IT Services</p>
		</center>
	</footer>	


</body>
</html>