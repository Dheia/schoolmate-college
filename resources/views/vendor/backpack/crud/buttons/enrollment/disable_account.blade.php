@if($crud->hasAccess('disableAccount') && $entry->studentCredential)
	@if(!$entry->studentCredential->is_disabled)
		<li>
			<a href="{{ backpack_url('enrollment/' . $entry->getKey() . '/disable-account') }}" class="text-sm action-btn" title="Disable Account">
				<i class="fa fa-lock"></i>
				Disable Account	
			</a>
		</li>
	@endif
@endif