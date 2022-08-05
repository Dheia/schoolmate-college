
@if ($crud->hasAccess('update') && !$entry->isActive)
	<a href="schoolyear/{{ $entry->getKey() }}/active" class="btn btn-xs btn-success action-btn" title="Set Active"> <i class="fa fa-bars"></i> {{-- Activate --}}</a>
@else
	<a href="schoolyear/{{ $entry->getKey() }}/deactive" class="btn btn-xs btn-danger action-btn" title="Deactivate"> <i class="fa fa-check-square-o"></i> {{-- Deactivate --}}</a>
@endif