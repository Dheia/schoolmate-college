<div class = "profilediv">
	<table class="table">
		<thead>
			<tr>
				<th colspan="4" 
				scope="col"
					class="text-uppercase text-center"
					style="background-color: #CCC">
					Student Information
				</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td>LRN:</td>
				<th class="text-capitalize">{{ strtolower($student->lrn ?? '-') }}</th>
				<td>Student Number:</td>
				<th class="text-uppercase">{{ strtolower($student->prefixed_student_number) }}</th>
			</tr>
			
			<tr>
				<td>Last Name:</td>
				<th class="text-capitalize">{{ strtolower($student->lastname) }}</th>
				<td>Middle Name:</td>
				<th class="text-capitalize">{{ strtolower($student->middlename) }}</th>
			</tr>
			<tr>
				<td>First Name:</td>
				<th class="text-capitalize">{{ strtolower($student->firstname) }}</th>
				<td>Gender:</td>
				<th class="text-capitalize">{{ $student->gender }}</th>
			</tr>
			<tr>
				<td>Date Of Birth:</td>
				<th>{{ \Carbon\Carbon::parse($student->birthdate)->format('F d, Y') }}</th>
				<td>Age:</td>
				<th>{{ $student->age }}</th>
			</tr>
			<tr>
				<td>Place Of Birth:</td>
				<th class="text-capitalize">{{ strtolower($student->birthplace) }}</th>
				<td>Citizenship:</td>
				<th class="text-capitalize">{{ strtolower($student->citizenship) }}</th>
			</tr>
			<tr>
				<td>Residential Address:</td>
				<th class="text-capitalize">{{ strtolower($student->residential_address) }}</th>
				<td>Religion:</td>
				<th class="text-capitalize">{{ strtolower($student->religion) }}</th>
			</tr>
			<tr>
				<td>Email Address:</td>
				<th>{{ $student->email ?? '-' }}</th>
				<td colspan="2"></td>
			</tr>

		</tbody>
	</table>
</div> <!-- .profilediv -->