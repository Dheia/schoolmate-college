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
				<td>Student Number:</td>
				<th class="text-uppercase">{{ strtolower($student->prefixed_student_number) }}</th>
				<td>Department:</td>
				<th class="text-capitalize">{{ $enrollment->department->name }}</th>
			</tr>
			
			<tr>
				<td>Name:</td>
				<th class="text-capitalize">{{ strtolower($student->lastname) }}, {{ strtolower($student->firstname) }} {{ strtolower($student->middlename) }}</th>
				<td>Level:</td>
				<th class="text-capitalize">{{ $enrollment->level->year }}</th>
			</tr>
			<tr>
				<td></td>
				<th class="text-capitalize"></th>
				@if($enrollment->track !== null)
                    <td>Track</td>
                    <th class="text-uppercase">{{ $enrollment->track->code }}</th>
                @endif
			</tr>
		</tbody>
	</table>
</div> <!-- .profilediv -->