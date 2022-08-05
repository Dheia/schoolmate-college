@php
	$levels = App\Models\YearManagement::with(['courses'])
										->with(['tracks' => function ($query) {
											$query->where('active', 1);
										}])
										->with(['department' => function ($query) {
											$query->with('term');
										}])
										->get();

	// dd($levels);
@endphp


{{-- {{ dd(get_defined_vars()) }} --}}
@push('crud_fields_styles')
        {{-- YOUR CSS HERE --}}
@endpush
{{-- FIELD JS - will be loaded in the after_scripts section --}}
@push('crud_fields_scripts')

	<script>
		{{-- Show the errors, if any --}}

		$(window).bind("load", function () {

			var levels = {!! json_encode($levels) !!};
			// console.log(levels);

			var select_department 	= $('select[name="department_id"]');
			var select_level 		= $('select[name="level_id"]');
			var select_term 		= $('select[name="term_id"]');
			var select_term_type 	= $('select[name="term_type"]');
			var select_track 		= $('select[name="track_id"]');
			var select_course 		= $('select[name="course_id"]');

			function watchDepartment () {
				var department_id = select_department.find('option:selected').val();

				var options = '';
				$.each(levels, function (key, level) {
					
					if(level.department_id == department_id) {
						@if($crud->getActionMethod() === 'edit')
							var old_level_id = '{{ $entry->level_id }}';
							if(old_level_id == level.id) {
								options += '<option value="' + level.id + '" selected>' + level.year + '</option>';
							} else {
								options += '<option value="' + level.id + '">' + level.year + '</option>';
							}
						@else
							options += '<option value="' + level.id + '">' + level.year + '</option>';
						@endif
						select_term.html('<option value="' + level.department.term.id + '">' + level.department.term.type + '</option>');

						if(level.department.course) {
							$('.course-wrapper').removeClass('hidden');
							$('.track-wrapper').addClass('hidden');
						} else {
							$('.track-wrapper').removeClass('hidden');
							$('.course-wrapper').addClass('hidden');
						}


						var termOptions = ''; 
						if(level.department.department_term_type == "Semester") {
							$.each(level.department.term.ordinal_terms, function (key, val) {
								@if($crud->getActionMethod() === 'edit')
									var old_term_type = '{{ $entry->term_type }}';

									if(old_term_type == val) {
										termOptions += '<option value="' + val + '" selected>' + val + '</option>'
									} else {
										termOptions += '<option value="' + val + '">' + val + '</option>'
									}
								@else
									termOptions += '<option value="' + val + '">' + val + '</option>'
								@endif
							})
						} else {
							termOptions += '<option val="Full">Full</option>'
						}
						select_term_type.html(termOptions);


					}
				});

				select_level.html(options);

				watchLevel();
			}

			function watchLevel ()
			{
				var level_id = select_level.find('option:selected').val();
				var track_id = select_track.find('option:selected').val();
				var options = '';

				$.each(levels, function (key, level) {
					if(level.id == level_id) {
						// console.log('level ', level);
						// If Department Type Is "COURSE"
						if(level.department.course) {
							$.each(level.courses, function(cKey, course) {
								@if($crud->getActionMethod() === 'edit') 
									var old_course_id = '{{ $entry->course_id ?? null }}';
									if(old_course_id == course.id) {
										options += '<option value="' + course.id + '" selected>' + course.acronym + ' - ' + course.name + '</option>'
									} else {
										options += '<option value="' + course.id + '" >' + course.acronym + ' - ' + course.name + '</option>'
									}
								@else 
									options += '<option value="' + course.id + '">' + course.acronym + ' - ' + course.name + '</option>'
								@endif
							});
							select_course.html(options);
						}
						else { // If Department Type is "TRACK"
							$.each(level.tracks, function(tKey, track) {
								// console.log('track ', track);
								@if($crud->getActionMethod() === 'edit') 
									var old_track_id = '{{ $entry->track_id ?? null }}';
									if(old_track_id == track.id) {
										options += '<option value="' + track.id + '" selected>' + track.code + '</option>'
									} else {
										options += '<option value="' + track.id + '" >' + track.code + '</option>'
									}
								@else 
									options += '<option value="' + track.id + '">' + track.code + '</option>'
								@endif
							});
							select_track.html(options);
						}

					}
				
				});
			}

			watchDepartment();	

			select_department.change(function () { watchDepartment(); });
			select_level.change(function () { watchLevel(); });
		});

		@if ($crud->groupedErrorsEnabled() && $errors->any())

		@endif

	</script>

@endpush