{{-- @if(backpack_user()->hasRole('School Head') && $entry->is_approved === "Pending")
	<a href="{{ url('admin/enrollment/' . $entry->getKey() . '/edit') }}" class="btn btn-default btn-xs" title="Edit"><i class="fa fa-edit"></i></a>	
@endif --}}