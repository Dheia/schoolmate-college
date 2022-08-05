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
    var select_track        = $('select[name="track_id"]');
    var select_term         = $('select[name="term_type"]');
    var select_tuition      = $('select[name="tuition_id"]');
    var select_curriculum   = $('select[name="curriculum_id"]');

    getLevel();
    var old_level = "{{ old('level_id') }}";
    if(old_level) {
        select_level.val(old_level);
    }

    getTrack();
    var old_track = "{{ old('track_id') }}";
    if(old_track) {
        select_track.val(old_track);
    }

    getTerm();
    var old_term_type = "{{ old('term_type') }}";
    if(old_term_type) {
        select_term.val(old_term_type);
    }

    getTuition();
    var old_tuition = "{{ old('tuition_id') }}";
    if(old_tuition) {
        select_tuition.val(old_tuition);
    }

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
                // console.log(val);
                // if(val.type == "FullTerm") {
                //     options += '<option value="">Full Term</option>';
                //     select_term.html(options);
                //     return false;
                // }

                // if(val.type == "Semester") {
                //     options += '<option value="First">First Term</option>\
                //                 <option value="Second">Second Term</option>';
                //     select_term.html(options);
                //     return false;
                // }

                $.each(val.ordinal_terms, function (termIndex, ordinal_term) {
                    // console.log(ordinal_term);
                    options += '<option value="' + ordinal_term + '">' + ordinal_term + '</option>';
                });
                select_term.html(options);
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
                var options = '';
                $.each(data, function (key, val) {
                    options += '<option value="' + key + '">' + val + '</option>'
                });
                select_tuition.html(options);
            }
        });
    }

    select_school_year.change(function () {
        getTuition();
        getSubjects();
    });

    select_department.change(function () {
        getLevel();
        getTrack();
        getTerm();
        getTuition();
        getSubjects();
    });

    select_level.change(function () {
        getTrack();
        getTerm();
        getTuition();
        getSubjects();
    });

    select_track.change(function () {
        getTuition();
        getSubjects();
    });

    select_term.change(function () {
        getSubjects();
    });

    select_curriculum.change(function () {
        getSubjects();
    });

    @if($crud->getActionMethod() === "edit")
        console.log('edit');
        select_school_year.find('option[value="{{ $entry->school_year_id }}"]').attr('selected', true);
        select_department.find('option[value="{{ $entry->department_id }}"]').attr('selected', true);
        getLevel();
        select_level.find('option[value="{{ $entry->level_id }}"]').attr('selected', true);
        select_term .html('<option value="{{ $entry->term_type }}">{{ $entry->term_type }} Term</option>');

        var entry_term_type = '{{$entry->term_type}}';
        var entry_department = '{{$entry->department_id}}';
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

        getTrack();
        select_track.find('option[value="{{ $entry->track_id }}"]').attr('selected', true);
        $.when(getTuition()).then(function () {
            console.log('asdsad');
            select_tuition.find('option[value="{{ $entry->tuition_id }}"]').attr('selected', true);
        })
    @endif


    // GET SUBJECT LIST
    getSubjects();

    function getSubjects()
    {
        var curriculum_id = $('select[name="curriculum_id"]').find('option:selected').val() ?? null;
        var department_id = $('select[name="department_id"]').find('option:selected').val() ?? null;
        var level_id      = $('select[name="level_id"]').find('option:selected').val() ?? null;
        var track_id      = $('select[name="track_id"]').find('option:selected').val() ?? null;
        var term_type     = $('select[name="term_type"]').find('option:selected').val() ?? null;

        if(curriculum_id && department_id && level_id && term_type){
            hideSubjects();
            startLoading();

            $.ajax({
            url: 'subject-mapping/api/get-subjects',
            data: 
            {
                curriculum_id: curriculum_id,
                department_id: department_id,
                level_id: level_id,
                track_id: track_id,
                term_type: term_type
            },
            success: function (response) {
              if(response.status == 'success') {
                var tableRow = '';
                var no_data = '<tr><td colspan="5" class="text-center">'+response.message ?? 'No Subjects in Subject Mapping'+'</td></tr>';

                if(response.data) {
                    if(response.data.subjects.length > 0) {
                        console.log(response.data.subjects);
                        $.each(response.data.subjects, function (key, val) {
                            tableRow += '<tr>\
                                            <td>' + val.subject_title + '</td>\
                                            <td>' + val.subject_description + '</td>\
                                            <td>' + val.no_unit + '</td>\
                                        </tr>';
                       
                        });
                    }
                }

                $('#subjects-table tbody').empty();
                $('#subjects-table-body').append(tableRow ? tableRow : no_data);
                stopLoading();
                showSubjects();
              } 
              else if(response.status == 'error') {
                pNotifyError(response.message);
                stopLoading();
              } else {
                pNotifyError();
                stopLoading();
              }
            },
                error: function() {
                    pNotifyError();
                    stopLoading();
                }
          });

        } else {
            stopLoading();
            hideSubjects();
            $('#subjects-table tbody').empty();
        }
    }

    // Show Loading GIF
    function startLoading() {
        $('#loading-gif').css("display", "block");
    }

    // Hide Loading GIF
    function stopLoading() {
        $('#loading-gif').css("display", "none");
    }

    // Show Subjects
    function showSubjects() {
        $('#subjects-container').css("display", "block");
    }

    // Hide Subjects
    function hideSubjects() {
        $('#subjects-container').css("display", "none");
    }

    // PNotify Error
    function pNotifyError(error = null) {
        showSubjects();
        $('#subjects-table tbody').html('<tr><td colspan="5" class="text-center">Something Went Wrong, Please Try To Reload The Page.</td></tr>');
        new PNotify({
            title: "Error",
            text: error ? error : 'Something Went Wrong, Please Try Again.',
            type: 'error'
        });
    }
    
</script>
@endpush


{{-- Note: you can use @if ($crud->checkIfFieldIsFirstOfItsType($field, $fields)) to only load some CSS/JS once, even though there are multiple instances of it --}}
