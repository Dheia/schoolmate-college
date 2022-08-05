{{-- regular object attribute --}}
@php
	$value = data_get($entry, $column['name']);

	if (is_array($value)) {
		$value = json_encode($value);
	}
@endphp

@if($value == "1")
	<span class="label label-success">
		Active
	</span>
@else
	<span class="label label-default">
		Inactive
	</span>
@endif