<!-- textarea -->
<div @include('crud::inc.field_wrapper_attributes') >
    <label>{!! $field['label'] !!}</label>
    @include('crud::inc.field_translatable_icon')
    <textarea
        {{-- maxlength="{{ $field['max'] }}" --}}
        name="{{ $field['name'] }}"
        @include('crud::inc.field_attributes')

        >{{ old(square_brackets_to_dots($field['name'])) ?? $field['value'] ?? $field['default'] ?? '' }}</textarea>

          <div id="the-count">
            <span id="counter"></span> / 
            <span id="current">160</span>&nbsp;
            <span id="maximum">(1)</span>
          </div>

    {{-- HINT --}}
    @if (isset($field['hint']))
        <p class="help-block">{!! $field['hint'] !!}</p>
    @endif
</div>


    @push('crud_fields_scripts')
        <!-- no scripts -->
        <script>
            var originalVal = 160;
            function countCharactersAndLimit () {
                var characterCount = $('textarea[name="{{$field['name']}}"]').val().length,
                current = $('#current'),
                maximum = $('#maximum'),
                counter = $('#counter');
                counter.text(characterCount);
                var mod = 160;
                
                if( characterCount > originalVal) {
                    var a = Math.floor(characterCount / mod) + 1; 
                    current.text(mod * a);
                    maximum.text('(' + a + ')');
                    originalVal = mod * a;
                    console.log('m:', mod ,' *' , 'a:', a, '=', mod * a);
                } 

                if(characterCount <= originalVal) {
                    var a = Math.floor(characterCount / mod) + 1; 
                    current.text(mod * a);
                    maximum.text('(' + a + ')');
                    originalVal = mod * a;
                    console.log('m:', mod ,' *' , 'a:', a, '=', mod * a);
                }

                console.log('og = ', originalVal);

                {{-- // if (characterCount === {{ $field['max'] }}) { return; } --}}
            } countCharactersAndLimit();

            $('textarea').keyup(function() { countCharactersAndLimit() });
        </script>
    @endpush


{{-- Note: you can use @if ($crud->checkIfFieldIsFirstOfItsType($field, $fields)) to only load some CSS/JS once, even though there are multiple instances of it --}}
