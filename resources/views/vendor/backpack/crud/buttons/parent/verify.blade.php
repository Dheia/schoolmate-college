@if($crud->hasAccess('verify'))	
	@if(!$entry->verified)
			<a href="{{ url('admin/parent-user/' . $entry->getKey() . '/verify') }}" class="btn btn-xs btn-default action-btn" title="Verify Account"><i class="fa fa-check-circle"></i></a>
	@else
			{{-- <a href="{{ url('admin/parent-user/' . $entry->getKey() . '/portal/disable') }}" class="btn btn-xs btn-success action-btn" title="Revoke Access to Portal"><i class="fa fa-key"></i> </a> --}}
	@endif
@endif