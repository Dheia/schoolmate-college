@if ($crud->hasAccess('create'))
	<a href="{{ url($crud->route.'/print') }}" class="btn btn-primary ladda-button" data-style="zoom-in"><span class="ladda-label">
        <i class="fa fa-print"></i> {{ ucfirst($crud->entity_name) }} Reports</span></a>
@endif