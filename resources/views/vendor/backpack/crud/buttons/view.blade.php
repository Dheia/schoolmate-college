{{-- This button is deprecated and will be removed in CRUD 3.5 --}}

@if ($crud->hasAccess('view'))
	<li>
		<a href="{{ url($crud->route.'/'.$entry->getKey() . '/open') }}" class="text-sm">
			<i class="fa fa-eye"></i> Open
		</a>
	</li>
@endif