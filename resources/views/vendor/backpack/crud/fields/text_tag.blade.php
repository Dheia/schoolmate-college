<!-- text input -->
<div @include('crud::inc.field_wrapper_attributes') >
    <label>{!! $field['label'] !!}</label>
    @include('crud::inc.field_translatable_icon')

    @if(isset($field['prefix']) || isset($field['suffix'])) <div class="input-group"> @endif
        @if(isset($field['prefix'])) <div class="input-group-addon">{!! $field['prefix'] !!}</div> @endif
        <input
            type="text"
            name="{{ $field['name'] }}"
            value="{{ old(square_brackets_to_dots($field['name'])) ?? $field['value'] ?? $field['default'] ?? '' }}"
            @include('crud::inc.field_attributes')
        >
        @if(isset($field['suffix'])) <div class="input-group-addon">{!! $field['suffix'] !!}</div> @endif
    @if(isset($field['prefix']) || isset($field['suffix'])) </div> @endif

    {{-- HINT --}}
    @if (isset($field['hint']))
        <p class="help-block">{!! $field['hint'] !!}</p>
    @endif
</div>


{{-- FIELD EXTRA CSS  --}}
{{-- push things in the after_styles section --}}

@push('crud_fields_styles')
    <link rel="stylesheet" href="{{ asset('css/jquery.tagsinput.min.css') }}">
    <style>
        .tagsinput {
            height: auto !important;
            min-height: auto !important;
        }

        .tagsinput > div > input {
          width: auto !important;
        }
    </style>
@endpush


{{-- FIELD EXTRA JS --}}
{{-- push things in the after_scripts section --}}

@push('crud_fields_scripts')
    <script src="{{ asset('js/jquery.tagsinput.min.js') }}"></script>

    <script>
        var field = $('input[name="{{ $field['name'] }}"]');
        
        field.tagsInput({
          'width': 'auto',
          'delimiter': ',',
          'defaultText': 'Add Author',
        });

    </script>
@endpush


{{-- Note: you can use @if ($crud->checkIfFieldIsFirstOfItsType($field, $fields)) to only load some CSS/JS once, even though there are multiple instances of it --}}
