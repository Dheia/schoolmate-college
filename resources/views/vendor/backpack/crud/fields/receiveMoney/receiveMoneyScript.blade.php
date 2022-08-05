@push('crud_fields_styles')
	<style>
		.d-none {
			display: none !important;
		}
   /*     ul.list-unstyled { 
            height: 30px;
            width: 100%;
            border: 1px #000 solid;
        }
        ul.list-unstyled li { padding: 5px 10px; z-index: 2; }
        ul.list-unstyled li:not(.init) { float: left; width: 100%; display: none; background: #ddd; }
        ul.list-unstyled li:not(.init):hover, ul.list-unstyled li.selected:not(.init) { background: #09f; }
        li.init { cursor: pointer; }

        a#submit { z-index: 1; }*/

        </style>
@endpush

{{-- FIELD JS - will be loaded in the after_scripts section --}}
@push('crud_fields_scripts')
	<script>
		$(document).ready(function () {

			function cashAccount (idx) {
				$.ajax({
					url: window.location.protocol + '//' + location.host + '/admin/api/cash-account/' + idx,
					success: function (data) {
						if(data.is_starting_balance && data.is_bank_maintained == false) {
							$('.status, .received-date').addClass('d-none');
							return false;
						}
							$('.status, .received-date').removeClass('d-none');
							$('.status, .received-date').val('');
					}
				});
			}

			var select_id = $('select[name="received_in_id"] option:selected').val();

			cashAccount(select_id);

			$('select[name="received_in_id"]').on('change', function () {
				var id = this.value;
				cashAccount(id);
			});

			$('select[name="status"]').on('change', function () {
				var val = this.value;
				if(val === "pending") {
					$('input[name="received_date"').addClass('d-none');
				} else {
					$('input[name="received_date"').removeClass('d-none');
					$('input[name="received_date"').val('');
				}
			});

			$('#saveActions a').attr('href', window.location.protocol + '//' + location.host + '/admin/cash-account');


			// RECEIVEMONEY

			
			function getCurrency () {
				// var amnt = $('#amount');

				var cId = $('select[name="received_in_id"] option:selected').val();

				$.ajax({
					url: window.location.protocol + '//' + location.host + '/admin/api/cash-account/' + cId,
					success: function (data) {
						$('.currency').val(data.currency);
					}
				});
			}
			getCurrency();

			$('select[name="received_in_id"]').on('change', function () {
				getCurrency();
			});



			//  ACCOUNT
            $("#tree1").on("click", ".init", function() {
                $(this).closest("ul").children('li:not(.init)').toggle();
            });

            var allOptions = $("ul.list-unstyled").children('li:not(.init)');
            $("#tree1").on("click", "li:not(.init)", function() {
                allOptions.removeClass('selected');
                $(this).addClass('selected');
                $("ul").children('.init').html($(this).html());
                allOptions.toggle();
            });
		});
	</script>
@endpush