<!-- select2 -->
@php
    $schoolYearActive = \App\Models\SchoolYear::active()->first();
    $teacherSubject = \App\Models\TeacherSubject::where('teacher_id', backpack_auth()->user()->employee_id)->pluck('section_id');
    $section_ids = $teacherSubject;
    $options = \App\Models\SectionManagement::whereIn('id', $section_ids)->get();

@endphp

{{--   <span class="input-group-addon">
      <button class="btn btn-default">+</button>
      <button class="btn btn-default">-</button>
  </span> --}}

<div class="input-group" style="width: 100%;">
    <span class="input-group-addon" id="basic-addon1" style="width: auto;  background: #d2d6de;">Section</span>
    <select name="section_id" id="section" class="form-control" style="width: 100%; display: unset;">
        @foreach($options as $option)
            @if(isset($_GET['section_id']))
                <option value="{{ $option->id }}"  {{ $_GET['section_id'] == $option->id ? 'selected=true' : null }}>{{ $option->name }}</option>
            @else
                <option value="{{ $option->id }}">{{ $option->name }}</option>
            @endif
        @endforeach
    </select>
</div>

{{-- </div> --}}

{{-- ########################################## --}}
{{-- Extra CSS and JS for this particular field --}}
{{-- If a field type is shown multiple times on a form, the CSS and JS will only be loaded once --}}

    {{-- FIELD CSS - will be loaded in the after_styles section --}}
    @push('crud_fields_styles')
    @endpush

    {{-- FIELD JS - will be loaded in the after_scripts section --}}
    @push('after_scripts')
        
        <script>

            var OriginalRoute = '{{ url()->current() }}/create';
            var OriginalRouteForReorder = '{{ url()->current() }}/reorder';
            var OriginalRouteForSetupGrade = '{{ url()->current() }}';
        
            function section () {
                $.ajax({
                    url: '/{{ $crud->route }}/get-subjects',
                    type: 'post',
                    data: {
                        section_id: $('#section option:selected').val()
                    },
                    success: function (response) {
                        var subject_select = $('select[name="subject_id"');
                        var options = '';
                        $.each(response, function (key, val) {
                            options += '<option value="' + val.id + '">' + val.subject_code + ' - ' + val.subject_title + '</option>';
                        });

                        subject_select.html(options);
                        period();
                        term();
                        refactorRoute();
                    }
                })
            } 

            function period () {
                $.ajax({
                    url: '/{{ $crud->route }}/get-periods',
                    type: 'post',
                    data: {
                        section_id: $('#section option:selected').val()
                    },
                    success: function (response) {
                        var period_select = $('select[name="period_id"]');
                        var options = '';
                        $.each(response, function (key, val) {
                            options += '<option value="' + val.id + '">' + val.name + '</option>'
                        });

                        period_select.html(options);
                        refactorRoute();
                    }
                });
            }

            function term () {
                $.ajax({
                    url: '/{{ $crud->route }}/get-terms',
                    type: 'post',
                    data: {
                        section_id: $('#section option:selected').val()
                    },
                    success: function (response) {
                        var term_select = $('select[name="term_type"]');
                        var options = '';
                        // $.each(response, function (key, val) {
                        //     options += '<option value="' + val.id + '">' + val.name + '</option>'
                        // });
                        if(response === "FullTerm") {
                            options += '<option value="Full">Full</option>'
                        } else if (response === "Semester") {
                            options += '<option value="First">First</option>'
                            options += '<option value="Second">Second</option>'
                        } else {
                            options += '<option selected disabled>-</option>'
                        }
                        term_select.html(options);
                        refactorRoute();
                    }
                });
            }

            section();

            function refactorRoute () {
                var anchor = $('a.ladda-button').eq(0);
                var anchorReorder = $('a.ladda-button').eq(1);
                var route = anchor.attr('href');

                var template = $('#template option:selected').val(); 
                var section = $('#section option:selected').val();
                var term = $('#term option:selected').val();
                var subject = $('#subject option:selected').val();
                var period = $('#period option:selected').val();

                                   anchor.attr('href', OriginalRoute + '?template_id=' + template + '&section_id=' + section + '&term_type=' + term + '&subject_id=' + subject + '&period_id=' + period);
                  anchorReorder.attr('href', OriginalRouteForReorder + '?template_id=' + template + '&section_id=' + section + '&term_type=' + term + '&subject_id=' + subject + '&period_id=' + period);
                $('#lookup').attr('href', OriginalRouteForSetupGrade + '?template_id=' + template + '&section_id=' + section + '&term_type=' + term + '&subject_id=' + subject + '&period_id=' + period);
            }
            
            
            $('#section').change(function () { section(); });

        </script>

    @endpush

{{-- End of Extra CSS and JS --}}
{{-- ########################################## --}}
