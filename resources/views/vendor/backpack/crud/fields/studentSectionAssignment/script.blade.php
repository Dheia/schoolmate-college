
{{-- {{ dd(get_defined_vars()) }} --}}


@push('crud_fields_styles')
        {{-- YOUR CSS HERE --}}
@endpush
{{-- FIELD JS - will be loaded in the after_scripts section --}}
@push('crud_fields_scripts')

	<script>
		var department 		= $('select[name="department_id"]');
		var level 			= $('select[name="level_id"]');
		var term 			= $('select[name="term_type"]');
		var departmentItems = null;

		function clear () {
			level.html('');
		}

		function watchDepartment () {
			// clear();

			var department_id 	= department.find('option:selected').val();
			var level_id 		= level.find('option:selected').val();
			if(department_id !== '') {
				$.ajax({
					url: '/admin/department/' + department_id + '/get',
					success: function (response) {
						departmentItems = response;
						var levelOptions = '';
						var trackOptions = "";
						var termOptions = "";
						// LEVEL
						$.each(response.levels, function (key, lvl) {
							levelOptions += '<option value="' + lvl.id + '" data-index="' + key + '">' + lvl.year + '</option>';
						});
						level.html(levelOptions);

						var termOptions;
						termOptions += '<optgroup label="" style="border-bottom: 1px solid #000; border-style: solid;">';
						$.each(response.term.ordinal_terms, function (termIndex, ordinal_term) {
							// console.log(ordinal_term);
							termOptions += '<option value="' + ordinal_term + '">' + ordinal_term + '</option>';
						});
						termOptions += '</optgroup>';
						termOptions += '<optgroup label="-------" style="border-bottom: 1px solid #000; border-style: solid;">\
									    	<option value="Summer">Summer</option>\
									  	</optgroup>';
						term.html(termOptions);

						getSection();

					}
				});
			}
		}

		function getSection () {
	    	var level = $('select[name="level_id"]');
	    	var level_id 		= level.find('option:selected').val();
	    	// alert(level_id);
	    	var section = $('select[name="section_id"]');
	        $.ajax({
	            url: '/{{ $crud->route }}/section',
	            data: {
	                level_id: level_id
	            },
	            success: function (response) {
	                var sectionOptions = '';
	                $.each(response, function (k, v) {
	                    sectionOptions += '<option value="' + v.id + '">' + v.name + '</option>'
	                });
	                section.html(sectionOptions);
	                @if($crud->getActionMethod() === "edit" || $crud->getActionMethod() === "clone")
	                    $('select[name="section_id"]').find('option[value="{{ $entry->section_id }}"]').attr('selected', true);
	                @endif
	            }
	        });
	    }
	    

		@if($crud->getActionMethod() === "create")
	        watchDepartment();
			department.change(function () { watchDepartment(); });

			
			level.change(function () { getSection(); });
	    @endif
	    // level.change(function () {  getSection(); });

	    @if($crud->getActionMethod() === "edit" || $crud->getActionMethod() === "clone")
	        @if($section->level)
	        	$('select[name="department_id"]').find('option[value="{{ $section->level->department_id }}"]').attr('selected', true);
	        @endif

	        var entry_department = {!! json_encode($department) !!};
	        var termOptions = '';
         	var entry_term_type = '{{$entry->term_type}}';
         	// console.log(termOptions);
         	termOptions += '<optgroup label="" style="border-bottom: 1px solid #000; border-style: solid;">';
			$.each(entry_department.term.ordinal_terms, function (termIndex, ordinal_term) {
				console.log(termIndex);
				// termOptions += '<option value="' + ordinal_term + '">' + ordinal_term + '</option>';
				if(entry_term_type == ordinal_term)
                {
                    termOptions += '<option value="' + ordinal_term + '" selected>' + ordinal_term + '</option>';
                }
                else
                {
                    termOptions += '<option value="' + ordinal_term + '">' + ordinal_term + '</option>';
                }
			});
			termOptions += '</optgroup>';
			termOptions += '<optgroup label="-------" style="border-bottom: 1px solid #000; border-style: solid;">\
						    	<option value="Summer">Summer</option>\
						  	</optgroup>';
			// console.log(termOptions);
			term.html(termOptions);

	        $('select[name="level_id"]').find('option[value="{{ $level->id }}"]').attr('selected', true);
	        department.change(function () { watchDepartment(); });
			level.change(function () { getSection(); });
	    @endif 

	</script>

@endpush