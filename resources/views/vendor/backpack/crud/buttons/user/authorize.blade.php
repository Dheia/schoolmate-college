@if($crud->hasAccess('list'))	
	{{-- @if(!$entry->has_student_credential) --}}
		<a href="{{ backpack_url('user/' . $entry->getKey() . '/portal/enable') }}" class="btn btn-info btn-xs" title="Activate Portal"><i class="fa fa-key"></i></a>
	{{-- @else --}}
		{{-- <a href="{{ url('admin/student/' . $entry->getKey() . '/portal/disable') }}" class="btn btn-danger btn-xs" title="Revoke Access to Portal"><i class="fa fa-key"></i></a> --}}
	{{-- @endif --}}
@endif