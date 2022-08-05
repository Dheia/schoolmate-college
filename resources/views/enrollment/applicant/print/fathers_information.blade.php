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