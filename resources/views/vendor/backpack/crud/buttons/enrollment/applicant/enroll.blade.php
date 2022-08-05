@if($entry->invoice_no === null && $crud->hasAccess('list') && $entry->is_applicant)
	<br>
	<a style="margin-bottom: 5px; width: 100%" href="{{ url($crud->route . '/' . $entry->getKey() . '/enroll') }}" class="btn btn-primary btn-xs" title="Enroll"> <i class="fa fa-user-plus"></i>
		Enroll
	</a>	
@endif