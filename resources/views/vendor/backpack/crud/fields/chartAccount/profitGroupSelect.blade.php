<!-- select from array -->
<div @include('crud::inc.field_wrapper_attributes') >
    <label>{!! $field['label'] !!}</label>
    @include('crud::inc.field_translatable_icon')

    {{ App\Models\ProfitsLossStatement::all() }}
    @php 
        $profits = App\Models\ProfitsLossStatement::all();

        function hasParent($p_id, $name) {
            $tmp = [];
            $profits = App\Models\ProfitsLossStatement::all();

            foreach($profits as $profit) {
                if($profit->id == $p_id) {
                    // return $profit->name;
                    if($profit->hierarchy_type == "GROUP") {
                        return "a = " . $profit;
                        array_push($tmp, $profit->name . ' ' . $name);
                        hasParent($profit->group_id, $profit->name);
                        // return json_decode($tmp);
                    } else {
                        // array_push($tmp, $profit->name);
                        array_push($tmp, $profit->name . ' =>> ' . $name);
                        return json_encode($tmp);
                    }
                }
            }

        }
    @endphp

    @foreach($profits as $profit)
        @if($profit->hierarchy_type == "Group" && $profit->group_id == null)
            <br><b>{{ $profit->name }}</b><br>
        @endif

        @if($profit->hierarchy_type == "Group" && $profit->group_id !== null)
            {{-- {{ hasParent($profit->group_id, $profit->name) }} <br> --}}
            {{ hasParent($profit->group_id, $profit->name) }} <br>
        @else

        @endif
    @endforeach
    <select
        name="{{ $field['name'] }}@if (isset($field['allows_multiple']) && $field['allows_multiple']==true)[]@endif"
        @include('crud::inc.field_attributes')
        @if (isset($field['allows_multiple']) && $field['allows_multiple']==true)multiple @endif
        >

        @if (isset($field['allows_null']) && $field['allows_null']==true)
            <option value="">-</option>
        @endif

        {{-- @if (count($field['options']))
            @foreach ($field['options'] as $key => $value)
                @if((old($field['name']) && (
                        $key == old($field['name']) ||
                        (is_array(old($field['name'])) &&
                        in_array($key, old($field['name']))))) ||
                        (null === old($field['name']) &&
                            ((isset($field['value']) && (
                                        $key == $field['value'] || (
                                                is_array($field['value']) &&
                                                in_array($key, $field['value'])
                                                )
                                        )) ||
                                (isset($field['default']) &&
                                ($key == $field['default'] || (
                                                is_array($field['default']) &&
                                                in_array($key, $field['default'])
                                            )
                                        )
                                ))
                        ))
                    <option value="{{ $key }}" selected>{{ $value }}</option>
                @else
                    <option value="{{ $key }}">{{ $value }}</option>
                @endif
            @endforeach
        @endif --}}
    </select>

    {{-- HINT --}}
    @if (isset($field['hint']))
        <p class="help-block">{!! $field['hint'] !!}</p>
    @endif
</div>
