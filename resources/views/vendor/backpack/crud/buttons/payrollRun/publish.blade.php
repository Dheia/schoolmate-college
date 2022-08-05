{{-- This button is deprecated and will be removed in CRUD 3.5 --}}

{{-- @if ($crud->hasAccess('publish')) --}}
@if($entry->status === "UNPUBLISH")
	<li>
		<a href="{{ url($crud->route.'/'.$entry->getKey() . '/publish') }}" class="text-sm">
			<i class="fa fa-check"></i> Publish
		</a>
	</li>
@endif
{{-- @endif --}}