<div clas="col-md-12">
   
    <?php $entity_model = $crud->model; ?>
    <input type="text" 
        ng-model="item.{{ $field['name'] }}"
        id="desc-<% $index + 1 %>"
        @include('crud::inc.field_attributes', ['default_class' =>  'form-control '])
        readonly/>
         {{--    <option value="">- Select Subject</option>

            @if (isset($field['model']))
                @foreach ($field['model']::all() as $connected_entity_entry)
                    <option value="{{ $connected_entity_entry->getKey() }}">
                    	{{ $connected_entity_entry->{$field['attribute']} }}
                    </option>
                @endforeach
            @endif --}}
    {{-- </select> --}}

</div>

