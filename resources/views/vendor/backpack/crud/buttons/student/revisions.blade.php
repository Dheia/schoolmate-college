@if ($crud->hasAccess('revisions') && count($entry->revisionHistory))
	<li>
	    <a style="margin-bottom: 5px; width: 100%;" href="{{ url($crud->route.'/'.$entry->getKey().'/revisions') }}" class="text-sm">
	    	<i class="fa fa-history" title="Review Revisions"></i> 
	    	{{ trans('backpack::crud.revisions') }}
		</a>
	<li>
@endif