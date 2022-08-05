@if ($crud->hasAccess('publish') && $entry->total_unpublished_amount > 0)
    @if($entry->response_code == 'GR001' || $entry->response_code == 'GR002' || $entry->response_code == 'GR033')
		<a class="btn btn-xs btn-success" href="javascript:void(0)" onclick="publishPayment(this)"
            title="Publish"
            data-id="{{ $entry->getKey() }}"
            data-amount ="{{ $entry->amount }}"
            data-fee="{{ $entry->fee }}"
            data-total-payment="{{ $entry->total_payment }}"
            data-published-amount="{{ $entry->total_published_amount }}"
            data-unpublished-amount="{{ $entry->total_unpublished_amount }}"
            data-fullname="{{ $entry->student ? $entry->student->full_name : '-' }}"
            data-payment-method="{{ $entry->paymentMethod ? $entry->paymentMethod->name : '-' }}"
            data-route="{{ url($crud->route . '/' . $entry->getKey() . '/publish') }}"
            data-button-type="publishPayment">
			<i class="fa fa-plus"></i> Publish
		</a>
	@endif
@endif