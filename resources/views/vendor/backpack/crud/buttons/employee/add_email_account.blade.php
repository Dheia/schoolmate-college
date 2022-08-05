@if(!$entry->has_webmail)
	<li><a href="{{ url('admin/employee/' . $entry->getKey() . '/create-email') }}" class="text-sm"><i class="fa fa-plus"></i> Create Email</a></li>
@endif