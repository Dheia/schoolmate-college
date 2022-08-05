<div class="col-md-12 p-t-10 form-group">
	
	<h4><b>{{ $entry[0]->name }}</b></h4>

	<table class="table table-striped table-bordered">
		<thead>
			<th>Time</th>
			<th>Mon</th>
			<th>Tue</th>
			<th>Wed</th>
			<th>Thu</th>
			<th>Fri</th>
			<th>Sat</th>
			<th>Sun</th>
		</thead>
		<tbody>
			<tr>
				<td><b>Timein</b></td>
				{{-- {{ dd(get_defined_vars()) }} --}}
				<td>{{ $entry[0]->mon_timein !== null ?  \Carbon\Carbon::parse($entry[0]->mon_timein)->format ('h:i a') : '' }}</td>
				<td>{{ $entry[0]->tue_timein !== null ?  \Carbon\Carbon::parse($entry[0]->tue_timein)->format ('h:i a') : '' }}</td>
				<td>{{ $entry[0]->wed_timein !== null ?  \Carbon\Carbon::parse($entry[0]->wed_timein)->format ('h:i a') : '' }}</td>
				<td>{{ $entry[0]->thu_timein !== null ?  \Carbon\Carbon::parse($entry[0]->thu_timein)->format ('h:i a') : '' }}</td>
				<td>{{ $entry[0]->fri_timein !== null ?  \Carbon\Carbon::parse($entry[0]->fri_timein)->format ('h:i a') : '' }}</td>
				<td>{{ $entry[0]->sat_timein !== null ?  \Carbon\Carbon::parse($entry[0]->sat_timein)->format ('h:i a') : '' }}</td>
				<td>{{ $entry[0]->sun_timein !== null ?  \Carbon\Carbon::parse($entry[0]->sun_timein)->format ('h:i a') : '' }}</td>
			</tr>
			<tr>
				<td><b>Timeout</b></td>
				<td>{{ $entry[0]->mon_timeout !== null ?  \Carbon\Carbon::parse($entry[0]->mon_timeout)->format ('h:i a') : '' }}</td>
				<td>{{ $entry[0]->tue_timeout !== null ?  \Carbon\Carbon::parse($entry[0]->tue_timeout)->format ('h:i a') : '' }}</td>
				<td>{{ $entry[0]->wed_timeout !== null ?  \Carbon\Carbon::parse($entry[0]->wed_timeout)->format ('h:i a') : '' }}</td>
				<td>{{ $entry[0]->thu_timeout !== null ?  \Carbon\Carbon::parse($entry[0]->thu_timeout)->format ('h:i a') : '' }}</td>
				<td>{{ $entry[0]->fri_timeout !== null ?  \Carbon\Carbon::parse($entry[0]->fri_timeout)->format ('h:i a') : '' }}</td>
				<td>{{ $entry[0]->sat_timeout !== null ?  \Carbon\Carbon::parse($entry[0]->sat_timeout)->format ('h:i a') : '' }}</td>
				<td>{{ $entry[0]->sun_timeout !== null ?  \Carbon\Carbon::parse($entry[0]->sun_timeout)->format ('h:i a') : '' }}</td>
			</tr>
		</tbody>
	</table>
</div>