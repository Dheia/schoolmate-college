
@if ($entry->isActive)
	@if($entry->enable_enrollment)
		<br><a style="margin-bottom: 5px !important; width: 100%;"href="schoolyear/{{ $entry->getKey() }}/enrollment/deactive" class="btn btn-xs btn-danger" title="Disable Enrollment"> <i class="fa fa-power-off"></i> Disable Enrollment</a>
	@else
		<br><a style="margin-bottom: 5px !important; width: 100%;" href="schoolyear/{{ $entry->getKey() }}/enrollment/active" class="btn btn-xs btn-info" title="Enable Enrollment"> <i class="fa fa-power-off"></i> Enable Enrollment</a>
	@endif
@endif