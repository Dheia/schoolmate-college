@php
    $departments = App\Models\Department::with('term')->get();
@endphp

@push('after_scripts')
<!-- no scripts -->
<script type="text/javascript">
    
    var departments = {!! json_encode($departments) !!};
    console.log(departments);

    $(document).ready(function () {

        function capitalize (s) {
            if (typeof s !== 'string') return ''
            return s.charAt(0).toUpperCase() + s.slice(1)
        }

        function getTerms () {
            var department = $('select[name="department_id"]');
            var term = $('select[name="term"]');

            if(departments) {
                $.each(departments, function (key, dVal) {
                    if(dVal.department_term_type == 'FullTerm') {

                        term.html('<option val="Full">Full</option>');

                    } else {

                        if(dVal.id == department.find('option:selected').val()) {
                            if(dVal.term) {
                                var options = '';
                                $.each(dVal.term.ordinal_terms, function (key, tVal) {
                                    options += '<option value="' + capitalize(tVal) + '">' + capitalize(tVal) + '</option>'
                                });
                                term.html(options);
                            }
                        }
                    }
                });
            }

        }

        getTerms();
        
        $('select[name="department_id"]').change(function () { getTerms() });



    })

</script>
@endpush