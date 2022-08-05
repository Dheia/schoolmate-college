@if($entry->invoice_no === null && $crud->hasAccess('list') && $entry->is_applicant)
	<a href="{{ url('admin/enrollment/' . $entry->getKey() . '/enroll') }}" class="btn btn-xs btn-default action-btn" title="Enroll">
		Enroll
	</a>	
@endif