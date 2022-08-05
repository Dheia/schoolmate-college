@if($crud->hasAccess('section grades'))
	<a href="/{{ $crud->route . '/' . $entry->id . '/grades' }}" class="btn btn-default btn-xs"><i class="fa fa-eye"></i>&nbsp; View Class</a>
@endif