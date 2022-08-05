
{{-- {{ dd(get_defined_vars()) }} --}}


@push('crud_fields_styles')
        {{-- YOUR CSS HERE --}}
@endpush
{{-- FIELD JS - will be loaded in the after_scripts section --}}
@push('crud_fields_scripts')

	<script>
		$(window).bind("load", function () {

setTimeout(function () {
		var department 		= $('select[name="department_id"]');
		var level 			= $('select[name="grade_level_id"]');
		var track 			= $('select[name="track_id"]');
		var departmentItems = null;

		function clear () {
			level.html('');
			track.html('');
		}

		function watchDepartment () {

			clear();

			var department_id 	= department.find('option:selected').val();
			var level_id 		= level.find('option:selected').val();
			var track_id 		= track.find('option:selected').val();
			if(department_id !== '') {
				$.ajax({
					url: '/admin/department/' + department_id + '/get',
					success: function (response) {
						departmentItems = response;

						var levelOptions = '';
						var trackOptions = "";
						// LEVEL
						$.each(response.levels, function (key, lvl) {
							levelOptions += '<option value="' + lvl.id + '" data-index="' + key + '">' + lvl.year + '</option>';
							
							// TRACK
							if(lvl.tracks.length > 0) {
								$('.track-wrapper').removeClass('hidden');
								$.each(lvl.tracks, function (k, trck) {
										trackOptions += '<option value="' + trck.id + '">' + trck.code + '</option>';
								});
							} else {
								$('.track-wrapper').addClass('hidden');
							}

						});
						level.html(levelOptions);
						track.html(trackOptions);
						watchLevel();

					}
				});
			}
		}
		watchDepartment();
		department.change(function () { watchDepartment(); });

		function watchLevel () {
			var department_id 	= department.find('option:selected').val();
			var level_id 		= level.find('option:selected').val();
			var track_id 		= track.find('option:selected').val();
			$.each(departmentItems.levels, function (kDpt, lvlVal) {
				if(lvlVal.id == level_id)
				{
					if(lvlVal.tracks.length > 0)
					{
						var trackOptions = '';
						$.each(lvlVal.tracks, function(trckKey, trckVal) {
							$('.track-wrapper').removeClass('hidden');
							trackOptions += '<option value="' + trckVal.id + '">' + trckVal.code + '</option>';
						});
						track.html(trackOptions);
					} 
					else
					{
						$('.track-wrapper').addClass('hidden');
						track.html('');
					}
				}
			});

		}
		
		level.change(function () { watchLevel(); });

		@if($action === 'edit')

			// clear();

			// var department_id 	= department.find('option:selected').val();
			// var level_id 		= level.find('option:selected').val();
			// var track_id 		= track.find('option:selected').val();
			$.ajax({
				url: '/admin/department/' + {{ $entry->department_id }} + '/get',
				success: function (response) {
					departmentItems = response;

					var levelOptions = '';
					var trackOptions = "";

					var trackHasValue = false;
					var old_lvl_id = '{{ $entry->grade_level_id }}';
					var old_track_id = '{{ $entry->track_id }}';
					$.each(response.levels, function (key, lvl) {
						if(lvl.id == old_lvl_id) {
							levelOptions += '<option value="' + lvl.id + '" data-index="' + key + '" selected>' + lvl.year + '</option>';
						} else {
							levelOptions += '<option value="' + lvl.id + '" data-index="' + key + '">' + lvl.year + '</option>';
						}
						
						// TRACK
						var old_track_id = '{{ $entry->track_id }}';
						if(lvl.tracks.length > 0) {
							$.each(lvl.tracks, function (k, trck) {
								if(trck.id == old_track_id) {
									trackHasValue = true;
									trackOptions += '<option value="' + trck.id + '" selected>' + trck.code + '</option>';
								} else {
									trackOptions += '<option value="' + trck.id + '" selected>' + trck.code + '</option>';
								}
							});
						}
					});

					if(trackHasValue) {
						$('.track-wrapper').removeClass('hidden');
					} else {
						$('.track-wrapper').addClass('hidden');
					}

					level.html(levelOptions);
					track.html(trackOptions);
					watchLevel();
					if(trackHasValue) {
						track.find('option[value=' + old_track_id +']').attr('selected', true);
					}
				}
			});


		@endif


}, 1000);
});

	</script>

@endpush