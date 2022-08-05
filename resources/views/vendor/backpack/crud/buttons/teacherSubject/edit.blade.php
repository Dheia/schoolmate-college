@if($crud->hasAccess('update'))
	<a href="{{ url($crud->route) }}/{{ $entry->id }}/edit?teacher_id={{ request()->teacher_id }}" class="btn btn-xs btn-default action-btn"><i class="fa fa-edit" title="Update"></i> {{-- Update --}} </a>
@endif