<div class="m-t-10 m-b-10 p-l-10 p-r-10 p-t-10 p-b-10">
	<div class="row">
			<table class="table table-striped table-bordered">
				<thead>
					<th>Subject Code</th>
					<th>Subject Title</th>
					<th>Subject Description</th>
					<th>Percentage</th>
					<th>No. Of Units</th>
					{{-- <th>Additional Price</th> --}}
				</thead>
				<tbody>
					{{-- $this->data['entry']->subjectMappings[0]->subjects --}}
					@foreach($entry->subjectMappings as $subjectMap)
						 {{-- {{ dd($subjectMap->subjects) }} --}}
						@foreach($subjectMap->subjects as $subject) 
						<tr>
							@php
								$subject = \App\Models\SubjectManagement::find($subject->subject_code);
							@endphp
							<td>{{ $subject->subject_code }}</td>
							<td>{{ $subject->subject_title }}</td>
							<td>{{ $subject->subject_description }}</td>
							<td>{{ $subject->percent }}</td>
							<td>{{ $subject->no_unit }}</td>
							{{-- <td>{{ $subject->price }}</td> --}}
						</tr>
						@endforeach
					@endforeach
				</tbody>
			</table>
		</div>
	</div>
</div>
<div class="clearfix"></div>