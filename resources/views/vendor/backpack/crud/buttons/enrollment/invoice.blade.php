@if( ($entry->invoice_no == null && !$entry->invoiced) && $crud->hasAccess('list') && !$entry->is_applicant)
	<a 
		{{-- href="{{ url('admin/enrollment/' . $entry->getKey() . '/set/invoice') }}"  --}}
		href="javascript:void(0)" 
		class="btn btn-default btn-xs setInvoice action-btn" 
		title="Set QBO Invoice"
		data-id="{{ $entry->getKey() }}"
		>
			<i class="fa fa-plus"></i>
	</a>
@endif