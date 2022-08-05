
@if($crud->hasAccess('transfer_class'))
	@if(!count($entry->submitted_grades) > 0)
		<a href="#" data-toggle="modal" data-target="#transferClassModal" class="btn btn-xs btn-success action-btn" title="Transfer Class" data-id="{{$entry->getKey()}}" data-section="{{$entry->section_name}}" data-subject="{{$entry->code_subject_name}}" data-level="{{$entry->level_name}}" data-track="{{$entry->track_name}}" 
			data-label="You are about to transfer the class for {{$entry->code_subject_name}} of {{$entry->level_name}} {{$entry->track_name}} - {{$entry->section_name}} to">
			<i class="fa fa-exchange"></i> Transfer Class
		</a>
	@endif
@endif