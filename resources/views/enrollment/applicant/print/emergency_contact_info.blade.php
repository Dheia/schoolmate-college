<div class = "">
	<table class="table">
		<thead>
			<tr>
				<th colspan="4" 
				scope="col"
					class="text-uppercase text-center col-12"
					style="background-color: #CCC">
					Emergency Contact Information
				</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<th class="text-uppercase">LEGAL GUARDIAN INFORMATION </th>
				<td></td>
				<th class="text-uppercase">Person to Contact</th>
				<td></td>
			</tr>
			<tr>
				<th>Full Name:</th>
				<td class="text-capitalize">{{ strtolower($student->legal_guardian_full_name) }}</td>
				<th>Full Name:</th>
				<td class="text-capitalize">
					<span class="text-capitalize">{{ strtolower($student->emergency_contact_name_on_record) }}</span>
				</td>
			</tr>
			<tr>
				<th>Occupation:</th>
				<td class="text-capitalize">{{ strtolower($student->legal_guardian_occupation) }}</td>
				<th>Relationship to the Applicant:</th>
				<td>
					@if($student->emergencyRelationshipToChild == "Other")
						<span class="text-capitalize">{{ strtolower($student->emergency_contact_other_relation_ship_to_child) }}</span>
					@else
						<span class="text-capitalize">{{ strtolower($student->emergencyRelationshipToChild) }}</span>
					@endif
				</td>
				
			</tr>
			<tr>
				<th>Mobile No.:</th>
				<td>{{ $student->legal_guardian_contact_number }}</td>
				<th>Contact Number:</th>
				<td>{{ $student->emergency_contact_number_on_record }}</td>
			</tr>

			<tr>
				<th>Telephone No.:</th>
				<td>{{ $student->emergencyhomephone }}</td>
				<th>Address:</th>
				<td colspan="3">{{ ucwords($student->emergency_contact_address_on_record)  ?? '-' }}</td>
			</tr>

			<!-- <tr>
				<th>Relationship to the Applicant:</th>
				<td>
					@if($student->emergencyRelationshipToChild == "Other")
						<span class="text-capitalize">{{ strtolower($student->emergency_contact_other_relation_ship_to_child) }}</span>
					@else
						<span class="text-capitalize">{{ strtolower($student->emergencyRelationshipToChild) }}</span>
					@endif
				</td>

				
			</tr> -->

		</tbody>
	</table>
</div> <!-- .profilediv -->