@if ($crud->hasAccess('pickup'))
    @if($entry->getAttributeValue('pickup') == 1)
        <a class="btn btn-sm btn-primary active"><i class="fa fa-check"></i> Picked up</a>
    @else
    	<a href="j{{ url($crud->route.'/#pickupModal') }}" data-code="{{ $entry->getAttributeValue('code') }}"  data-id="{{ $entry->getKey() }}" data-route="{{ url($crud->route.'/pickup/'.$entry->getKey()) }}" class="btn btn-xs btn-default" data-toggle="modal" data-target="#pickupModal"><i class="fa fa-check"></i> Picked up</a>
    @endif

    <div class="modal fade" tabindex="-1" role="dialog" id="pickupModal">
		<div class="modal-dialog" role="document">
		    <div class="modal-content">
			    <div class="modal-header">
			        <strong><h3 class="modal-title font-weight-bold" id="pickupModalLabel">Modal title</h3></strong>
			        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
			          <span aria-hidden="true">&times;</span>
			        </button>
			    </div>
			    <form method="POST" action="{{ url($crud->route.'/pickup') }}">
			    	{!! csrf_field() !!}
				    <div class="modal-body">
				      	<input type="hidden" name="order_id" id="order_id">
				        <p id="pickupModalBody">Modal body text goes here.</p>
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
	        $('#pickupModal').on("show.bs.modal", function (e) {
	        	$("#order_id").val($(e.relatedTarget).data('id'));
	            $("#pickupModalLabel").html('Order #'+$(e.relatedTarget).data('code'));
	            $("#pickupModalBody").html('Update Order #'+$(e.relatedTarget).data('code')+' picked up?');
	        });
	    });

		// make it so that the function above is run after each DataTable draw event
		// crud.addFunctionToDataTablesDrawEventQueue('deleteEntry');
	</script>

@endif
