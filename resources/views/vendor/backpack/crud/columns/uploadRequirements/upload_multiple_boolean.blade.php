{{-- converts 1/true or 0/false to yes/no/lang --}}
@php
    $value = data_get($entry, $column['name']);
@endphp
<span data-order="{{ $column['name'] }}">
	@if (is_array($value))
        @if ( count($value) > 0 )
            {{ Lang::has('backpack::crud.yes')?trans('backpack::crud.yes'):'Yes' }}
        @else
            {{ Lang::has('backpack::crud.no')?trans('backpack::crud.no'):'No' }}
        @endif
    @else
            {{ Lang::has('backpack::crud.no')?trans('backpack::crud.no'):'No' }}
    @endif
</span>
