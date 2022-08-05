
@if($entry->encoding_status)
<a  href="{{ URL::to('admin/encode-grade/encode?template_id=' . $entry->template_id . '&subject_id='  . $entry->subject_id . '&section_id=' . $entry->section_id . '&term_type=' . $entry->term_type . '&school_year_id=' . $entry->school_year_id) }}" 
    class="btn btn-xs btn-default">
		<i class="fa fa-pencil"></i> Encode
</a>
@elseif(count($entry->allowed_employee) > 0)
	@if(in_array(backpack_auth()->user()->employee_id, $entry->allowed_employee->toArray()))
	<a  href="{{ URL::to('admin/encode-grade/encode?template_id=' . $entry->template_id . '&subject_id='  . $entry->subject_id . '&section_id=' . $entry->section_id . '&term_type=' . $entry->term_type . '&school_year_id=' . $entry->school_year_id) }}" 
	    class="btn btn-xs btn-default">
			<i class="fa fa-pencil"></i> Encode
	</a>
	@endif
@else
<a  href="javascript:void(0)" 
    class="btn btn-xs btn-default" title="Encoding is closed." disabled>
		<i class="fa fa-pencil"></i> Encode
</a>
@endif