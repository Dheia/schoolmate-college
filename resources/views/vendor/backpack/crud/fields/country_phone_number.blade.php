
<!-- number input -->
<div @include('crud::inc.field_wrapper_attributes') >
    <label for="{{ $field['name'] }}">{!! $field['label'] !!}</label>
    @include('crud::inc.field_translatable_icon')

    @if(isset($field['prefix']) || isset($field['suffix'])) <div class="input-group"> @endif
        @if(isset($field['prefix'])) <div class="input-group-addon">{!! $field['prefix'] !!}</div> @endif
        <input
            type="tel"
            class="country-phone-number form-control"
            name="{{ $field['name'] }}"
            id="{{ $field['name'] }}"
            value="{{ old($field['name']) ? old($field['name']) : (isset($field['value']) ? $field['value'] : (isset($field['default']) ? $field['default'] : '' )) }}"
            @include('crud::inc.field_attributes')
            style="height: auto;">
        @if(isset($field['suffix'])) <div class="input-group-addon">{!! $field['suffix'] !!}</div> @endif

    @if(isset($field['prefix']) || isset($field['suffix'])) </div> @endif

    {{-- HINT --}}
    @if (isset($field['hint']))
        <p class="help-block">{!! $field['hint'] !!}</p>
    @endif
</div>




{{-- ########################################## --}}
{{-- Extra CSS and JS for this particular field --}}
{{-- If a field type is shown multiple times on a form, the CSS and JS will only be loaded once --}}
@if ($crud->checkIfFieldIsFirstOfItsType($field, $fields))

    {{-- FIELD CSS - will be loaded in the after_styles section --}}
    @push('crud_fields_styles')
        {{-- YOUR CSS HERE --}}
        <link rel="stylesheet" href="{{ asset('css/intlTelInput.css') }}">
    @endpush

    @push('after_scripts')
        <script src="{{ asset('js/intlTelInput.js') }}"></script>
    @endpush

    {{-- FIELD JS - will be loaded in the after_scripts section --}}
    @push('crud_fields_scripts')
        {{-- YOUR JS HERE --}}
    
        <script>
            $(document).ready(function () {

                    var dialCode, countryCode;

                    $('.country-phone-number').intlTelInput({
                        defaultCountry: "ph",
                        preferredCountries: [ "ph" ],
                        // seperateDialCode: true,
                    });

                    $('.country-list li').click(function () {
                        dialCode = $(this).attr('data-dial-code');
                        countryCode = $(this).attr('data-country-code');
                        
                        $('#{{ $field['name'] }}CountryCode').val(countryCode);
                        $('#{{ $field['name'] }}DialCode').val(dialCode);
                    });


                    if($('#{{ $field['name'] }}CountryCode').val() !== '') {
                        $("#{{ $field['name'] }}")
                            .intlTelInput("selectCountry", $('#{{ $field['name'] }}CountryCode').val());

                        $("#{{ $field['name'] }}")
                            .val("{{ old($field['name']) ? old($field['name']) : (isset($field['value']) ? $field['value'] : (isset($field['default']) ? $field['default'] : '' )) }}");

                    } else {
                        $('.country-phone-number').each(function () {
                            $('#' + $(this).attr('name') + 'CountryCode').val('ph');
                        });
                    }
                 

            });
        </script>

    @endpush
@endif
{{-- End of Extra CSS and JS --}}
{{-- ########################################## --}}


{{-- ########################################## --}}
{{-- Extra CSS and JS for this particular field --}}
{{-- If a field type is shown multiple times on a form, the CSS and JS will only be loaded once --}}

{{-- End of Extra CSS and JS --}}
{{-- ########################################## --}}