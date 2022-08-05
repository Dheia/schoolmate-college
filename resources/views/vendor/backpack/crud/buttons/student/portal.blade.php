@if($crud->hasAccess('list'))	
	@if(!$entry->has_student_credential)
			<a href="{{ url('admin/student/' . $entry->getKey() . '/portal/enable') }}" class="btn btn-xs btn-danger action-btn" title="Activate Portal"><i class="fa fa-key"></i></a>
	@else
			<a href="{{ url('admin/student/' . $entry->getKey() . '/portal/disable') }}" class="btn btn-xs btn-success action-btn" title="Revoke Access to Portal"><i class="fa fa-key"></i> </a>
	@endif
@endif