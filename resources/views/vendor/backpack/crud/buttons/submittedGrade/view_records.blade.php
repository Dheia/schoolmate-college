@if($crud->hasAccess('list'))
	<a href="{{ backpack_url('submitted-grade/' . \Route::current()->parameter('school_year_id') . '/school-year/' . \Route::current()->parameter('department_id') . '/department/' . $entry->teacher_id . '/records') }}" class="btn btn-default btn-xs" title="Reopen"><i class="fa fa-eye"></i> View Records</a>	
@endif