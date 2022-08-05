
{{-- {{ dd(get_defined_vars()) }} --}}

@push('crud_fields_styles')
        {{-- YOUR CSS HERE --}}
@endpush

{{-- FIELD JS - will be loaded in the after_scripts section --}}
@push('crud_fields_scripts')

	<script>
        var count = 1;

        referredType();

        $('#medium').change(function () {
            referredType();
        });

        function referredType()
        {
            var medium = $('#medium option:selected').val();

            var referrer_contact = 	'<input type="text" name="contact" id="contact" class="form-control" placeholder="Contact Number">';
            
            if(medium == 'social media') {
                var referred_by = 	'<label>Social Media</label>' +
                                    '<select class="form-control" name="referred_by" id="referred_by" width="100%" required>\
                                        <option value="Facebook">Facebook</option>\
                                        <option value="Instagram">Instagram</option>\
                                        <option value="Twitter">Twitter</option>\
                                    </select>';
                $('#referred_by').empty();
                $('#referred_by').html(referred_by);
                $('#contact').hide();
            } else if(medium == 'search engine') {
                var referred_by = 	'<label>Search Engine</label>' +
                                    '<input type="text" name="referred_by" id="referred_by" class="form-control">';
                $('#referred_by').empty();
                $('#referred_by').html(referred_by);
                $('#contact').hide();
            } else if(medium == 'referred') {
                var referred_by  = 	'<label>Referred by</label>' +
                                    '<input type="text" name="referred_by" id="referred_by" class="form-control">';
                $('#referred_by').empty();
                $('#referred_by').html(referred_by);
                $('#contact').show();
            } else{
                var referred_by = 	'<label>Specify</label>' +
                                    '<input type="text" name="referred_by" id="referred_by" class="form-control">';
                $('#referred_by').empty();
                $('#referred_by').html(referred_by);
                $('#contact').show();
            }
        }
        count++;
	</script>

@endpush