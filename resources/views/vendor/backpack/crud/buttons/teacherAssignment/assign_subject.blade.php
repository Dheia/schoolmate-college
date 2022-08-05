@if($crud->hasAccess('list'))
	<a id="teacher-{{ $entry->id }}" href="javascript:void(0)" class="btn btn-xs btn-default"><i class="fa fa-plus"></i> Assign Subjects</a>
@endif
{{-- {{ backpack_url('teacher-subject') . '?teacher_id=' . $entry->id }} --}}
<script>
	$("#teacher-{{ $entry->id }}").click(function() {
		@if($entry->user)
			// Check If Employee Has A Teacher Role
			@if(!$entry->employee->user->hasTeacherRole)			
		  	$.confirm({
			    title: 'Confirmation',
			    content: '<h5>Adding subject to this employee will add a teacher role in his permissions, are you sure you want to continue?</h5>',
			    buttons: {
			        confirm: function () {
			        	window.location.href = "{{ backpack_url('teacher-subject') . '?teacher_id=' . $entry->id }}";
			        },
			        cancel: function () {
			            // $.alert('Canceled!');
			        }
			    }
			});
			@else
				window.location.href = "{{ backpack_url('teacher-subject') . '?teacher_id=' . $entry->id }}";
			@endif
		@else
		$.alert({
		    title: 'Warning!',
		    content: '<h5>You must create user account first.</h5>',
		});
		@endif
	});
</script>