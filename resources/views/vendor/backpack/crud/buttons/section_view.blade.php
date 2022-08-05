@if ($crud->hasAccess('update'))
	<a target="_blank" href="section_management/{{ $entry->getKey() }}/view" class="btn btn-xs btn-default"><i class="fa fa-eye"></i> View</a>
@endif