@if($entry->qbo_id == null)
	<a href="{{ url('admin/employee/' . $entry->getKey() . '/qbo-connect') }}" class="btn btn-default btn-xs"><i class="fa fa-plus"></i>&nbsp; Connect</a>
@endif