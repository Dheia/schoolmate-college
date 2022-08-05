@if( ($entry->invoice_no !== null && !$entry->invoiced) && $crud->hasAccess('list'))	
	<a href="{{ url('admin/enrollment/' . $entry->invoice_no . '/invoice/delete') }}" class="btn btn-xs btn-default action-btn" title="Mark QBO Invoice to Inactive"><i class="fa fa-close"></i></a>
@endif