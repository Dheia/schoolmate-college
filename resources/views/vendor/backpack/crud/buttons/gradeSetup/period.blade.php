
{{--   <span class="input-group-addon">
      <button class="btn btn-default">+</button>
      <button class="btn btn-default">-</button>
  </span> --}}
<div class="input-group" style="width: 100%">
    <span class="input-group-addon" id="basic-addon1" style="width: auto;  background: #d2d6de;">Period</span>
    <select name="period_id" id="period" class="form-control" style="width: 100%; display: unset;">
        <option selected disabled>-</option>
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
                var anchor        = $('a.ladda-button').eq(0);
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
            
            refactorRoute();
            
            $('#period').change(function () {
                refactorRoute();
            });

        </script>

    @endpush

{{-- End of Extra CSS and JS --}}
{{-- ########################################## --}}
