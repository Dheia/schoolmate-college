@if($entry->submitted && $crud->hasAccess('list'))
	<a href="{{ backpack_url('submitted-grade/' . $entry->getKey() . '/reopen') }}" class="btn btn-default btn-xs action-btn" title="Reopen"><i class="fa fa-unlock"></i></a>	
@endif