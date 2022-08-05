{{-- regular object attribute --}}
@php
	$value = $entry->{$column['name']};
@endphp

<span>
	{!! \App\Models\CashAccount::select('credit_limit')->find($entry->id)->credit_limit !!}
	{{ (array_key_exists('prefix', $column) ? $column['prefix'] : '').str_limit(strip_tags($value), array_key_exists('limit', $column) ? $column['limit'] : 50, "[...]").(array_key_exists('suffix', $column) ? $column['suffix'] : '') }}
</span>
