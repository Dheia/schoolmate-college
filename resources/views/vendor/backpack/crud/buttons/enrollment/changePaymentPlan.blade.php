@if( ($entry->invoice_no == null && $entry->invoiced) && $crud->hasAccess('list') && !$entry->is_applicant)
	<a href="javascript:void(0)"  data-route="{{ url($crud->route. '/' . $entry->getKey() .'/commitment-payment/update') }}" data-id="{{ $entry->getKey() }}"  data-title="Change Payment Plan" class="btn btn-xs btn-default changePaymentPlan action-btn" title="Change Payment Plan">
		<i class="fa fa-credit-card"></i>
	</a>
@endif