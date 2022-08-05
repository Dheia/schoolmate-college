@if( ($entry->invoice_no === null && !$entry->invoiced) && $crud->hasAccess('list') && !$entry->is_applicant)
	<a href="{{ url('admin/enrollment/' . $entry->getKey() . '/edit') }}" class="btn btn-xs btn-default action-btn" title="Edit"><i class="fa fa-edit"></i></a>	
@endif