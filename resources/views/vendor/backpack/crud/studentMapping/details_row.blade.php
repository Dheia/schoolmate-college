<div class="m-t-10 m-b-10 p-l-10 p-r-10 p-t-10 p-b-10">
	{{-- <div class="row"> --}}
			<table class="table table-striped" id="table-{{ $entry->id }}">
				<thead>
					<tr>
						<th><small class="text-uppercase">Subject Code</small></th>
						<th><small class="text-uppercase">Subject Title</small></th>
						<th><small class="text-uppercase">Subject Description</small></th>
						<th><small class="text-uppercase">Percentage</small></th>
						<th><small class="text-uppercase">No. Of Units</small></th>
						<th><small class="text-uppercase">Additional Price</small></th>
					</tr>
				</thead>
				<tbody>
					@if($entry->subjects !== null)
						@foreach($entry->subjects as $subject)
							<tr>
								<td>{{ \App\Models\SubjectManagement::find($subject->subject_code)->subject_code }}</td>
								<td>{{ $subject->subject_title }}</td>
								<td>{{ $subject->subject_description }}</td>
								<td>{{ $subject->percentage }}</td>
								<td>{{ $subject->no_unit }}</td>
								<td>{{ $subject->additional_fee }}</td>
							</tr>
						@endforeach
					@endif
				</tbody>
			</table>
		</div>
	{{-- </div> --}}
</div>
<div class="clearfix"></div>

<script>
	$(document).ready(function () {

		// PARENT 
		var parent = {!! json_encode($parent) !!};
		
		trParent = "";
		$.each(parent, function (key, val) {
			additional_fee = '';
			if(val.additional_fee !== null) {
				additional_fee = val.additional_fee
			}
			trParent += "<tr id='subject-" + val.subject_id + "' style='background-color: #2CC16A; color: #FFF;'>\
							<td>" + val.subject_code + "</td>\
							<td>" + val.subject_title + "</td>\
							<td>" + val.subject_description + "</td>\
							<td>" + val.percentage + "</td>\
							<td>" + val.no_unit + "</td>\
							<td>" + additional_fee + "</td>\
						</tr>";
		});

		$('#table-{{ $entry->id }} > tbody').html(trParent);

		// CHILDREN
		var children = {!! json_encode($children) !!};

		$.each(children, function (key, val) {
			trChildren = 	"<tr id='subject-" + val.subject_id + "'>\
								<td style='padding-left: 30px;'>" + val.subject_code + "</td>\
								<td style='padding-left: 30px;'>" + val.subject_title + "</td>\
								<td style='padding-left: 30px;'>" + val.subject_description + "</td>\
								<td style='padding-left: 30px;'>" + val.percentage + "</td>\
								<td style='padding-left: 30px;'>" + val.no_unit + "</td>\
								<td style='padding-left: 30px;'>" + additional_fee + "</td>\
							</tr>";
			$(trChildren).insertAfter('#table-{{ $entry->id }} > tbody > #subject-' + val.parent_of);
		});
	});
</script>