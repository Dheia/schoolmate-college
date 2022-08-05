<li><a href="#" id="addLeave{{ $entry->id }}" class="text-sm"><i class="fa fa-plus"></i> Add Leave</a></li>

<!-- Modal -->

@php
	$leaves = App\Models\Leave::select('name', 'id')->get();
@endphp

<script>
	$('#addLeave{{$entry->id}}').click(function () {
		console.log({{ $entry->id }});
		$('body').append('<div class="modal fade" id="addLeaveModal{{ $entry->id }}" tabindex="-1" role="dialog" aria-labelledby="addLeave{{ $entry->id }}">\
							<div class="modal-dialog" role="document">\
								<div class="modal-content">\
									<div class="modal-header">\
										<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>\
										<h4 class="modal-title"><small>Add Leave | <b>{{ $entry->full_name }}</small></b></h4>\
									</div>\
									<div class="modal-body">\
										<form action="/" id="leaveForm{{ $entry->id }}" method="post">\
											<div class="form-group col-md-8">\
												<label for="selectLeave{{ $entry->id }}">Leave Type</label>\
												<select name="leave_id" id="selectLeave{{ $entry->id }}" class="form-control selectLeave{{ $entry->id }}" required>\
													@foreach($leaves as $leave)\
														<option value="{{ $leave->id }}">{{ $leave->name }}</option>\
													@endforeach\
												</select>\
											</div>\
											<div class="form-group col-md-4">\
												<label for="noOfDays">No. of Days</label>\
												<input type="number" name="days" id="noOfDays" class="form-control" required/>\
											</div>\
											<div class="form-group col-md-6">\
												<label for="startDate">Start Date</label>\
												<input type="date" name="start_date" id="startdate" class="form-control" required/>\
											</div>\
											<div class="form-group col-md-6">\
												<label for="endDate">End Date</label>\
												<input type="date" name="end_date" id="endDate" class="form-control" required/>\
											</div>\
											<div class="form-group col-md-12">\
												<label for="description">Description</label>\
												<textarea name="description" id="description" class="form-control"></textarea>\
											</div>\
											<div class="clearfix"></div>\
											<div class="col-md-12">\
												<button type="submit" class="btn btn-primary" id="submitLeave{{ $entry->id }}" style="float: right;">Submit</button>\
												<button type="button" class="btn btn-default" data-dismiss="modal" style="float: right;">Close</button>\
											</div>\
											<div class="clearfix"></div>\
										</form>\
									</div>\
								</div>\
							</div>\
						</div>');
		$('#addLeaveModal{{ $entry->id }}').modal('show');

		// Fetch All Leave Items
		$('#selectLeave{{ $entry->id }}').select2();
		$('.select2-choice').css('border', '1px solid #ccc');

		$('#addLeaveModal{{ $entry->id }}').on('hidden.bs.modal', function (e) {
			$('#addLeaveModal{{ $entry->id }}').remove();		  
		});

		$('#leaveForm{{ $entry->id }}').submit(function (e) {
			e.preventDefault();
			var serializeData = $(this).serialize();
			$.ajax({
				type: 'post',
				url: 'employee/{{ $entry->id }}/add-leave?' + serializeData,
				success: function (response) {
					if(response.error) {
						alert(JSON.stringify(response.message));
						return;
					}

					alert(response.message);
					$('#addLeaveModal{{ $entry->id }}').modal('toggle');
				}
			});
		})
	});

</script>