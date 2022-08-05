<!-- text input -->
<div @include('crud::inc.field_wrapper_attributes') >

    @if(isset($field['prefix']) || isset($field['suffix'])) <div class="input-group"> @endif
        @if(isset($field['prefix'])) <div class="input-group-addon">{!! $field['prefix'] !!}</div> @endif

    {{-- HINT --}}
    @if (isset($field['hint']))
        <p class="help-block">{!! $field['hint'] !!}</p>
    @endif
</div>


@push('crud_fields_styles')
    <!-- no styles -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.0/jquery-confirm.min.css">
@endpush


{{-- FIELD EXTRA JS --}}
{{-- push things in the after_scripts section --}}

@push('after_scripts')
    <!-- no scripts -->
    {{-- <script src="{{ asset('js/student-accounting.js') }}"></script> --}}

@endpush