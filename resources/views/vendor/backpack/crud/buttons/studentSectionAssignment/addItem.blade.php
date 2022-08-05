@if($crud->hasAccess('create'))
	<button onclick="addStudent({{ $entry->getKey() }})" class="btn btn-default btn-xs"><i class="fa fa-plus"></i>&nbsp; Add</a>
@endif