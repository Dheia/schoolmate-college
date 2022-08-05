<!-- select2 -->
@php
    
    $options = \App\Models\SchoolYear::active()->get();

@endphp

{{--   <span class="input-group-addon">
      <button class="btn btn-default">+</button>
      <button class="btn btn-default">-</button>
  </span> --}}

<div class="input-group" style="width: 100%">
    <span class="input-group-addon" id="basic-addon1" style="width: auto;  background: #d2d6de;">Active School Year</span>
    <select name="school_year_id" id="schoolYear" class="form-control" style="width: 100%; display: unset;">
        @foreach($options as $option)
            @if(isset($_GET['school_year_id']))
                <option value="{{ $option->id }}"  {{ $_GET['school_year_id'] == $option->id ? 'selected=true' : null }}>{{ $option->schoolYear }}</option>
            @else
                <option value="{{ $option->id }}">{{ $option->schoolYear }}</option>
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
            var OriginalRouteForSchoolYear = '{{ url()->current() }}';
        
            function refactorRoute () {
                var anchor = $('a.ladda-button').eq(0);
                var anchorReorder = $('a.ladda-button').eq(1);
                var route = anchor.attr('href');

                var schoolYear = $('#schoolYear option:selected').val(); 
                var section = $('#section option:selected').val();
                var subject = $('#subject option:selected').val();


                anchor.attr('href', OriginalRoute + '?school_year_id=' + schoolYear + '&section_id=' + section + '&subject_id=' + subject); 
                anchorReorder.attr('href', OriginalRouteForReorder + '?school_year_id=' + schoolYear + '&section_id=' + section + '&subject_id=' + subject);
                // $('#lookup').attr('href', OriginalRouteForSchoolYear + '?school_year_id=' + schoolYear + '&section_id=' + section + '&subject_id=' + subject);
            }
            
            refactorRoute();
            
            $('#schoolYear').change(function () {
                refactorRoute();
            });

        </script>

    @endpush

{{-- End of Extra CSS and JS --}}
{{-- ########################################## --}}
