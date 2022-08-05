@if($crud->hasAccess('list'))
	<a href="{{ url('admin/locker/' . $entry->getKey() . '/read') }}" class="btn btn-default btn-xs" title="Show"><i class="fa fa-eye"></i></a>	
@endif