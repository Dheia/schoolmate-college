<!-- text input -->
<div @include('crud::inc.field_wrapper_attributes') >
    <label>{!! $field['label'] !!}</label>
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

    @push('crud_fields_styles')
        <!-- no styles -->
        <link rel="stylesheet" href="{{ asset('css/easy-autocomplete.min.css') }}">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.0/jquery-confirm.min.css">
    @endpush


{{-- FIELD EXTRA JS --}}
{{-- push things in the after_scripts section --}}

    @push('crud_fields_scripts')
        <!-- no scripts -->
        <script src="{{ asset('js/easy-autocomplete.min.js') }}"></script>
        <script src="{{ asset('js/accounting.min.js') }}"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.0/jquery-confirm.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.min.js"></script>
        <script>
            
            var options = {
                url:  window.location.protocol + '//' + location.host + '/admin/api/tuitions-list',
                getValue: function (element) {
                    return element.studentnumber + ' ' + element.firstname + ' ' + element.lastname;
                },
                list: {
                    // maxNumberOfElements: 1,
                    onSelectItemEvent: function() {
                        other_program_total = 0.00;
                        var data = $("#search").getSelectedItemData();
                        $("#search").val(parseInt(data.studentnumber)).trigger("change");

                    },
                    onClickEvent: function() { },
                    match: {
                        enabled: true
                    }
                },
            };
            $("#search").easyAutocomplete(options); 

        </script>
    @endpush