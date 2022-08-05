<script type="text/javascript">
        function getReady(){
            $("#legalguardianwrapper").hide();

        };
        
        function showTextbox(){
          JsonConstruct();
          var legalguardian = document.getElementById('legalguardian')
          if(legalguardian.checked){
            $("#legalguardianwrapper").show();
          }else{
            document.getElementById("legalguardian2").value = "";
            $("#legalguardianwrapper").hide();
          }
        }

        function JsonConstruct(){
          var father = 0;
          var stepfather = 0;
          var legalguardian3 = 0;
          var mother = 0;
          var stepmother = 0;
          
          //console.log("\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n");
          if(document.getElementById('cbfatherid').checked){
            father = 1;
          }else { father = 0; }

          if(document.getElementById('cbstepfatherid').checked){
            stepfather = 1;
          }else { stepfather = 0; }

          if(document.getElementById('cbmotherid').checked){
            mother = 1;
          }else { mother = 0; }

          if(document.getElementById('cbstepmotherid').checked){
            stepmother = 1;
          }else { stepmother = 0; }

          if(document.getElementById('legalguardian').checked){
            legalguardian3 = 1;
          }else { legalguardian3 = 0; }

          var arrayLivingwith = { "father":father, "step-father":stepfather, "mother":mother, "step-mother":stepmother, "legal_guardian":legalguardian3};
          var jsonLivingwith = JSON.stringify(arrayLivingwith);

          document.getElementById("{{ $field['attributes']['id'] }}").value = jsonLivingwith;
          console.log(document.getElementById("{{ $field['attributes']['id'] }}").value);
        }
     </script>
     @php
        if(old($field['name'])){
          $livingWith = json_decode(old($field['name']),true);
          $mother;
            $father;
            $stepFather;
            $stepMother;
            $legalGuardian;

            if($livingWith['father']){
                $father = true;
            }else{
                $father = false;
            }

            if($livingWith['mother']){
                $mother = true;
            }else{
                $mother = false;
            }

            if($livingWith['step-father']){
                $stepFather = true;
            }else{
                $stepFather = false;
            }

            if($livingWith['step-mother']){
                $stepMother = true;
            }else{
                $stepMother = false;
            }

            if($livingWith['legal_guardian']){
                $legalGuardian = true;
            }else{
                $legalGuardian = false;
            }
        }else{
          if(isset($field['value'])){
            $livingWith = json_decode($field['value'],true);
            $mother;
            $father;
            $stepFather;
            $stepMother;
            $legalGuardian;

            if($livingWith['father']){
                $father = true;
            }else{
                $father = false;
            }

            if($livingWith['mother']){
                $mother = true;
            }else{
                $mother = false;
            }

            if($livingWith['step-father']){
                $stepFather = true;
            }else{
                $stepFather = false;
            }

            if($livingWith['step-mother']){
                $stepMother = true;
            }else{
                $stepMother = false;
            }

            if($livingWith['legal_guardian']){
                $legalGuardian = true;
            }else{
                $legalGuardian = false;
            }
          }
        }
          
     @endphp


     <div @include('crud::inc.field_wrapper_attributes') >
      <h5><b>Living</b></h5>
      <label>The student will be living in the Philippines with (pls. check all that apply) *</label> <br>
      <table>
        <tr>
          <td><input
            @if (old($field['name']))
                @if($father)
                 checked="checked"
                @endif
              @else
                @if (isset($field['value']))
                  @if($father)
                   checked="checked"
                  @endif
                @endif
            @endif
            type="checkbox" name="father" id="cbfatherid" onclick="JsonConstruct()"></td>
          <td><label for="cbfatherid">Father</label></td>
          <td><input 
            @if (old($field['name']))
                @if($mother)
                 checked="checked"
                @endif
              @else
                @if (isset($field['value']))
                  @if($mother)
                   checked="checked"
                  @endif
                @endif
            @endif
            type="checkbox" name="mother" id="cbmotherid" onclick="JsonConstruct()"></td>
          <td><label for="cbmotherid">Mother</label></td>
        </tr>
        <tr>
          <td><input 
            @if (old($field['name']))
                @if($stepFather)
                 checked="checked"
                @endif
              @else
                @if (isset($field['value']))
                  @if($stepFather)
                   checked="checked"
                  @endif
                @endif
            @endif
            type="checkbox" name="step-father" id="cbstepfatherid" onclick="JsonConstruct()"></td>
          <td style="padding-right: 50px"><label for="cbstepfatherid">Step-Father</label></td>
          <td><input 
            @if (old($field['name']))
                @if($stepMother)
                 checked="checked"
                @endif
              @else
                @if (isset($field['value']))
                  @if($stepMother)
                   checked="checked"
                  @endif
                @endif
            @endif
            type="checkbox" name="step-mother" id="cbstepmotherid" onclick="JsonConstruct()"></td>
          <td><label for="cbstepmotherid">Step-Mother</label></td>
        </tr>
        <tr>
          <td><input 
            @if (old($field['name']))
                @if($legalGuardian)
                 checked="checked"
                @endif
              @else
                @if (isset($field['value']))
                  @if($legalGuardian)
                   checked="checked"
                  @endif
                @endif
            @endif
            type="checkbox" name="legalguardian" id="legalguardian" onclick="showTextbox()"></td>
          <td><label for="legalguardian">Legal Guardian</label></td>
          <td colspan="2"><label>{!! $field['label'] !!}</label>
    <input
        type="hidden"
        name="{{ $field['name'] }}"
        value="{{ old($field['name']) ? old($field['name']) : (isset($field['value']) ? $field['value'] : (isset($field['default']) ? $field['default'] : '' )) }}"
        @include('crud::inc.field_attributes')
    >
    @php 
      //old($field['name'])
    @endphp

    {{-- HINT --}}
    @if (isset($field['hint']))
        <p class="help-block">{!! $field['hint'] !!}</p>
    @endif</td>
        </tr>
      </table>
      </div>

     

     
    
      <?php
        $living = array('mother' => 'true', 'father' => 'false');
        // echo $field['name']."HERE SENSEI!";
        $myJSON = json_encode($living);

        //echo $myJSON;
        
      ?>







@if ($crud->checkIfFieldIsFirstOfItsType($field, $fields))
  {{-- FIELD EXTRA CSS  --}}
  {{-- push things in the after_styles section --}}

      @push('crud_fields_styles')
          <!-- no styles -->
      @endpush


  {{-- FIELD EXTRA JS --}}
  {{-- push things in the after_scripts section --}}

      @push('crud_fields_scripts')
          <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
      @endpush
@endif

{{-- Note: most of the times you'll want to use @if ($crud->checkIfFieldIsFirstOfItsType($field, $fields)) to only load CSS/JS once, even though there are multiple instances of it. --}}