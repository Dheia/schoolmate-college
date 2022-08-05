{{-- regular object attribute --}}
@php
	$value = data_get($entry, $column['name']);

	if (is_array($value)) {
		$value = json_encode($value);
	}
@endphp
@if($value)
	@if(count($value)>0)
		@foreach($value as $period)
			<p> <span> <i class="fa fa-check" style="color: green"></i> {{ $period->period_name }} </span> </p>
		@endforeach
	@else
		<p> <span> <i class="fa fa-exclamation" style="color: red"></i> No Grades Submitted </span> </p>
	@endif

@else
	<p><i class="fa fa-exclamation" style="color: red"></i> No Grades Submitted</p>
@endif