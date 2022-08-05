@if ($crud->hasAccess('list'))
	<a  href="{{ url($crud->route) . '/' . $entry->id . '/print' }}" class="btn btn-xs btn-default action-btn" title="print">
		<i class="fa fa-print"></i>
	</a>
@endif