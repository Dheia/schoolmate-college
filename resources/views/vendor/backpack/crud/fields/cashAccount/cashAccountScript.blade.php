@push('crud_fields_styles')
	<style>
		.d-none {
			display: none !important;
		};
	</style>
@endpush

{{-- FIELD JS - will be loaded in the after_scripts section --}}
@push('crud_fields_scripts')
	<script>
		$(document).ready(function () {

			$('input[name="starting_balance"]').wrap('<div class="input-group"></div>');
			$('input[name="starting_balance"').after('<div class="input-group-addon" id="currencyAddOn">.</div>');

			var is_bank_maintained = false;
			var is_starting_balance = false;

			function checkbox () {
				is_bank_maintained = $("#is_bank_maintained").is(":checked");
				is_starting_balance = $("#is_starting_balance").is(":checked");

				is_bank_maintained === true && is_starting_balance === true ? $('.desc').removeClass('d-none') : $('.desc').addClass('d-none');


			    is_bank_maintained === true ? $('.credit-limit').removeClass('d-none') : $('.credit-limit').addClass('d-none');
				is_starting_balance === true ? $('.starting-balance').removeClass('d-none') : $('.starting-balance').addClass('d-none');
			}

			checkbox();

			$("#is_bank_maintained").change(function() {
			    is_bank_maintained = this.checked;
			    checkbox();
			});

			$("#is_starting_balance").change(function() {
				is_starting_balance = this.checked;
				checkbox();
			});


			// // // // // // // //
			
			var timer;
			function getCurrency () {
				var currency = $('#select-currency option:selected').attr('data-currency');
				if(currency == undefined) {
					timer = setInterval(function () { getCurrency() }, 1000);;
				} else {
					$('#currencyAddOn').text(currency);
					$('input[name="currency"]').val(currency);
					clearInterval(timer);
				}
			}

			getCurrency();

			$('#select-currency').on('change', function () {
				getCurrency();
			});

		});
	</script>
@endpush