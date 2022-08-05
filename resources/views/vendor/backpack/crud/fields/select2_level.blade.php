
<div @include('crud::inc.field_wrapper_attributes') >
    <label>{!! $field['label'] !!}</label>
    @include('crud::inc.field_translatable_icon')
    <?php $entity_model = $crud->getRelationModel($field['entity'],  - 1); ?>
    <select
        id="micsid"
        onChange="changeAmount()"
        name="{{ $field['name'] }}"
        style="width: 100%"
        @include('crud::inc.field_attributes', ['default_class' =>  'form-control select2_field'])
        >

        <option>-</option>

        @if ($entity_model::isColumnNullable($field['name']))
            <option value="">-</option>
        @endif

        @if (isset($field['model']))
            @foreach ($field['model']::all() as $connected_entity_entry)
                @if(old($field['name']) == $connected_entity_entry->getKey() || (is_null(old($field['name'])) && isset($field['value']) && $field['value'] == $connected_entity_entry->getKey()))
                    <option value="{{ $connected_entity_entry->getKey() }}" selected>{{ $connected_entity_entry->{$field['attribute']} }}</option>
                @else
                    <option value="{{ $connected_entity_entry->getKey() }}">{{ $connected_entity_entry->{$field['attribute']} }}</option>
                @endif
            @endforeach
        @endif
    </select>

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
        <!-- include select2 css-->
        <link href="{{ asset('vendor/adminlte/bower_components/select2/dist/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />
    @endpush

    {{-- FIELD JS - will be loaded in the after_scripts section --}}
    @push('crud_fields_scripts')
        <!-- include select2 js-->
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

            var micsid;
            var amount = 0.0;
            var miscs = {!! json_encode($misc) !!};
            function changeAmount(){
                amount = 0.0;
                micsid = document.getElementById('micsid').value;
                len = Object.keys(miscs).length;
                for (var i = 0; i < len; i++) {
                    if(micsid == miscs[i]['id']){
                        var misc2 = JSON.parse(miscs[i]['miscellaneous']);
                        for(var i2 = 0; i2 < Object.keys(misc2).length; i2++){
                            amount = amount + misc2[i2]['amount'];
                            
                        }
                    document.getElementById('amount').value = amount;
                    break;
                    }
                }
                changeTotal2();
            }
            
        </script>
    @endpush

@endif
{{-- End of Extra CSS and JS --}}
{{-- ########################################## --}}