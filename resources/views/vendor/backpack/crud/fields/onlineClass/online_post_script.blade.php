@push('crud_fields_scripts')
	<script>
		$(document).ready(function () {
			if($("#cb_addQuiz").prop("checked") == true){
                // console.log("Checkbox is checked.");
                $('.quiz-field').show();
            }
            else if($("#cb_addQuiz").prop("checked") == false){
                // console.log("Checkbox is unchecked.");
                $('.quiz-field').hide();
            }

		   	$( "#cb_addQuiz" ).change(function() {
		   		if($(this).prop("checked") == true){
	                // console.log("Checkbox is checked.");
	                $('.quiz-field').show();
	            }
	            else if($(this).prop("checked") == false){
	                // console.log("Checkbox is unchecked.");
	                $('.quiz-field').hide();
	            }
			});
		});
	</script>
@endpush