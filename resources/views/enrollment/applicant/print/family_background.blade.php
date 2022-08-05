<div class = "">
	<table class="table">
		<thead>
			<tr>
				<th colspan="4" 
				scope="col"
					class="text-uppercase text-center"
					style="background-color: #CCC">
					Family Background
				</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<th>MOTHER'S INFORMATION</th>
				<td></td>
				<th >FATHER'S INFORMATION</th>
				<td></td>
			</tr>
			<tr>
				<th>Last Name:</th>
				<td>{{ ucwords($student->motherlastname) }}</td>
				<th>Last Name:</th>
				<td>{{ ucwords($student->fatherlastname) }}</td>
			</tr>
			<tr>
				<th>First Name:</th>
				<td>{{ ucwords($student->motherfirstname) }}</td>
				<th>First Name:</th>
				<td>{{ ucwords($student->fatherfirstname) }}</td>
			</tr>
			<tr>
				<th>Middle Name:</th>
				<td>{{ ucwords($student->mothermiddlename) ?? '-' }}</td>
				<th>Middle Name:</th>
				<td>{{ ucwords($student->fathermiddlename) ?? '-' }}</td>
			</tr>
			<tr>
				<th>Occupation:</th>
				<td>{{ ucwords($student->mother_occupation) ?? '-' }}</td>
				<th>Occupation:</th>
				<td>{{ ucwords($student->father_occupation) ?? '-' }}</td>
			</tr>
			<tr>
				<th>Nationality:</th>
				<td>{{ ucwords($student->mothercitizenship ?? '-') }}</td>
				<th>Nationality:</th>
				<td>{{ ucwords($student->fathercitizenship ?? '-') }}</td>
			</tr>
			<tr>
				<th>Contact Number:</th>
				<td>{{ $student->mothernumber ?? '-' }}</td>
				<th>Contact Number:</th>
				<td>{{ $student->fatherMobileNumber ?? '-' }}</td>
			</tr>
			<tr>
				<th>Deceased?</th>
				<td>@if($student->mother_living_deceased == 'deceased') Yes @else No @endif</td>
				<th>Deceased?</th>
				<td>@if($student->father_living_deceased == 'deceased') Yes @else No @endif</td>
			</tr>
		</tbody>
	</table>
</div> <!-- .profilediv -->