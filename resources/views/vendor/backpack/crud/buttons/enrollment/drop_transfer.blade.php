@if($entry->invoice_no === null && $crud->hasAccess('list') && !$entry->is_applicant)
	<a href="{{ backpack_url('enrollment/' . $entry->getKey() . '/drop-or-transfer') }}" class="btn btn-default btn-xs action-btn" title="Drop and Transfer"><i class="fa fa-exchange"></i></a>	
@endif