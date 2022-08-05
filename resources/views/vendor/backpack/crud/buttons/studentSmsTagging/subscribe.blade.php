@if($crud->hasAccess('list') && config('settings.smsgroup') == null)
	<a href="{{ route('subscribe') }}" class="btn btn-default ladda-button"><i class="fa fa-plus"></i>&nbsp; Set Contact Group</a>
@endif