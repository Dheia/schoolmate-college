@push('crud_fields_styles')
			{{-- YOUR CSS HERE --}}
	<style>
		.d-none {
			display: none !important;
		}
	</style>
@endpush

{{-- FIELD JS - will be loaded in the after_scripts section --}}
@push('crud_fields_scripts')
		{{-- YOUR JS HERE --}}

		<script>
			$(document).ready(function () {
				function checkHierarchyType () {
					var h_type = $('select[name="hierarchy_type"] option:selected').val();

					if(h_type == "group") {
						$('.tax-code').addClass('d-none');
						$('.is-control-account').addClass('d-none');
						$('.made-up').addClass('d-none');
						$('.is-starting-balance').addClass('d-none');
						$('.starting-balance-type').addClass('d-none');
						$('.starting-balance').addClass('d-none');
					} else {
						$('.tax-code').removeClass('d-none');
						$('.is-control-account').removeClass('d-none');
						$('.made-up').removeClass('d-none');
						$('.is-starting-balance').removeClass('d-none');
						$('.starting-balance-type').removeClass('d-none');
						$('.starting-balance').removeClass('d-none');
					}
				}

				checkHierarchyType();

				$('select[name="hierarchy_type"]').on('change', function () {
					checkHierarchyType();
				});
			});
		</script>
@endpush