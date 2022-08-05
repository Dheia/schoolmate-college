	@if($entry->is_active == '0')
		<a href="{{ url($crud->route) . '/' . $entry->id . '/active' }}" class="btn btn-xs btn-default action-btn" title="Set Active"> <i class="fa fa-bars"></i></a>
	@else
		<a href="{{ url($crud->route) . '/' . $entry->id . '/deactive' }}" class="btn btn-xs btn-success action-btn" title="Deactivate"> <i class="fa fa-check-square-o"></i></a>
	@endif