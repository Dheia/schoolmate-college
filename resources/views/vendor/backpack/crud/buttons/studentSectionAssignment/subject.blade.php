<!-- select2 -->
@php
    
    $emp = \App\Models\Employee::where('id', backpack_auth()->user()->id)->first(); 
    $empId = null;

    if($emp === null) {
        echo "<script>alert('You are not  registered yet or.....')</script>";
        echo "<script>alert('You do not have account. and also tag the employee from user table')</script>";
        $empId = 0;
    } else {
        $empId = $emp->employee_id;
    }

    $teacher_assign_subject_id = \App\Models\TeacherAssignment::where('employee_id', $empId)->pluck('subject_id');

    if(count($teacher_assign_subject_id) == 0) {
        echo "<script>alert('No assigned teacher')</script>";
    }   

    $options = \App\Models\SubjectManagement::find($teacher_assign_subject_id);

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

            var OriginalRoute = '{{ url()->current() }}/create';
            var OriginalRouteForReorder = '{{ url()->current() }}/reorder';
            var OriginalRouteForSetupGrade = '{{ url()->current() }}';
        
            function refactorRoute () {
                var anchor = $('a.ladda-button').eq(0);
                var anchorReorder = $('a.ladda-button').eq(1);
                var route = anchor.attr('href');

                var schoolYear = $('#schoolYear option:selected').val(); 
                var section = $('#section option:selected').val();
                var subject = $('#subject option:selected').val();


                anchor.attr('href', OriginalRoute + '?schoolYear_id=' + schoolYear + '&section_id=' + section + '&subject_id=' + subject); 
                anchorReorder.attr('href', OriginalRouteForReorder + '?school_year_id=' + schoolYear + '&section_id=' + section + '&subject_id=' + subject);
            }
            
            refactorRoute();
            
            $('#subject').change(function () {
                refactorRoute();
            });

        </script>

    @endpush

{{-- End of Extra CSS and JS --}}
{{-- ########################################## --}}
