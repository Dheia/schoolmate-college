@if($entry->qbo_id == null)
	<li><a href="{{ url('admin/employee/' . $entry->getKey() . '/reset-password') }}" class="text-sm"><i class="fa fa-unlock-alt "></i> Reset Password</a></li>
@endif