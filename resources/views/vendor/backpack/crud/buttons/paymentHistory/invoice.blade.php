@if($entry->invoice_no == null && $crud->hasAccess('list'))
	<a href="{{ url('admin/payment-history/' . $entry->getKey() . '/set-invoice') }}" class="btn btn-default btn-xs"><i class="fa fa-plus"></i>&nbsp; Set Invoice</a>
@else
	<button class="btn btn-default btn-xs" disabled><i class="fa fa-check"></i> Invoiced</button>
@endif