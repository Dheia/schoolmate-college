
{{-- {{ dd(get_defined_vars()) }} --}}


@push('crud_fields_styles')
        {{-- YOUR CSS HERE --}}
@endpush
{{-- FIELD JS - will be loaded in the after_scripts section --}}
@push('crud_fields_scripts')
	<script type="text/javascript">

		if ( $('#majorLanguage').find('option:selected').val() === 'Other' ) {
			$('#other_language_specify').attr('disabled', false);
			$('#other_language_specify').attr('required', true);
		}

		$('#majorLanguage').change(function () {

			if($(this).find('option:selected').val() === 'Other') {
				$('#other_language_specify').attr('disabled', false);
				$('#other_language_specify').attr('required', true);
			} else {
				$('#other_language_specify').val('');
				$('#other_language_specify').attr('disabled', true);
				$('#other_language_specify').removeAttr('required');
			}
		});



		function watchEmergency () {
			var rToChild = $('select[name="emergencyRelationshipToChild"').val();
			if(rToChild == 'Other') {
				$('.emergency_contact_other_relation_ship_to_child .emergency_lastname, .emergency_firstname, .emergency_middlename, .emergencymobilenumber').removeClass('hidden');
			} else {
				$('.emergency_contact_other_relation_ship_to_child .emergency_lastname, .emergency_firstname, .emergency_middlename, .emergencymobilenumber').addClass('hidden');
			}
		}
		watchEmergency();
		$('select[name="emergencyRelationshipToChild"').change(function () {
			watchEmergency();
		});
	</script>

	<script>
		$(window).bind("load", function () {

    		setTimeout(function () {
					var department 		= $('select[name="department_id"]');
					var level 			= $('select[name="level_id"]');
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
								var old_lvl_id = '{{ $entry->level_id }}';
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
	

	{{-- FOR ADDRESS --}}
	<script type="text/javascript">
		const capitalize = (str) => {
		    return str.replace(/\w\S*/g, function(txt){
		        return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();
		    });
		}
		var action = "{{ $crud->getActionMethod() }}";

		var provinces,
			cities, 
			barangays;



		function init () {
			$('.select2-selection__rendered').css('margin-top', '0');
			
			

			

		}

		// init();

		$(window).bind("load", function () {
			var select2City 	= $('#city_municipality').select2({ sortResults: function(results) { return results.sort(); } }), 
				select2Barangay = $('#barangay').select2({ sortResults: function(results) { return results.sort(); } }),
				select2Province = $('#province').select2({ sortResults: function(results) { return results.sort(); } });


			$(document).ready(function () {
					// var targetNodes         = $("#province, #city_municipality, #barangay");
					// var MutationObserver    = window.MutationObserver || window.WebKitMutationObserver;
					// var myObserver          = new MutationObserver (mutationHandler);
					// var obsConfig           = { childList: true, characterData: true, attributes: true, subtree: true };

					// targetNodes.each (function () {
					//     myObserver.observe (this, obsConfig);
					// });

					// function mutationHandler (mutationRecords) {
					//     console.info ("mutationHandler:");

					//     mutationRecords.forEach (function (mutation) {
					//         console.log ('mutation ', mutation);
					//         console.log ('type ', mutation.type);
					//         $(mutation.target).trigger('change');
					//     });
					// }

					function getProvinces () {
						var options = '<option selected disabled>Please Select Province</option>';

						$.ajax({
							url: '/{{ $crud->route }}/api/provinces',
							success: function (response) {
								$.each(response.records, function (key, val) {
									options += '<option value="' + capitalize(val.provDesc.toLowerCase()) + '" province-code="' + val.provCode + '">' + capitalize(val.provDesc.toLowerCase()) + '</option>';
								})

								$('select[name="province"]').html(options);

								@if($crud->getActionMethod() === 'edit')
									select2Province.val('{{ ucwords(strtolower($entry->province)) }}');
									select2Province.trigger('change');
								@endif
							}
						})
						
					}

					getProvinces();

					function getCities () {
						var provinceCode = $('#province').find('option:selected').attr('province-code');

						$.ajax({
							url: '/{{ $crud->route }}/api/cities',
							data: {
								province_code: $('#province').find('option:selected').attr('province-code'),
							},
							success: function (response) {
								var options = '<option selected disabled>Please Select City/Municipality</option>';
								$.each(response, function (key, val) {
									if(val.provCode == provinceCode) {
										options += '<option value="' + capitalize(val.citymunDesc.toLowerCase()) + '" city-code="' + val.citymunCode + '">' + capitalize(val.citymunDesc.toLowerCase()) + '</option>';
									}
								});

								$('select[name="city_municipality"]').html(options);
								$('#city_municipality').closest('.form-group').find('.select2-chosen').text('-');
								@if($crud->getActionMethod() === 'edit')
									select2City.val('{{ ucwords(strtolower($entry->city_municipality)) }}');
									select2City.trigger('change');
								@endif
							}
						})
						
					}

					function getBarangay () {
						var cityCode = $('#city_municipality').find('option:selected').attr('city-code');

						$.ajax({
							url: '/{{ $crud->route }}/api/barangay',
							data: {
								city_code: $('#city_municipality').find('option:selected').attr('city-code')
							},
							success: function (response) {
								var options = '<option selected disabled>Please Select Barangay</option>';
								$.each(response, function (key, val) {
									if(val.citymunCode == cityCode) {
										options += '<option value="' + capitalize(val.brgyDesc.toLowerCase()) + '" barangay-code="' + val.brgyCode + '">' + capitalize(val.brgyDesc.toLowerCase()) + '</option>';
									}
								});

								$('select[name="barangay"]').html(options);
								$('#barangay').closest('.form-group').find('.select2-chosen').text('-');

								@if($crud->getActionMethod() === 'edit')
									select2Barangay.val('{{ ucwords(strtolower($entry->barangay)) }}');
									select2Barangay.trigger('change');
								@endif
							}
						});
						
					}

					select2Province.on('change', function(e) {
						$('#city_municipality').html('');
						$('#barangay').html('');
						
						$('#city_municipality').closest('.form-group').find('.select2-chosen').text('Loading...');
						$('#barangay').closest('.form-group').find('.select2-chosen').text('Loading...');
		                getCities();
		            });

		            select2City.on('change', function (e) {
		            	$('#barangay').html('');
		            	$('#barangay').closest('.form-group').find('.select2-chosen').text('Loading...');
		            	getBarangay();
		            });
			})
		});

	</script>
@endpush