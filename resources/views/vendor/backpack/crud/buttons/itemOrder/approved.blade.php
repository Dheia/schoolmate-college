@if ($crud->hasAccess('approved'))
    @if($entry->getAttributeValue('approved') == 1)
        <a class="btn btn-sm btn-primary active"><i class="fa fa-check"></i> Approved</a>
    @else
    	<a href="j{{ url($crud->route.'/#approvedModal') }}" data-code="{{ $entry->getAttributeValue('code') }}"  data-id="{{ $entry->getKey() }}" data-route="{{ url($crud->route.'/approved/'.$entry->getKey()) }}" class="btn btn-xs btn-default" data-toggle="modal" data-target="#approvedModal"><i class="fa fa-check"></i> Approve</a>
    @endif

    <div class="modal fade" tabindex="-1" role="dialog" id="approvedModal">
		<div class="modal-dialog" role="document">
		    <div class="modal-content">
			    <div class="modal-header">
			        <strong><h3 class="modal-title font-weight-bold" id="approvedModalLabel">Modal title</h3></strong>
			        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
			          <span aria-hidden="true">&times;</span>
			        </button>
			    </div>
			    <form method="POST" action="{{ url($crud->route.'/approved') }}">
			    	{!! csrf_field() !!}
				    <div class="modal-body">
				      	<input type="hidden" name="order_id" id="order_id">
				        <p id="approvedModalBody">Modal body text goes here.</p>
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
	        $('#approvedModal').on("show.bs.modal", function (e) {
	        	$("#order_id").val($(e.relatedTarget).data('id'));
	            $("#approvedModalLabel").html('Order #'+$(e.relatedTarget).data('code'));
	            $("#approvedModalBody").html('Are you sure you want to approve this order?');
	        });
	    });

		// make it so that the function above is run after each DataTable draw event
		// crud.addFunctionToDataTablesDrawEventQueue('deleteEntry');
	</script>

@endif
