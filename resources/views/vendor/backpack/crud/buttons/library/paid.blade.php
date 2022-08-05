@if ($crud->hasAccess('paid'))
    @if($entry->getAttributeValue('paid') == 1)
        <a class="btn btn-sm btn-primary active action-btn"><i class="fa fa-check"></i> Paid</a>
    @else
    	<a href="j{{ url($crud->route.'/#paidModal') }}" data-id="{{ $entry->getKey() }}" data-route="{{ url($crud->route.'/paid/'.$entry->getKey()) }}" class="btn btn-xs btn-default action-btn" data-toggle="modal" data-target="#paidModal"><i class="fa fa-check"></i> Paid</a>
    @endif

    <div class="modal fade" tabindex="-1" role="dialog" id="paidModal">
		<div class="modal-dialog" role="document">
		    <div class="modal-content">
			    <div class="modal-header">
			        <strong><h3 class="modal-title font-weight-bold" id="paidModalLabel">Confirmation</h3></strong>
			        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
			          <span aria-hidden="true">&times;</span>
			        </button>
			    </div>
			    <form method="POST" action="{{ url($crud->route.'/paid') }}">
			    	{!! csrf_field() !!}
				    <div class="modal-body">
				      	<input type="hidden" name="return_book_id" id="return_book_id">
				        <p id="paidModalBody">Are you sure you want to paid this student?</p>
				    </div>
				    <div class="modal-footer">
				        <button type="submit" class="btn btn-primary">Confirm</button>
				        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
				    </div>
				</form>
		    </div>
		</div>
	</div>

	<script>
		$(function() {
	        $('#paidModal').on("show.bs.modal", function (e) {
	        	$("#return_book_id").val($(e.relatedTarget).data('id'));
	        });
	    });

		// make it so that the function above is run after each DataTable draw event
		// crud.addFunctionToDataTablesDrawEventQueue('deleteEntry');
	</script>

@endif
