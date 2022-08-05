<?php 
	$school_year_id = App\Models\GradeTemplate::where('id', $entry->template_id)->first()->school_year_id ?? '-'; 

	// CHECK IF EXIST THEN PASS THE EDIT ACTION METHOD IF NOT CREATE ACTION METHOD
	$isAssignCreated = App\Models\StudentSectionAssignment::where('school_year_id', $school_year_id)
															->where('section_id', $entry->section_id);
															// where('subject_id', $entry->subject_id);
	$assignId = $isAssignCreated->first()->id ?? null;
	$actionMethod = "create";
	if($isAssignCreated->exists()) {
		$actionMethod = "edit";
	}
?>

@if($isAssignCreated->exists())
	<a target="_blank" href="{{ URL::to('admin/student-section-assignment/' . $assignId . '/'. $actionMethod . '?redirect=' . $crud->getRoute() ) }}" class="btn btn-xs btn-default">
			<i class="fa fa-cog"></i> Manage 
	</a>
@else
<a target="_blank" href="{{ URL::to('admin/student-section-assignment/' . $actionMethod . '?schoolYear_id=' . $school_year_id . '&subject_id=' . $entry->subject_id . '&section_id=' . $entry->section_id . 'redirect=' . $crud->getRoute() ) }}" 
    class="btn btn-xs btn-default">
		<i class="fa fa-cog"></i> Manage 
</a>
@endif
