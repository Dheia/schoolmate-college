@if($crud->hasAccess('list'))
	<a href="/{{ $crud->route . '/' . $entry->school_year_id . '/' . $entry->section_id }}" class="btn btn-default btn-xs"><i class="fa fa-eye"></i>&nbsp; View</a>
@endif