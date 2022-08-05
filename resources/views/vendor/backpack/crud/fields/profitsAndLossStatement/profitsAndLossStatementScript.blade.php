@push('crud_fields_styles')
	<style>
		.d-none {
			display: none !important;
		}
	</style>
@endpush

{{-- FIELD JS - will be loaded in the after_scripts section --}}
@push('crud_fields_scripts')
	<script>
		$(document).ready(function () {

			//  DEFAULT GROUP SELECTED
			$('#name').wrap('<div class="input-group col-md-12">\
							  </div>');
			$('#name').before('<div class="input-group-addon d-none">Less:</div>')

			$('#expenses').change(function () {
				if($(this).is(':checked')) {
					$('.input-group-addon').removeClass('d-none');
				} else {
					console.log("UNCHECKED");
					$('.input-group-addon').addClass('d-none');
				}
			});


			function checkHierarchy () {
				var hType = $('#hierarchy-type option:selected').val();

				//  IF HIERARCHY SELECTED IS GROUP THEN;
				if(hType == 'group') {

					$('.expenses').removeClass('d-none');

					if($('#group option:selected').val() == "") {
						
						$('.input-group-addon').addClass('d-none');
						$('.tax-code').addClass('d-none');
						$('.expenses').removeClass('d-none');
					} else {
						$('.input-group-addon').addClass('d-none');
						$('.tax-code').addClass('d-none');
						$('.expenses').addClass('d-none');

						$('#expenses').prop('checked', false);
					}
				} else if (hType == 'account') {

					$('.tax-code').removeClass('d-none');
					$('.expenses').addClass('d-none');

				} else {
					return false;
				}
			}

			// checkHierarchy();

			$('#hierarchy-type').on('change', function () {
				checkHierarchy();
			});

			$('#group').on('change', function () {
				checkHierarchy();
			})

		});
	</script>
@endpush