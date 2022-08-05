@if ($crud->hasAccess('list'))
	@if($entry->active)
		<a href="{{ url($crud->route) . '/deactivate?tuition_id=' . $entry->id . '&schoolyear_id=' . $entry->schoolyear_id . '&department_id=' . $entry->department_id . '&grade_level_id=' . $entry->grade_level_id . '&track_id=' . $entry->track_id }}" class="btn btn-xs btn-danger action-btn" title="Deactivate">
			<i class="fa fa-power-off"></i>
		</a>
	@else
		<a href="{{ url($crud->route) . '/activate?tuition_id=' . $entry->id . '&schoolyear_id=' . $entry->schoolyear_id . '&department_id=' . $entry->department_id . '&grade_level_id=' . $entry->grade_level_id . '&track_id=' . $entry->track_id }}" class="btn btn-xs btn-info action-btn" title="Activate">
			<i class="fa fa-toggle-on" aria-hidden="true"></i>
		</a>
	@endif
@endif