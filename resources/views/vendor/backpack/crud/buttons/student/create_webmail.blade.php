{{-- @if(!$entry->has_webmail) --}}
	<li><a href="{{ url('admin/student/' . $entry->getKey() . '/create-email') }}" class="text-sm" title="Create webmail account"><i class="fa fa-plus"></i> Create Email Account</a></li>
{{-- @endif --}}