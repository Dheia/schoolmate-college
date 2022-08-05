<!-- html5 date input -->

<div @include('crud::inc.field_wrapper_attributes') >
    @include('crud::inc.field_translatable_icon')
    @if(isset($field['show_label']))
        <label for="">{{ $column['label'] }}</label>
    @endif
    <input
        type="text"
        ng-model="item.{{ $field['name'] }}"
        @include('crud::inc.field_attributes')
        >

    {{-- HINT --}}
    @if (isset($field['hint']))
        <p class="help-block">{!! $field['hint'] !!}</p>
    @endif
</div>