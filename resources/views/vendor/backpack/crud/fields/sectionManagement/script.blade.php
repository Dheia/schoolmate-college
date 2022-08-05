
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
			var select_department 	= $('select[name="department_id"]');
			var select_level 		= $('select[name="level_id"]');
			var select_track 		= $('select[name="track_id"]');


			function watchDepartment () {
				// clear();

				var department_id 	= select_department.find('option:selected').val();
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
							});
							select_level.html(levelOptions);
							level();

						}
					});
				}
			}

			function level () {
				@if($crud->getActionMethod() === "edit" && isset($entry->level_id))
					$('select[name="level_id"]').find('option[value="{{ $entry->level_id }}"]').attr('selected', true);
				@endif
				disableButton();
				level_id = select_level.find('option:selected').val();
				$.ajax({
					url: '/{{ $crud->route }}/get-tracks',
					data: {
						level_id: level_id
					},
					success: function (response) {
						var options = '';
						$.each(response, function (key, val) {
							options += '<option value="' + val.id + '">' + val.code + '</option>'
						});
						select_track.html(options);
						@if($crud->getActionMethod() === "edit" && isset($entry->track_id))
							$('select[name="track_id"]').find('option[value="{{ $entry->track_id }}"]').attr('selected', true);
						@endif
						enableButton();

					}
				});
				
			} 
			// level();

			@if($crud->getActionMethod() === "create")
				watchDepartment();
				select_department.change(function () { watchDepartment(); });
				select_level.change(function () { level(); });
			@endif

			@if($crud->getActionMethod() === "edit")
		        @php
		            $level = App\Models\YearManagement::where('id', $entry->level_id)->with('tracks')->first();
		            $level_id = $level->id;
		        @endphp

		        @if($level)
			        $('select[name="department_id"]').find('option[value="{{ $level->department_id }}"]').attr('selected', true);
		        	watchDepartment();
		        	setTimeout(function () {
					  $('select[name="track_id"]').find('option[value="{{ $entry->track_id }}"]').attr('selected', true);
					}, 1000);
		        @endif
		        
		        select_department.change(function () { watchDepartment(); });
				select_level.change(function () { level(); });
		    @endif 
    	});
	</script>

@endpush