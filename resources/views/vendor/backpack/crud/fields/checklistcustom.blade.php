<script type="text/javascript">
              var athleticsValue = 0;
              var bandValue = 0;
              var stringValue = 0;
              var danceValue = 0;
              var dramaValue =  0;
              var artValue =  0;
              var computerValue =  0;
              var vocalValue =  0;
              var sports;
              var stringInstrument;
              var bandInstrument;
                function JsonEncode(){
                   sports = document.getElementById("athleticsid2").value;
                   bandInstrument = document.getElementById("bandid2").value;
                   stringInstrument = document.getElementById("stringid2").value;
                   var talents = { "athletics":{"isChecked":athleticsValue,"sports":sports}, "band":{"isChecked":bandValue, "instrument":bandInstrument}, "string":{"isChecked":stringValue, "instrument":stringInstrument}, "dance":danceValue, "drama":dramaValue, "art":artValue, "computer":computerValue, "vocal":vocalValue};
                   var talentsJSON = JSON.stringify(talents);
                   //console.log(JSON.parse(talentsJSON).athletics.sports);
                   document.getElementById("specialtalentid").value = talentsJSON;
                   console.log(talentsJSON);
              }

              function hideClearAthletics(){
                
                  var cbAthletics = document.getElementById('athleticsid');
                  if(cbAthletics.checked){
                    document.getElementById("athleticsid2").disabled = false;
                    athleticsValue = 1;
                  }else{
                    document.getElementById("athleticsid2").disabled = true;
                    document.getElementById("athleticsid2").value = "";
                    athleticsValue = 0;
                  }
                  JsonEncode();
              }

              function hideClearBand(){
                  var cbBand = document.getElementById('bandid');
                  if(cbBand.checked){
                    document.getElementById("bandid2").disabled = false;
                    bandValue = 1;
                  }else{
                    document.getElementById("bandid2").disabled = true;
                    document.getElementById("bandid2").value = "";
                    bandValue = 0;
                  }
                  JsonEncode();
              }

              function hideClearString(){
                  var cbString = document.getElementById('stringid');
                  if(cbString.checked){
                    document.getElementById("stringid2").disabled = false;
                    stringValue = 1;
                  }else{
                    document.getElementById("stringid2").disabled = true;
                    document.getElementById("stringid2").value = "";
                    stringValue = 0;
                  }
                  JsonEncode();
              }

              function DanceOnClick(){
                  var cbDance = document.getElementById('danceid');
                  if(cbDance.checked){
                    danceValue = 1;
                  }else{
                    danceValue = 0;
                  }
                  JsonEncode();
              }

              function DramaOnClick(){
                  var cbDrama = document.getElementById('dramaid');
                  if(cbDrama.checked){
                    dramaValue = 1;
                  }else{
                    dramaValue = 0;
                  }
                  JsonEncode();
              }

              function ArtOnClick(){
                  var cbArt = document.getElementById('artid');
                  if(cbArt.checked){
                    artValue = 1;
                  }else{
                    artValue = 0;
                  }
                  JsonEncode();
              }

              function ComputerOnClick(){
                  var cbComputer = document.getElementById('computerid');
                  if(cbComputer.checked){
                    computerValue = 1;
                  }else{
                    computerValue = 0;
                  }
                  JsonEncode();
              }

              function VocalOnClick(){
                  var cbVocal = document.getElementById('vocalid');
                  if(cbVocal.checked){
                    vocalValue = 1;
                  }else{
                    vocalValue = 0;
                  }
                  JsonEncode();
              }

              function CheckBoxOnCheck(){
                  DanceOnClick();
                  DramaOnClick();
                  ArtOnClick();
                  ComputerOnClick();
                  VocalOnClick();
                  hideClearAthletics();
                  hideClearBand();
                  hideClearString();
              }

              
          </script>

<!-- code by dhey -->
{{-- @php
    if(old($field['name'])){
      echo "old!";
      var_dump(json_decode(old($field['name']),true));
    }
    else{
      echo "notold";
      var_dump($field);
    }
@endphp --}}


<!-- field_type_name -->
<div @include('crud::inc.field_wrapper_attributes') >
    <label>{!! $field['label'] !!}</label>
    <table width="100%" style="border: 10px solid #f2f2f2">
        <tr>
            <td width="20%" class="td10px"><input 
                  @if(old($field['name']) ? json_decode(old($field['name']),true)['dance'] == 1 ? true : false : (isset($field['value']) ? json_decode($field['value'],true)['dance'] == 1 ? true : false : false )))
                      checked="checked"
                  @endif
              onclick="CheckBoxOnCheck()" id="danceid" type="checkbox" name="dance">Dance</td>
            <td width="20%" class="td10px"><input 
                  @if(old($field['name']) ? json_decode(old($field['name']),true)['drama'] == 1 ? true : false : (isset($field['value']) ? json_decode($field['value'],true)['drama'] == 1 ? true : false : false )))
                    checked="checked"
                  @endif
              onclick="CheckBoxOnCheck()" id="dramaid" type="checkbox" name="drama">Drama</td>
            <td width="20%" class="td10px"><input 
                  @if(old($field['name']) ? json_decode(old($field['name']),true)['art'] == 1 ? true : false : (isset($field['value']) ? json_decode($field['value'],true)['art'] == 1 ? true : false : false )))
                    checked="checked"
                  @endif
              onclick="CheckBoxOnCheck()" id="artid" type="checkbox" name="art">Art</td>
            <td width="20%" class="td10px"><input 
                  @if(old($field['name']) ? json_decode(old($field['name']),true)['computer'] == 1 ? true : false : (isset($field['value']) ? json_decode($field['value'],true)['computer'] == 1 ? true : false : false )))
                    checked="checked"
                  @endif
              onclick="CheckBoxOnCheck()" id="computerid" type="checkbox" name="computer">Computer</td>
            <td width="20%" class="td10px"><input 
                  @if(old($field['name']) ? json_decode(old($field['name']),true)['vocal'] == 1 ? true : false : (isset($field['value']) ? json_decode($field['value'],true)['vocal'] == 1 ? true : false : false )))
                    checked="checked"
                  @endif
              onclick="CheckBoxOnCheck()" id="vocalid" type="checkbox" name="vocal">Vocal</td>
        </tr>
        <tr>
            <td colspan="5" class="td10px"><input 
                  @if(old($field['name']) ? json_decode(old($field['name']),true)['athletics']['isChecked'] == 1 ? true : false : (isset($field['value']) ? json_decode($field['value'],true)['athletics']['isChecked'] == 1 ? true : false : false )))
                    checked="checked"
                  @endif
              value="1" onclick="CheckBoxOnCheck()" id="athleticsid" type="checkbox" name="">Athletics:<input onkeyup="JsonEncode()" id="athleticsid2" type="text" name="athletics" class="form-control" placeholder="Favorite sport(s)" 
              value="{{ old($field['name']) ? json_decode(old($field['name']),true)['athletics']['sports'] : (isset($field['value']) ? json_decode($field['value'],true)['athletics']['sports'] : '') }}"></td>
        </tr>
        <tr tr valign="top">
            <td colspan="3" class="td10px"><input 
                  @if(old($field['name']) ? json_decode(old($field['name']),true)['band']['isChecked'] == 1 ? true : false : (isset($field['value']) ? json_decode($field['value'],true)['band']['isChecked'] == 1 ? true : false : false )))
                    checked="checked"
                  @endif
              onclick="CheckBoxOnCheck()" id="bandid" type="checkbox" name="">Band:<input onkeyup="JsonEncode()" id="bandid2" type="text" name="band" class="form-control" placeholder="Instrument(s)"
              value="{{ old($field['name']) ? json_decode(old($field['name']),true)['band']['instrument'] : (isset($field['value']) ? json_decode($field['value'],true)['band']['instrument'] : '') }}"></td>
            <td colspan="2" class="td10px"><input 
                  @if(old($field['name']) ? json_decode(old($field['name']),true)['string']['isChecked'] == 1 ? true : false : (isset($field['value']) ? json_decode($field['value'],true)['string']['isChecked'] == 1 ? true : false : false )))
                    checked="checked"
                  @endif
              onclick="CheckBoxOnCheck()" id="stringid" type="checkbox" name="">String:<input onkeyup="JsonEncode()" id="stringid2" type="text" name="string" class="form-control" placeholder="Instrument(s)"
              value="{{ old($field['name']) ? json_decode(old($field['name']),true)['string']['instrument'] : (isset($field['value']) ? json_decode($field['value'],true)['string']['instrument'] : '') }}"></td>
        </tr>
    </table>
    <input
        type="hidden"
        name="{{ $field['name'] }}"
        value="{{ old($field['name']) ? old($field['name']) : (isset($field['value']) ? $field['value'] : (isset($field['default']) ? $field['default'] : '' )) }}"
        @include('crud::inc.field_attributes')
    >

    {{-- HINT --}}
    @if (isset($field['hint']))
        <p class="help-block">{!! $field['hint'] !!}</p>
    @endif
</div>


@if ($crud->checkIfFieldIsFirstOfItsType($field, $fields))
  {{-- FIELD EXTRA CSS  --}}
  {{-- push things in the after_styles section --}}

      @push('crud_fields_styles')
          <style type="text/css">
              .td10px{
                padding-right: 10px;
                padding-left: 10px;
                padding-bottom: 5px;
                border: 1px solid #f2f2f2;
                background-color: #f2f2f2;
              }
          </style>
      @endpush


  {{-- FIELD EXTRA JS --}}
  {{-- push things in the after_scripts section --}}

      @push('crud_fields_scripts')
            <script type="text/javascript">
                document.getElementById("bandid2").disabled = true;
                document.getElementById("stringid2").disabled = true;
                document.getElementById("athleticsid2").disabled = true;
            </script>
      @endpush
@endif

{{-- Note: most of the times you'll want to use @if ($crud->checkIfFieldIsFirstOfItsType($field, $fields)) to only load CSS/JS once, even though there are multiple instances of it. --}}