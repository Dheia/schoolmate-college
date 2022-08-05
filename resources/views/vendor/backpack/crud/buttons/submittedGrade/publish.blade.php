@if($entry->submitted && $crud->hasAccess('list'))
	@if($entry->is_published)
		<a href="{{ backpack_url('submitted-grade/' . $entry->getKey() . '/unpublish') }}" class="btn btn-default btn-xs action-btn" title="Unpublish" style="background-color: red !important; color: #FFF !important;">
			<i class="fa fa-upload"></i>
		</a>	
	@else
		<a href="{{ backpack_url('submitted-grade/' . $entry->getKey() . '/publish') }}" class="btn btn-default btn-xs action-btn" title="Publish"><i class="fa fa-upload"></i></a>	
	@endif
@endif