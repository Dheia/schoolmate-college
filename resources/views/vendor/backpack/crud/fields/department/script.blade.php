{{-- FIELD EXTRA CSS  --}}
{{-- push things in the after_styles section --}}

    {{-- @push('crud_fields_styles')
        <!-- no styles -->
    @endpush --}}

{{-- FIELD EXTRA JS --}}
{{-- push things in the after_scripts section --}}

@push('crud_fields_scripts')
	<script>
		var term_type = $( "#term_type" ).find('option:selected').val();
		if(term_type == 'Semester') {
	  		$( "#no_of_term" ).show();
	  	} else {
	  		$( "#no_of_term" ).hide();
	  		$('input[name="no_of_term"]').val('').removeAttr('value');
	  	}

		$( "#term_type" ).change(function() {
			term_type = $( "#term_type" ).find('option:selected').val();
		  	if(term_type == 'Semester') {
		  		$('input[name="no_of_term"]').val(2);
		  		$( "#no_of_term" ).show();
		  	} else {
		  		// $( "#no_of_term" ).val('').removeAttr('value');
		  		$('input[name="no_of_term"]').val('').removeAttr('value');
		  		$( "#no_of_term" ).hide();
		  	}
		});

	</script>
@endpush