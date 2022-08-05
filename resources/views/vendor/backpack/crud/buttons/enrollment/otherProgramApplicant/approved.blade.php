@if($entry->invoice_no === null && ! $entry->approved)
	<a href="{{ url($crud->route . '/' . $entry->getKey() . '/approve') }}" class="btn btn-primary btn-xs action-btn" title="Approve"> <i class="fa fa-user-plus"></i>
		Approve
	</a>	
@endif