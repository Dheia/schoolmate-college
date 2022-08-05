@if($crud->hasAccess('list') && config('settings.smsgroup') == null)
	<a href="{{ route('create.groups') }}" class="btn btn-default ladda-button"><i class="fa fa-plus"></i>&nbsp; Set Contact Group</a>
@endif