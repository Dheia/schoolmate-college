@if($crud->hasAccess('list'))
	@if($entry->verified)	
		@if(!$entry->has_parent_credential)
			<a href="{{ url('admin/parent-user/' . $entry->getKey() . '/portal/enable') }}" class="btn btn-xs btn-danger action-btn" title="Activate Portal"><i class="fa fa-key"></i></a>
		@else
			@if(! $entry->parentCredential->active)
				<a href="{{ url('admin/parent-user/' . $entry->getKey() . '/portal/enable') }}" class="btn btn-xs btn-danger action-btn" title="Activate Portal"><i class="fa fa-key"></i></a>
			@else
				<a href="{{ url('admin/parent-user/' . $entry->getKey() . '/portal/disable') }}" class="btn btn-xs btn-success action-btn" title="Revoke Access to Portal"><i class="fa fa-key"></i> </a>
			@endif
		@endif
	@endif
@endif