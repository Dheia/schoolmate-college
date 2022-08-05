@push('crud_fields_scripts')
	<script>
		$(document).ready(function () {
			$('input[type="text"').on('keyup', function() {
				
	            var rubrics = JSON.parse($('#rubrics').val());
	            var total = 0;
	            var rubrics_arr = [];
				// rubrics_arr.push("Kiwi", "Lemon", "Pineapple");
	            $.each(rubrics, function( index, value ) {
	            	if(value.name != "" && value.points != "")
	            	{
	            		var numberRegex = /^[+]?\d+(\.\d+)?([eE][+-]?\d+)?$/;
						if(numberRegex.test(value.points)) {
						   	rubrics_arr.push(value);
		            		total += Number(value.points);
						}
						else{
							alert(value.points + ' is not a valid points.');
							console.log('invalid points');
						}
	            	}
				});
				$('#rubrics').val(JSON.stringify(rubrics_arr));
				$('input[name="total"').val(total);
	            
	        });

	        @if($crud->getActionMethod() === "edit")

				var rubrics = JSON.parse($('#rubrics').val());
	            var total = 0;
	            var rubrics_arr = [];
				// rubrics_arr.push("Kiwi", "Lemon", "Pineapple");
	            $.each(rubrics, function( index, value ) {
	            	if(value.name != "" && value.points != "")
	            	{
	            		var numberRegex = /^[+]?\d+(\.\d+)?([eE][+-]?\d+)?$/;
						if(numberRegex.test(value.points)) {
						   	rubrics_arr.push(value);
		            		total += Number(value.points);
						}
						else{
							alert(value.points + ' is not a valid points.');
							console.log('invalid points');
						}
	            	}
				});
				$('#rubrics').val(JSON.stringify(rubrics_arr));
				$('input[name="total"').val(total);

			@endif
		});
	</script>
@endpush