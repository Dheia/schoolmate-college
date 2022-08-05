@push('crud_fields_scripts')
	<script>
		$(document).ready(function () {

			var ec_er = $('#ec_er');
			var ss_er = $('#social_security_er');
			var ss_ee = $('#social_security_ee');
			var tc_er = $('#total_contribution_er');
			var tc_ee = $('#total_contribution_ee');

			$('#social_security_er, #ec_er').on('keyup', function () {
				console.log()
				tc_er.val(parseFloat(ss_er.val()) + parseFloat(ec_er.val()));
			})

		});
	</script>
@endpush