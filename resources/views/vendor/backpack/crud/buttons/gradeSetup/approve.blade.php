@if(backpack_user()->hasRole('School Head') && $entry->is_approved === "Pending")
	<a href="/{{ $crud->route . '/approve/' . $entry->id }}" class="btn btn-xs btn-default" data-style="zoom-in">
	    <i class="fa fa-check"></i> &nbsp;Approve
	</a>
@endif