<!-- enum -->
<div @include('crud::inc.field_wrapper_attributes') >
    <label>{!! $field['label'] !!}</label>
    @include('crud::inc.field_translatable_icon')
    <?php $entity_model = $crud->model; ?>
    <select
        onChange="paymentMethod()"
        name="{{ $field['name'] }}"
        @include('crud::inc.field_attributes')
        >

        
            <option value="">-</option>
        

            @if (count($entity_model::getPossibleEnumValues($field['name'])))
                @foreach ($entity_model::getPossibleEnumValues($field['name']) as $possible_value)
                    <option value="{{ $possible_value }}"
                        @if (( old($field['name']) &&  old($field['name']) == $possible_value) || (isset($field['value']) && $field['value']==$possible_value))
                            selected
                        @endif
                    >{{ $possible_value }}</option>
                @endforeach
            @endif
    </select>

    {{-- HINT --}}
    @if (isset($field['hint']))
        <p class="help-block">{!! $field['hint'] !!}</p>
    @endif
</div>
<div id="fullpayment" @include('crud::inc.field_wrapper_attributes') >
    <table width="100%">
        <tr>
            <td style="padding-right: 10px;" width="80%" align="right"><b>Total:</b></td>
            <td><input style="width: 100%; height: 33px;" id="totalid2" type="text" name="total2"></td>
        </tr>
    </table>
</div>
<div id="installment" @include('crud::inc.field_wrapper_attributes') >
    installment
</div>
<script type="text/javascript">
    function paymentMethod(){
        var f = document.getElementById("fullpayment");
        var i = document.getElementById("installment");
        var fieldValue = document.getElementById('paymenttypeid').value;
        if(fieldValue == "Full Payment"){
            f.style.display = "block";
            i.style.display = "none";
        }
        if(fieldValue == "Installment"){
            i.style.display = "block";
            f.style.display = "none";
        }
    }

    function changeTotal2(){
        var allTotal = 0.0;
        allTotal = amount + parseFloat(document.getElementById("payment").value);
        document.getElementById("totalid2").value = allTotal;
    }
</script>