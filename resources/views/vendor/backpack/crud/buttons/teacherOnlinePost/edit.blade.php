@if($crud->hasAccess('update'))
	<a href="{{ url($crud->route) }}/{{ $entry->id }}/edit?teacher_id={{ request()->teacher_id }}&class_code={{ request()->class_code }}" class="btn btn-xs btn-default"><i class="fa fa-edit" title="Update"></i> </a>
@endif