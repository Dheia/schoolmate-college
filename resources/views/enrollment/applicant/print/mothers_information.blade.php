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