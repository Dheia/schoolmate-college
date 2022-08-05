@push('crud_fields_styles')
	<link href="{{ asset('vendor/adminlte/bower_components/select2/dist/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />
	<style>
		.d-none {
			display: none !important;
		}
	</style>
@endpush

{{-- FIELD JS - will be loaded in the after_scripts section --}}
@push('crud_fields_scripts')
	<script src="{{ asset('vendor/adminlte/bower_components/select2/dist/js/select2.min.js') }}"></script>
        <script>
            jQuery(document).ready(function($) {
                // trigger select2 for each untriggered select2 box
                $('#paid-from, #received-in').each(function (i, obj) {
                    if (!$(obj).hasClass("select2-hidden-accessible"))
                    {
                        $(obj).select2({
                            theme: "bootstrap"
                        });
                    }
                });
            });
    </script>

	<script>
		
		var currency_paid = $('#paid_amount').next().text(), 
			currency_received = $('#receive_in_amount').next().text();

		function cashAccount (idx, attr) {
			$.ajax({
				url: window.location.protocol + '//' + location.host + '/admin/api/cash-account/' + idx,
				success: function (data) {
					if(attr == "paid") {
						$('#paid_amount').next().text(data.currency);

						if(data.is_starting_balance && data.is_bank_maintained == false) {
							$('.paid_from_status, .paid_from_date').addClass('d-none');
						} else {
							$('.paid_from_status, .paid_from_date').removeClass('d-none');
							$('.paid_from_status select, .paid_from_date input').val('');
						}

					}

					if(attr == "received") {
						$('#receive_in_amount').next().text(data.currency);

						if(data.is_starting_balance && data.is_bank_maintained == false) {
							$('.received_in_status, .received_in_date').addClass('d-none');
							return false;
						}
						$('.received_in_status, .received_in_date').removeClass('d-none');
						$('.received_in_status select, .received_in_date input').val('');
					}
					checkCurrency();
				}
			});
		}
		
		$('#paid_amount, #receive_in_amount').each(function (k, obj) {
			$(this).wrapAll('<div class="input-group"></div>');
			$(this).after('<span class="input-group-addon">...</span>');
		});

		$('#paid-from, #received-in').each(function (key, obj) {
			var attr =  $(this).attr('attr-type'),
				  id =  $(this).val();
			cashAccount(id, attr);
		});


		function checkCurrency () {
			var p = $('#paid_amount').next().text(),
				r = $('#receive_in_amount').next().text()
			
			if(p === r) {
				$('#receive_in_amount').val($('#paid_amount').val())
			} else {
				$('#receive_in_amount').val('');
			}
		}

		$('#paid-from, #received-in').on('change', function () {
			var attr =  $(this).attr('attr-type'),
				  id =  $(this).val();
			cashAccount(id, attr);
		});

		$('#paid_amount, #receive_in_amount').on('keyup', function () {
			var p = $('#paid_amount').next().text(),
				r = $('#receive_in_amount').next().text()

			if(p === r) { 
				if($(this).attr('id') == "paid_amount") {
					$('#receive_in_amount').val($(this).val());
				} else {
					$('#paid_amount').val($('#receive_in_amount').val());
				}
			}
		});

		// cashAccount(select_id);
	</script>
@endpush