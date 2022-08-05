<!-- html5 date input -->

<?php
// if the column has been cast to Carbon or Date (using attribute casting)
// get the value as a date string
if (isset($field['value']) && ( $field['value'] instanceof \Carbon\Carbon || $field['value'] instanceof \Jenssegers\Date\Date )) {
    $field['value'] = $field['value']->toDateString();
}
?>

<div @include('crud::inc.field_wrapper_attributes') >
    {{-- <label>{!! $field['label'] !!}</label> --}}
    @include('crud::inc.field_translatable_icon')
   {{--  @if($field['show_label'])
        <label for="">{{ $column['label'] }}</label>
    @endif --}}
    @if($crud->getActionMethod() == 'edit' || $crud->getActionMethod() == 'clone')
        <input
            type="text"
            ng-model="item.{{ $field['name'] }}"
            value="{{ old($field['name']) ? old($field['name']) : (isset($field['value']) ? $field['value'] : (isset($field['default']) ? $field['default'] : '' )) }}"
            @include('crud::inc.field_attributes')
        >
    @else 
        <input
            type="date"
            ng-model="item.{{ $field['name'] }}"
            value="{{ old($field['name']) ? old($field['name']) : (isset($field['value']) ? $field['value'] : (isset($field['default']) ? $field['default'] : '' )) }}"
            @include('crud::inc.field_attributes')
        >
    @endif

    {{-- HINT --}}
    @if (isset($field['hint']))
        <p class="help-block">{!! $field['hint'] !!}</p>
    @endif
</div>

@push('after_scripts')
    <script type="text/javascript">
        $(document).ready(function () {
            $('input[ng-model="item.{{ $field['name'] }}"]').attr('type', 'date');
        })
    </script>
@endpush
