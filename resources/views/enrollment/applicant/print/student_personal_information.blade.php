<div class = "">
	<table class="table">
		<thead>
			<tr>
				<th colspan="4" 
				scope="col"
					class="text-uppercase text-center"
					style="background-color: #CCC">
					Personal Information
				</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<th>Age:</th>
				<td>{{ $student->age }}</td>
				<th>Sex:</th>
				<td>{{ $student->gender }}</td>
			</tr>
			<tr>
				<th>Date Of Birth:</th>
				<td>{{ \Carbon\Carbon::parse($student->birthdate)->format('F d, Y') }}</td>
				<th>Citizenship:</th>
				<td class="text-capitalize">{{ strtolower($student->citizenship) }}</td>
			</tr>
			<tr>
				<th>Place Of Birth:</th>
				<td class="text-capitalize">{{ strtolower($student->birthplace) }}</td>
				<th>Religion:</th>
				<td class="text-capitalize">{{ strtolower($student->religion) }}</td>
			</tr>
			<tr>
				<th style="padding-top: 5px !important;"></th>
			</tr>
			<tr>
				<th>Residential Address</th>
			</tr>
				
			<tr>
				<th style="padding-left: 10px !important;">Address Line</th>
				<td class="text-capitalize">{{ strtolower($student->street_number ?? '-') }}</td>
				<th>City / Municipality:</th>
				<td class="text-capitalize">{{ strtolower($student->city_municipality ?? '-') }}</td>
			</tr>
			<tr>
				<th style="padding-left: 10px !important;">Barangay</th>
				<td class="text-capitalize">{{ strtolower($student->barangay ?? '-') }}</td>
				<th>Province:</th>
				<td class="text-capitalize">{{ strtolower($student->province ?? '-') }}</td>
			</tr>

		</tbody>
	</table>
</div> <!-- .profilediv -->