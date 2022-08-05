<div clas="col-md-12">
	{{-- { dd($fields) }} --}}
    <?php $entity_model = $crud->model; ?>
    <select ng-change="changeSubject()" 
        ng-model="item.{{ $field['name'] }}"
        post-render
        required
        id="{{ $field['name'] }}"
        {{-- shared-scope="<% item %>" --}}
        {{-- id="{{ $field['attribute']}}-" --}}
        @include('crud::inc.field_attributes', ['default_class' =>  'form-control select2'])
        >
            <option value="" selected>Select Subject</option>

            @if (isset($field['model']))
                @foreach ($field['model']::all() as $connected_entity_entry)
                    <option value="{{ $connected_entity_entry->id }}">
                        {{ $connected_entity_entry->{$field['attribute']} }}
                    </option>
                @endforeach
            @endif
    </select>

    {{-- HINT --}}
    @if (isset($field['hint']))
        <p class="help-block">{!! $field['hint'] !!}</p>
    @endif
</div>

@if (!$crud->child_resource_included['select'])

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
    @endpush

    
    <?php $crud->child_resource_included['select'] = true; ?>
@endif