<!-- select2 -->
@php
    
    $options = \App\Models\SectionManagement::all();

@endphp

{{--   <span class="input-group-addon">
      <button class="btn btn-default">+</button>
      <button class="btn btn-default">-</button>
  </span> --}}

<div class="input-group" style="width: 100%">
    <span class="input-group-addon" id="basic-addon1" style="width: auto;  background: #d2d6de;">Setion</span>
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

            var OriginalRouteForEncode = '{{ url()->current() }}/encode';
        
            function refactorRoute () {
                var anchor = $('a.ladda-button').eq(0);
                var anchorReorder = $('a.ladda-button').eq(1);
                var route = anchor.attr('href');

                var schoolYear = $('#schoolYear option:selected').val(); 
                var section    = $('#section option:selected').val();
                var subject    = $('#subject option:selected').val();

                // anchor.attr('href', OriginalRoute + '?schoolYear_id=' + schoolYear + '&section_id=' + section + '&subject_id=' + subject); 
                // anchorReorder.attr('href', OriginalRouteForReorder + '?schoolYear_id=' + schoolYear + '&section_id=' + section + '&subject_id=' + subject);
                $('#lookup').attr('href', OriginalRouteForEncode + '?schoolYear_id=' + schoolYear + '&section_id=' + section + '&subject_id=' + subject);
            }

            refactorRoute();
            
            $('#section').change(function () {
                refactorRoute();
            });

        </script>

    @endpush

{{-- End of Extra CSS and JS --}}
{{-- ########################################## --}}
