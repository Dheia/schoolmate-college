{{-- FIELD EXTRA CSS  --}}
{{-- push things in the after_styles section --}}

    {{-- @push('crud_fields_styles')
        <!-- no styles -->
    @endpush --}}

{{-- FIELD EXTRA JS --}}
{{-- push things in the after_scripts section --}}

@push('crud_fields_scripts')
<script>
    var levels = {!! json_encode($levels) !!};
    var tracks = {!! json_encode($tracks) !!};
    var terms  = {!! json_encode($terms) !!};

    var select_school_year  = $('select[name="school_year_id"]');
    var select_department   = $('select[name="department_id"]');
    var select_level        = $('select[name="level_id"]');
    var select_term         = $('select[name="term_type"]');
    var select_track        = $('select[name="track_id"]');
    var select_tuition      = $('select[name="tuition_id"]');
    var select_payment      = $('select[name="commitment_payment_id"]');
    var select_curriculum   = $('select[name="curriculum_id"]');

    var count = 0;


    function getLevel() {
        var department_id = select_department.find('option:selected').val();
        var options = "";
        $.each(levels, function (k, val) {
            if(val.department_id == department_id) {
                options += '<option value="' + val.id + '">' + val.year + '</option>';
            }
        });
        select_level.html(options);
    }

    function getTerm () {
        var term_id         = select_term.find('option:selected').val();
        var department_id   = select_department.find('option:selected').val();
        var level_id        = select_level.find('option:selected').val();
        var options         = "";

        $.each(terms, function (k, val) {
            if(department_id == val.department_id) {

                if(val.type == "FullTerm") {
                    options += '<option value="Full">Full</option>';
                    @if( $entry->department_id )
                        options += '<option value="Summer">Summer</option>';
                    @endif
                    select_term.html(options);
                    return false;
                }

                if(val.type == "Semester") {
                    $.each(val.ordinal_terms, function (key, val) {
                        options += '<option value="' + val + '">' + val + '</option>';
                    })
                    @if( $entry->department_id )
                        options += '<option value="Summer">Summer</option>';
                    @endif
                    select_term.html(options);
                    return false;
                }
            }
        });
    }

    function getTrack () {
        var track_id = select_department.find('option:selected').val();
        var level_id = select_level.find('option:selected').val();

        var options = "";
        $.each(tracks, function (k, val) {
            if(val.level_id == level_id) {
                options += '<option value="' + val.id + '">' + val.code + '</option>';
            }
        });
        select_track.html(options);
    }

    function getTuition () {
        select_tuition.html('');
        
        var school_year_id  = select_school_year.find('option:selected').val();
        var department_id   = select_department.find('option:selected').val();
        var level_id        = select_level.find('option:selected').val();
        var track_id        = select_track.find('option:selected').val();
        $.ajax({
            url: '/admin/enrollment/tuition-forms',
            type: 'get',
            data: {
                schoolyear_id : school_year_id,
                department_id : department_id,
                level_id : level_id,
                track_id : track_id  
            },
            success: function (data) {
                var options = '<option value="" selected disabled>Please Select Tuition</option>';
                $.each(data, function (key, val) {
                    options += '<option value="' + key + '">' + val + '</option>'
                });
                select_tuition.html(options);

                if(! count) {
                    @if($entry->tuition_id !== null)
                        select_tuition.find('option[value={{ $entry->tuition_id }}]').attr('selected', true);
                    @endif
                }
                count++;
            }
        });
    }

    select_school_year.find('option[value={{ $entry->school_year_id }}]').attr('selected', true);

    @if($entry->commitment_payment_id !== null)
    select_payment.find('option[value={{ $entry->commitment_payment_id }}]').attr('selected', true);
    @endif

    @if($entry->curriculum_id !== null)
    select_curriculum.find('option[value={{ $entry->curriculum_id }}]').attr('selected', true);
    @endif

    select_department.find('option[value={{ $entry->department_id }}]').attr('selected', true);
    getLevel();

    select_level.find('option[value={{ $entry->level_id }}]').attr('selected', true);
    getTerm();

    select_term.find('option[value={{ $entry->term_type }}]').attr('selected', true);
    getTrack();

    @if($entry->track_id !== null)
        select_track.find('option[value={{ $entry->track_id }}]').attr('selected', true);
    @endif

    getTuition();

    // ONCHANGE VALUE
    // 
    select_school_year.change(function () {
        getTuition();
    });

    select_department.change(function () {
        getLevel();
        getTrack()
        getTerm();
        getTuition();
    });

    select_level.change(function () {
        getTrack();
        getTerm();
        getTuition();
    });

    select_track.change(function () {
        getTuition();
    });
</script>
@endpush


{{-- Note: you can use @if ($crud->checkIfFieldIsFirstOfItsType($field, $fields)) to only load some CSS/JS once, even though there are multiple instances of it --}}
