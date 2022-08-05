<!-- select2 -->
@php
    $profits = \App\Models\ProfitsLossStatement::all();

    $categories = \App\Models\ProfitsLossStatement::where('group_id', '=', null)->get();
    $allCategories = \App\Models\ProfitsLossStatement::pluck('name','id')->all();

    // dd($categories);
@endphp
<div clas="col-md-12">

    <?php $entity_model = $crud->model; ?>
    <select 
        ng-model="item.{{ $field['name'] }}"
        @include('crud::inc.field_attributes', ['default_class' =>  'form-control select2'])
        >
            <option value="">-</option>

            @if (isset($field['model']))
                @foreach ($field['model']::all() as $connected_entity_entry)
                    @if($connected_entity_entry->hierarchy_type == "Group")
                        <optgroup label="{{ $connected_entity_entry->{$field['attribute']} }}" id="group-{{ $connected_entity_entry->getKey() }}">
                        </optgroup>
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
{{-- @if (!$crud->child_resource_included['select']) --}}

    {{-- FIELD CSS - will be loaded in the after_styles section --}}
    @push('crud_fields_styles')
        <!-- include select2 css-->
        <link href="{{ asset('vendor/backpack/select2/select2.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('vendor/backpack/select2/select2-bootstrap-dick.css') }}" rel="stylesheet" type="text/css" />

       
    @endpush

    {{-- FIELD JS - will be loaded in the after_scripts section --}}
    @push('crud_fields_scripts')
        <!-- include select2 js-->
        <script src="{{ asset('vendor/backpack/select2/select2.js') }}"></script>

        <script>
            $(document).ready(function () {

                var accounts = {!! $field['model']::all() !!};

                $.each(accounts, function (key, val) {
                    if(val.hierarchy_type == "Account") {
                        $('<option value="' + val.id + '">' + val.name + '<option>').appendTo('#group-' + val.group_id);
                    }
                });

                setTimeout(function () {
                    $('select[ng-model="item.account"] option[value=""]').remove();
                }, 100);

            });
        </script>
    @endpush

    
    <?php $crud->child_resource_included['select'] = true; ?>
{{-- @endif --}}
{{-- End of Extra CSS and JS --}}
{{-- ########################################## --}}
{{-- child_number.blade.php --}}
