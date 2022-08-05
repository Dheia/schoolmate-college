@if ($crud->hasAccess('list'))
	@if($entry->active)
		<a href="{{ url($crud->route) . '/' . $entry->id . '/deactivate' }}" class="btn btn-xs btn-danger action-btn" title="Deactivate">
			<i class="fa fa-power-off"></i>
		</a>
	@else
		<a href="{{ url($crud->route) . '/' . $entry->id . '/activate' }}" class="btn btn-xs btn-info action-btn" title="Activate">
			<i class="fa fa-toggle-on" aria-hidden="true"></i>
		</a>
	@endif
@endif