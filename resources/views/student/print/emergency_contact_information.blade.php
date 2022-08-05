<div class = "profilediv">
	<table class="profile table">
		<tbody>

			<tr>
				<td colspan="4" class="text-uppercase text-center" style="background-color: #CCC">Emergency Contact Information</td>
			</tr>

			<tr>
				<td>Full Name:</td>
				<td class="text-capitalize">
					
					<span class="text-capitalize">{{ strtolower($student->emergency_contact_name_on_record) }}</span>
					
				</td>

				<td>Telephone No.:</td>
				<td>{{ $student->emergencyhomephone }}</td>
			</tr>

			<tr>
				<td>Relationship To The Child:</td>
				<td>
					@if($student->emergencyRelationshipToChild == "Other")
						<span class="text-capitalize">{{ strtolower($student->emergency_contact_other_relation_ship_to_child) }}</span>
					@else
						<span class="text-capitalize">{{ strtolower($student->emergencyRelationshipToChild) }}</span>
					@endif
				</td>

				<td>Mobile No.:</td>
				<td>{{ $student->emergency_contact_number_on_record }}</td>
			</tr>

			<tr>
				<td>Address:</td>
				<td colspan="3">{{ ucwords($student->emergency_contact_address_on_record) }}</td>
			</tr>

		</tbody>
	</table>
</div> <!-- .profilediv -->