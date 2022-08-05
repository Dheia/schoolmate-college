<div class="col-md-11" style="padding-top: 30px; padding-bottom: 20px; left: 50%; transform: translateX(-50%);">
	<table class="table table-sm table-responsive table-striped table-hoverable bordered">
		<thead style="background-color: #d6dee8;">
			<tr>
				<th rowspan="3" class="text-center" style="vertical-align: middle;">Range Of Compensation</th>
				<th rowspan="3" class="text-center" style="vertical-align: middle;">Monthly Salary Credit</th>
				<th colspan="7" class="text-center">Employer - Employee</th>
				<th class="text-center">SE/VM/OFW</th>
			</tr>
			<tr>
				<th colspan="3" class="text-center">Social Security</th>
				<th>EC</th>
				<th colspan="3" class="text-center">Total Contribution</th>
				<th rowspan="2" class="text-center" style="vertical-align: middle;">Total Contribution</th>
			</tr>
			<tr>
				<th colspan="">ER</th>
				<th colspan="">EE</th>
				<th colspan="">Total</th>
				<th colspan="">ER</th>
				<th colspan="">ER</th>
				<th colspan="">EE</th>
				<th colspan="">Total</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td class="text-center">{{ $entry->range_of_compensation_min }} - {{ $entry->range_of_compensation_max }}</td>
				<td class="text-center">{{ $entry->monthly_salary_credit }}</td>
				<td>{{ $entry->social_security_er }}</td>
				<td>{{ $entry->social_security_ee }}</td>
				<td>{{ ( $entry->social_security_er + $entry->social_security_ee ) }}</td>
				<td>{{ $entry->ec_er }}</td>
				<td>{{ $entry->total_contribution_er }}</td>
				<td>{{ $entry->total_contribution_ee }}</td>
				<td>{{ ( $entry->total_contribution_er + $entry->total_contribution_ee ) }}</td>
				<td class="text-center">{{ ( $entry->social_security_er + $entry->social_security_ee ) }}</td>
			</tr>
		</tbody>
	</table>
</div>