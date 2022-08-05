@if($entry->invoice_no !== null || $entry->invoiced)	
	<a target="_blank" href="{{ url('admin/enrollment/' . $entry->getKey() . '/invoice/download') }}" class="btn btn-xs btn-default action-btn" title="Download QBO Invoice"><i class="fa fa-download"></i></a>
@endif