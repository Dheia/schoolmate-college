@if($entry->qbo_id == null && $crud->hasAccess('list'))
	<a href="{{ url('admin/other-programs/' . $entry->getKey() . '/qbo-connect') }}" class="btn btn-default btn-xs"><i class="fa fa-plus"></i>&nbsp; Add to QBO</a>	
@endif