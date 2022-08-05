<!-- select from array -->
<div @include('crud::inc.field_wrapper_attributes') >
    <label>{!! $field['label'] !!}</label>
    @include('crud::inc.field_translatable_icon')
    <select
        name="{{ $field['name'] }}@if (isset($field['allows_multiple']) && $field['allows_multiple']==true)[]@endif"
        @include('crud::inc.field_attributes')
        @if (isset($field['allows_multiple']) && $field['allows_multiple']==true)multiple @endif
        >

        @if (isset($field['allows_null']) && $field['allows_null']==true)
            <option value="">-</option>
        @endif

        @if (count($field['options']))
            @foreach ($field['options'] as $key => $value)
                @if((old(square_brackets_to_dots($field['name'])) && (
                        $key == old(square_brackets_to_dots($field['name'])) ||
                        (is_array(old(square_brackets_to_dots($field['name']))) &&
                        in_array($key, old(square_brackets_to_dots($field['name'])))))) ||
                        (null === old(square_brackets_to_dots($field['name'])) &&
                            ((isset($field['value']) && (
                                        $key == $field['value'] || (
                                                is_array($field['value']) &&
                                                in_array($key, $field['value'])
                                                )
                                        )) ||
                                (isset($field['default']) &&
                                ($key == $field['default'] || (
                                                is_array($field['default']) &&
                                                in_array($key, $field['default'])
                                            )
                                        )
                                ))
                        ))
                    <option value="{{ $key }}" selected>{{ $value }}</option>
                @else
                    <option value="{{ $key }}">{{ $value }}</option>
                @endif
            @endforeach
        @endif
    </select>
    {{-- HINT --}}
    @if (isset($field['hint']))
        <p class="help-block">{!! $field['hint'] !!}</p>
    @endif
</div>

@push('after_scripts')
<!-- no scripts -->
<script type="text/javascript">
    options          = '<option value="">-</option>';
    var term         = $('select[name="term_type"]');
    var track        = $('select[name="track_id"]');
    var level        = $('select[name="level_id"]');
    var levels       = {!! json_encode($levels) !!};
    // department_id that term type is semester
    var semester     = {!! json_encode($department_with_semester) !!};

    $('select[name="department_id"').on('change', function() {
        var selected_department = $('select[name="department_id"').find(":selected").val();

        if (semester.includes(selected_department)){
            // TERM Options If Term Type is Semester
            options     = '<option value="First">First</option>';
            options     += '<option value="Second">Second</option>';
            term.html(options);
            // Levels Options
            options = '';
            var department_id = department.find('option:selected').val();
            $.each(levels, function (key, val) {
                if(department_id == val.department_id) {
                    options += '<option value="' + val.id + '">' + val.year + '</option>';
                }
            });
            level.html(options);

            // TRACK Options If SHS
            options = '<option value="">-</option>';
            $.each(tracks, function (key, val) {
                var level_id = level.find('option:selected').val();
                if(level_id == val.level_id) {
                    options += '<option value="' + val.id + '">' + val.code + '</option>';
                }
            });

            track.html(options);
        } else {
            options     = '<option value="">-</option>';
            term.html(options);
        }
    });
</script>
@endpush