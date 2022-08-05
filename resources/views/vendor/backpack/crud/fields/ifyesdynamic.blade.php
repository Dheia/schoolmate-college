<!-- text input -->
<div @include('crud::inc.field_wrapper_attributes') >
    <label>{!! $field['label'] !!}</label><br>
    <input onclick="Show{{ $field['attributes']['id'] }}()" id="{{ $field['attributes']['id'] }}yes" type="radio" name="{{ $field['attributes']['id'] }}name" value="1"><b>Yes</b><br>
    <input onclick="Hide{{ $field['attributes']['id'] }}()" id="{{ $field['attributes']['id'] }}no" type="radio" name="{{ $field['attributes']['id'] }}name" value="0"><b>No</b>
    @include('crud::inc.field_translatable_icon')
    @if(isset($field['prefix']) || isset($field['suffix'])) <div class="input-group"> @endif
        @if(isset($field['prefix'])) <div class="input-group-addon">{!! $field['prefix'] !!}</div> @endif
        <input
            type="text"
            name="{{ $field['name'] }}"
            value="{{ old($field['name']) ? old($field['name']) : (isset($field['value']) ? $field['value'] : (isset($field['default']) ? $field['default'] : '' )) }}"
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

    {{-- @push('crud_fields_styles')
        <!-- no styles -->
    @endpush --}}


{{-- FIELD EXTRA JS --}}
{{-- push things in the after_scripts section --}}

    @push('crud_fields_scripts')
        <script type="text/javascript">

                    $("#{{ $field['attributes']['id'] }}").hide();

                    function Show{{ $field['attributes']['id'] }}(){
                       $("#{{ $field['attributes']['id'] }}").show();
                    }

                    function Hide{{ $field['attributes']['id'] }}(){
                       $("#{{ $field['attributes']['id'] }}").hide();
                       document.getElementById("{{ $field['attributes']['id'] }}").value = "";
                    }
        </script>
    @endpush


{{-- Note: you can use @if ($crud->checkIfFieldIsFirstOfItsType($field, $fields)) to only load some CSS/JS once, even though there are multiple instances of it --}}