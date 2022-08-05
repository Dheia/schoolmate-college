@if ($crud->hasAccess('show'))
	<a href="{{ url($crud->route) . '/' . $entry->id . '?class_code=' . $entry->class_code}}" class="btn btn-xs btn-primary action-btn" title="Preview">
		<i class="fa fa-eye"></i>
		View
	</a
@endif