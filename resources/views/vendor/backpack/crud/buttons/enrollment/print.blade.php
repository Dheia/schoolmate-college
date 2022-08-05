@if ($crud->hasAccess('print'))
	<a href="{{ str_replace('search/','',Request::url().'/'.$entry->getKey()) }}/print" target="_blank" class="btn btn-xs btn-default action-btn" title="Print"><i class="fa fa-print"></i></a>
@endif