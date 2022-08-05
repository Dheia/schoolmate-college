@if($crud->hasAccess('disableAccount') && $entry->studentCredential)
	@if($entry->studentCredential->is_disabled)
		<li>
			<a href="{{ backpack_url('enrollment/' . $entry->getKey() . '/enable-account') }}" class="text-sm action-btn" title="Enable Account">
				<i class="fa fa-unlock"></i>
				Enable Account			
			</a>
		</li>
	@endif
@endif