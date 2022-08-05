<div class = "col-8 m-0 p-0">
	<table class="table">
		<thead>
			<tr>
				<th colspan="4" 
				scope="col"
					class="text-uppercase text-center"
					style="background-color: #CCC;">
					Student Information
				</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td>Last Name:</td>
				<th class="text-capitalize">{{ strtolower($student->lastname) }}</th>
			</tr>
			<tr>
				<td>First Name:</td>
				<th class="text-capitalize">{{ strtolower($student->firstname) }}</th>
			</tr>
			<tr>
				<td>Middle Name:</td>
				<th class="text-capitalize">{{ strtolower($student->middlename) }}</th>
			</tr>
		</tbody>
	</table>
</div> <!-- .profilediv -->