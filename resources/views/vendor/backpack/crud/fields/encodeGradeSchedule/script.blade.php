{{-- FIELD EXTRA CSS  --}}
{{-- push things in the after_styles section --}}

    {{-- @push('crud_fields_styles')
        <!-- no styles -->
    @endpush --}}

{{-- FIELD EXTRA JS --}}
{{-- push things in the after_scripts section --}}

@push('crud_fields_scripts')
<script>
    var terms  = {!! json_encode($terms) !!};

    var select_department   = $('select[name="department_id"]');
    var select_term         = $('select[name="term_type"]');

    function getTerm () {
        var term_id         = select_term.find('option:selected').val();
        var department_id   = select_department.find('option:selected').val();
        var options         = "";
        $.each(terms, function (k, val) {
            if(department_id == val.department_id) {
                $.each(val.ordinal_terms, function (termIndex, ordinal_term) {
                    // console.log(ordinal_term);
                    options += '<option value="' + ordinal_term + '">' + ordinal_term + '</option>';
                });
                select_term.html(options);
            }
        });
    }
    
    getTerm();

    select_department.change(function () {
        getTerm();
    });

    @if($crud->getActionMethod() === "edit")
        select_department.find('option[value="{{ $entry->department_id }}"]').attr('selected', true);
        getTerm();
        select_term .html('<option value="{{ $entry->term_type }}">{{ $entry->term_type }} Term</option>');

        var entry_term_type = '{{$entry->term_type}}';
        var entry_department = {{$entry->department_id}};
        // Get The Terms Options
        var options         = "";
        $.each(terms, function (k, val) {
            if(entry_department == val.department_id) {
                $.each(val.ordinal_terms, function (termIndex, ordinal_term) {
                    if(entry_term_type == ordinal_term)
                    {
                        options += '<option value="' + ordinal_term + '" selected>' + ordinal_term + '</option>';
                    }
                    else
                    {
                        options += '<option value="' + ordinal_term + '">' + ordinal_term + '</option>';
                    }
                });
                select_term.html(options);
            }
        });
    @endif
</script>
@endpush


{{-- Note: you can use @if ($crud->checkIfFieldIsFirstOfItsType($field, $fields)) to only load some CSS/JS once, even though there are multiple instances of it --}}
