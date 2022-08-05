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

    var select_employee     = $('select[name="employee_id"]');
    var select_schoolYear   = $('select[name="school_year_id"]');
    var select_department   = $('select[name="department_id"]');
    var select_term         = $('select[name="term_type"]');
    var select_level        = $('select[name="level_id"]');
    var select_section      = $('select[name="section_id"]');
    var select_subject      = $('select[name="subject_id"]');

    var employee_id         = select_employee.find('option:selected').val();
    var school_year_id      = select_schoolYear.find('option:selected').val();
    var department_id       = select_department.find('option:selected').val();
    var term_type           = select_term.find('option:selected').val();
    var level_id            = select_level.find('option:selected').val();
    var section_id          = select_section.find('option:selected').val();
    var subject_id          = select_subject.find('option:selected').val();

    function getTerm () {
        term_type         = select_term.find('option:selected').val();
        department_id   = select_department.find('option:selected').val();
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

    function getLevels () {
        employee_id         = select_employee.find('option:selected').val();
        school_year_id      = select_schoolYear.find('option:selected').val();
        department_id       = select_department.find('option:selected').val();
        term_type           = select_term.find('option:selected').val();

        $.ajax({
            url: '/{{ $crud->route }}/get-levels',
            data: {
                employee_id: employee_id,
                school_year_id: school_year_id,
                department_id: department_id,
                term_type: term_type,
            },
            success: function (response) {
                var options = '';
                $.each(response, function (key, val) {
                    options += '<option value="' + val.id + '">' + val.year + '</option>'
                });
                select_level.html(options);

                @if($crud->getActionMethod() === "edit" && isset($entry->level_id))
                    $('select[name="level_id"]').find('option[value="{{ $entry->level_id }}"]').attr('selected', true);
                @endif

                if(!response.length > 0) {
                    select_level.html('<option>-</option>');
                    select_section.html('<option>-</option>');
                    select_subject.html('<option>-</option>');
                } else {
                    getSections();
                }
            }
        });
    }

    function getSections () {
        employee_id         = select_employee.find('option:selected').val();
        school_year_id      = select_schoolYear.find('option:selected').val();
        department_id       = select_department.find('option:selected').val();
        term_type           = select_term.find('option:selected').val();
        level_id            = select_level.find('option:selected').val();

        $.ajax({
            url: '/{{ $crud->route }}/get-sections',
            data: {
                employee_id: employee_id,
                school_year_id: school_year_id,
                department_id: department_id,
                term_type: term_type,
                level_id: level_id,
            },
            success: function (response) {
                var options = '';
                $.each(response, function (key, val) {
                    options += '<option value="' + val.id + '">' + val.name + '</option>'
                });
                select_section.html(options);

                @if($crud->getActionMethod() === "edit" && isset($entry->section_id))
                    $('select[name="section_id"]').find('option[value="{{ $entry->section_id }}"]').attr('selected', true);
                @endif

                if(!response.length > 0) {
                    select_section.html('<option>-</option>');
                } else {
                    getSubjects();
                }
            }
        });
    }

    function getSubjects () {
        employee_id         = select_employee.find('option:selected').val();
        school_year_id      = select_schoolYear.find('option:selected').val();
        department_id       = select_department.find('option:selected').val();
        term_type           = select_term.find('option:selected').val();
        level_id            = select_level.find('option:selected').val();
        section_id          = select_section.find('option:selected').val();

        $.ajax({
            url: '/{{ $crud->route }}/get-subjects',
            data: {
                employee_id: employee_id,
                school_year_id: school_year_id,
                department_id: department_id,
                term_type: term_type,
                level_id: level_id,
                section_id: section_id,
            },
            success: function (response) {
                var options = '';
                $.each(response, function (key, val) {
                    options += '<option value="' + val.id + '">' + val.subject_code + '</option>'
                });
                select_subject.html(options);

                @if($crud->getActionMethod() === "edit" && isset($entry->subject_id))
                    $('select[name="subject_id"]').find('option[value="{{ $entry->subject_id }}"]').attr('selected', true);
                @endif

                if(!response.length > 0) {
                    select_subject.html('<option>-</option>');
                }
            }
        });
    }
    
    getTerm();
    getLevels();

    select_employee.change(function () {
        employee_id = select_employee.find('option:selected').val();
        getLevels();
    });

    select_schoolYear.change(function () {
        getLevels();
    });

    select_department.change(function () {
        getTerm();
        getLevels();
    });

    select_level.change(function () {
        getSections();
    });

    select_section.change(function () {
        getSubjects();
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
