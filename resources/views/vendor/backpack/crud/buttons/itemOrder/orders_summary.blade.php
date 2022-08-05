
@if(request('date'))
	<a href="{{ url('admin/item-order-summary?date='.request('date')) }}" class="btn btn-primary">
		<i class="fa fa-eye"></i>&nbsp; View Item Order Summary
	</a>
@else
	<a href="{{ url('admin/item-order-summary') }}" class="btn btn-primary">
		<i class="fa fa-eye"></i>&nbsp; View Item Order Summary
	</a>
@endif