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
<script>
    var level = $('#searchSectionLevel');
    var section = $('select[name="section_id"]');
    async function getSection () {
        await $.ajax({
            url: '/{{ $crud->route }}/section',
            data: {
                level_id: level.val()
            },
            success: function (response) {
                var sectionOptions = '';
                $.each(response, function (k, v) {
                    sectionOptions += '<option value="' + v.id + '">' + v.name + '</option>'
                });
                section.html(sectionOptions);
                @if($crud->getActionMethod() === "edit" || $crud->getActionMethod() === "clone")
                    $('select[name="section_id"]').find('option[value="{{ $entry->section_id }}"]').attr('selected', true);
                @endif
            }
        });
    }
    @if($crud->getActionMethod() === "create")
        getSection();
    @endif
    level.change(function () {  getSection(); });

    @if($crud->getActionMethod() === "edit" || $crud->getActionMethod() === "clone")
        @php
            $section = App\Models\SectionManagement::where('id', $entry->section_id)->first();
            $level_id = $section->level_id;
        @endphp
        $('select[name="level_id"]').find('option[value="{{ $level_id }}"]').attr('selected', true);
        getSection();
    @endif 
</script>
@endpush