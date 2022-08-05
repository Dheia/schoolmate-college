<!-- select2 -->
@php

    $emp = \App\Models\Employee::where('id', backpack_auth()->user()->employee_id)->first(); 
    $teacher_assign_subject_id = \App\Models\TeacherAssignment::where('employee_id', $emp->employee_id)->pluck('subject_id');
    $options = \App\Models\SubjectManagement::find($teacher_assign_subject_id);
    // dd($options);
@endphp
{{--   <span class="input-group-addon">
      <button class="btn btn-default">+</button>
      <button class="btn btn-default">-</button>
  </span> --}}

<div class="input-group" style="width: 100%">
    <span class="input-group-addon" id="basic-addon1" style="width: auto;  background: #d2d6de;">Subject</span>
    <select name="subject_id" id="subject" class="form-control" style="width: 100%; display: unset;">
        @foreach($options as $option)
            @if(isset($_GET['subject_id']))
                <option value="{{ $option->id }}"  {{ $_GET['subject_id'] == $option->id ? 'selected=true' : null }}>{{ $option->subject_code }}</option>
            @else
                <option value="{{ $option->id }}">{{ $option->subject_code }}</option>
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

            var OriginalRouteForEncode = '{{ url()->current() }}/encode';

            function refactorRoute () {
                var anchor        = $('a.ladda-button').eq(0);
                var anchorReorder = $('a.ladda-button').eq(1);
                var route = anchor.attr('href');

                var schoolYear = $('#schoolYear option:selected').val(); 
                var section = $('#section option:selected').val();
                var subject = $('#subject option:selected').val();

                // anchor.attr('href', OriginalRoute + '?schoolYear_id=' + schoolYear + '&section_id=' + section + '&subject_id=' + subject);
                // anchorReorder.attr('href', OriginalRouteForReorder + '?schoolYear_id=' + schoolYear + '&section_id=' + section + '&subject_id=' + subject);
                $('#lookup').attr('href', OriginalRouteForEncode + '?schoolYear_id=' + schoolYear + '&section_id=' + section + '&subject_id=' + subject); 
                
            }
            
            refactorRoute();
            
            $('#subject').change(function () {
                refactorRoute();
            });

        </script>

    @endpush

{{-- End of Extra CSS and JS --}}
{{-- ########################################## --}}
