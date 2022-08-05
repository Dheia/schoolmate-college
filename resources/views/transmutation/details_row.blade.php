<div class="col-md-6 col-md-offset-3 p-t-10 form-group">
	

	<table class="table table-striped table-bordered">
		<thead>
			<th class="text-center">Min</th>
			<th class="text-center">Max</th>
			<th class="text-center">Transmuted Grade</th>
			
		</thead>
		<tbody>
			@foreach($entry->transmutation_table as $transmutation)
				<tr>
					<td class="text-center">{{ $transmutation->min }}</td>
					<td class="text-center">{{ $transmutation->max }}</td>
					<td class="text-center">{{ $transmutation->transmuted_grade }}</td>
				</tr>
			@endforeach
		</tbody>
	</table>
</div>