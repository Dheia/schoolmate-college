@if($crud->hasAccess('create_online_class'))

	@if(!$entry->active_online_class)
		<a href="{{ url($crud->route) . '/' . $entry->getKey() . '/create-online-class?teacher_id=' . request()->teacher_id }}" class="btn btn-xs btn-info action-btn" title="Activate Online Class">
			<i class="fa fa-power-off"></i> Activate Online Class
		</a>
	{{-- 	@else
		<br><a style="margin-bottom: 5px !important; width: 100%;" href="{{ url($crud->route) . '/' . $entry->id . '/activate' }}" class="btn btn-xs btn-info" title="Activate"> Deactivate
			<i class="fa fa-toggle-on" aria-hidden="true"></i>
		</a> --}}
	@endif
@endif
