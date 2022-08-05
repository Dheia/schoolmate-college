<!-- select2 -->
@php
    
    $options = \App\Models\YearManagement::all();

@endphp

{{--   <span class="input-group-addon">
      <button class="btn btn-default">+</button>
      <button class="btn btn-default">-</button>
  </span> --}}

<div class="input-group" style="width: 100%; margin-bottom: 5px;">
    <span class="input-group-addon" id="basic-addon1" style="width: auto;  background: #d2d6de;">Level</span>
    <select name="level_id" id="level" class="form-control" style="width: 100%; display: unset;">
        @foreach($options as $option)
            @if(isset($_GET['level_id']))
                <option value="{{ $option->id }}"  {{ $_GET['level_id'] == $option->id ? 'selected=true' : null }}>{{ $option->year }}</option>
            @else
                <option value="{{ $option->id }}">{{ $option->year }}</option>
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
            
            function watchLevel () {
                $.ajax({

                });
            }
            

            var OriginalRoute = '{{ url()->current() }}/create';
            var OriginalRouteForReorder = '{{ url()->current() }}/reorder';
            var OriginalRouteForSetupGrade = '{{ url()->current() }}';
        
            function refactorRoute () {
                var anchor = $('a.ladda-button').eq(0);
                var anchorReorder = $('a.ladda-button').eq(1);
                var route = anchor.attr('href');

                // var template = $('#template option:selected').val(); 
                var section = $('#section option:selected').val();
                var level = $('#level option:selected').val();


                anchor.attr('href', OriginalRoute + '?section_id=' + section + '&level_id=' + level); 
                anchorReorder.attr('href', OriginalRouteForReorder + '?section_id=' + section + '&level_id=' + level);
                $('#lookup').attr('href', OriginalRouteForSetupGrade + '?section_id=' + section + '&level_id=' + level);
            }
            
            refactorRoute();
            
            $('#section').change(function () {
                refactorRoute();
            });

        </script>

    @endpush

{{-- End of Extra CSS and JS --}}
{{-- ########################################## --}}
