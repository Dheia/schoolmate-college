{{-- regular object attribute --}}
@php
	$value = data_get($entry, $column['name']);

	if (is_array($value)) {
		$value = json_encode($value);
	}
@endphp

<span class="" style=" height: 20px; width: 100%; background-color: {{$value}}; border-radius: 5px; display: inline-block;"></span>