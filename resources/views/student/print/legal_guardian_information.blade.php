<div class = "profilediv">
	<table class="profile table">
		<tbody>
			<tr>
				<td colspan="4" class="text-uppercase text-center" style="background-color: #CCC">
					Legal Guardian's Information (If Applicable)
				</td>
			</tr>

			<tr>
				<td>Last Name:</td>
				<td class="text-capitalize">{{ strtolower($student->legal_guardian_lastname) }}</td>
				<td>Middle Name:</td>
				<td class="text-capitalize">{{ strtolower($student->legal_guardian_middlename) }}</td>
			</tr>
			<tr>
				<td>First Name:</td>
				<td class="text-capitalize">{{ strtolower($student->legal_guardian_firstname) }}</td>
				<td>Occupation:</td>
				<td class="text-capitalize">{{ strtolower($student->legal_guardian_occupation) }}</td>
			</tr>
			<tr>
				<td>Mobile No.:</td>
				<td>{{ $student->legal_guardian_contact_number }}</td>
			</tr>

		</tbody>
	</table>
</div> <!-- .profilediv -->