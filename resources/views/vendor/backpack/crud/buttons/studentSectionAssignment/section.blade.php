<!-- select2 -->
@php
    
    $levels = \App\Models\SectionManagement::with('level')->whereHas('year', function ($q) { $q->active(); })->get();
    $levels = $levels->groupBy('level.year');
    // dd($levels);
@endphp

{{--   <span class="input-group-addon">
      <button class="btn btn-default">+</button>
      <button class="btn btn-default">-</button>
  </span> --}}

<div class="input-group" style="width: 100%">
    <span class="input-group-addon" id="basic-addon1" style="width: auto;  background: #d2d6de;">Section</span>
    <select name="section_id" id="section" class="form-control select2_field" style="width: 100%; display: unset;">
        @foreach($levels as $key => $level)
            <optgroup label="{{ $key }}">
                @foreach($level as $section)
                    @if(isset($_GET['section_id']))
                        <option value="{{ $section->id }}" {{ $_GET['section_id'] == $section->id ? 'selected=true' : null }}>
                            {{ $section->name }}
                        </option>
                    @else
                        <option value="{{ $section->id }}">{{ $section->name }}</option>
                    @endif
                @endforeach
            </optgroup>
        @endforeach
    </select>
</div>

{{-- </div> --}}

{{-- ########################################## --}}
{{-- Extra CSS and JS for this particular field --}}
{{-- If a field type is shown multiple times on a form, the CSS and JS will only be loaded once --}}

    {{-- FIELD CSS - will be loaded in the after_styles section --}}
    @push('crud_fields_styles')
        <!-- include select2 css-->
        <link href="{{ asset('vendor/adminlte/bower_components/select2/dist/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />
    @endpush

    {{-- FIELD JS - will be loaded in the after_scripts section --}}
    @push('after_scripts')
        <script src="{{ asset('vendor/adminlte/bower_components/select2/dist/js/select2.min.js') }}"></script>
            
        <script>
            
            jQuery(document).ready(function($) {
                // trigger select2 for each untriggered select2 box
                $('.select2_field').each(function (i, obj) {
                    if (!$(obj).hasClass("select2-hidden-accessible"))
                    {
                        $(obj).select2({
                            theme: "bootstrap"
                        });
                    }
                });
            });


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


                anchor.attr('href', OriginalRoute + '?schoolYear_id=' + schoolYear + '&section_id=' + section); 
                anchorReorder.attr('href', OriginalRouteForReorder + '?school_year_id=' + schoolYear + '&section_id=' + section);
            }
            
            refactorRoute();
            
            $('#section').change(function () {
                refactorRoute();
            });

        </script>

    @endpush

{{-- End of Extra CSS and JS --}}
{{-- ########################################## --}}
