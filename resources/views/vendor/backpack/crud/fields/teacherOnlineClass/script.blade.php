
{{-- {{ dd(get_defined_vars()) }} --}}


@push('crud_fields_styles')
        {{-- YOUR CSS HERE --}}
@endpush
{{-- FIELD JS - will be loaded in the after_scripts section --}}
@push('crud_fields_scripts')
	<script>
		function disableButton () {
			$('button[type="submit"]').attr('disabled', true);
		}

		function enableButton () {
			$('button[type="submit"]').removeAttr('disabled');
		}

		$(window).bind("load", function () {
			console.log('test');
			var select_level 	= $('select[name="level_id"]');
			var select_track 	= $('select[name="track_id"]');
			var select_section 	= $('select[name="section_id"]');
			var select_subject 	= $('select[name="subject_id"]');
			var select_term_type = $('select[name="term_type"]');

			async function level () {
				
				// IF EDIT ACTION
				@if($crud->getActionMethod() === "edit")
					select_level.find('option[value="{{ $entry->section->level_id }}"]').attr('selected', true);
				@endif

				disableButton();
				var level_id = select_level.find('option:selected').val();
				await $.ajax({
					url: '/{{ $crud->route }}/get-tracks',
					data: {
						level_id: level_id,
						teacher_id: {{ request()->teacher_id }}
					},
					success: function (response) {
						var options = '';
						$.each(response, function (key, val) {
							options += '<option value="' + val.id + '">' + val.code + '</option>'
						});
						select_track.html(options);
						track();
						enableButton();
					}
				});
			} level();

			async function track () {
				var level_id = select_level.find('option:selected').val();
				var track_id = select_track.find('option:selected').val();
				await $.ajax({
					url: '/{{ $crud->route }}/get-sections',
					data: {
						level_id: level_id,
						track_id: track_id,
						teacher_id: {{ request()->teacher_id }}
					},
					success: function (response) {
						var options = '';
						$.each(response, function (key, val) {
							options += '<option value="' + val.id + '">' + val.name + '</option>'
						});
						select_section.html(options);
						terms ();
					}
				});
			}

			function subject () {
				var section_id = select_section.find('option:selected').val();
				var term_type = select_term_type.find('option:selected').val();
				console.log(term_type);
				$.ajax({
					url: '/{{ $crud->route }}/get-subjects',
					data: {
						section_id: section_id,
						term_type: term_type,
						teacher_id: {{ request()->teacher_id }},
					},
					success: function (response) {
						var options = '';
						$.each(response, function (key, val) {
							options += '<option value="' + val.id + '">' + val.subject_code + ' - ' + val.subject_description + '</option>'
						});
						select_subject.html(options);
					}
				});
			}

			async function terms () {
				var term_type = select_term_type.find('option:selected').val();
				var section_id = select_section.find('option:selected').val();
				await $.ajax({
					url: '/{{ $crud->route }}/get-terms',
					data: {
						section_id: section_id,
						teacher_id: {{ request()->teacher_id }},
					},
					success: function (response) {
						var options = '';
						$.each(response, function (key, val) {
							options += '<option value="' + val + '">' + val + '</option>';
						});
						select_term_type.html(options);
						subject();
					}
				});
			}

			select_level.change(function () { level(); });
			select_track.change(function () { track(); });
			select_section.change(function () { subject(); });
			select_term_type.change(function () { subject(); });


			// EDIT 
			{{-- {{ dd(get_defined_vars()) }} --}}
			@if($crud->getActionMethod() === "edit")
				select_level.find('option[value="{{ $entry->level_id }}"]').attr('selected', true);
			@endif
    	});
	</script>

@endpush