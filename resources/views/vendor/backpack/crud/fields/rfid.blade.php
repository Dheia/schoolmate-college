<!-- text input -->
<div @include('crud::inc.field_wrapper_attributes')>
    <label>{!! $field['label'] !!}</label>
    @include('crud::inc.field_translatable_icon')

    @if(isset($field['prefix']) || isset($field['suffix'])) <div class="input-group"> @endif
        @if(isset($field['prefix'])) <div class="input-group-addon">{!! $field['prefix'] !!}</div> @endif
        {{-- <input
            type="text"
            name="{{ $field['name'] }}"
            value="{{ old($field['name']) ? old($field['name']) : (isset($field['value']) ? $field['value'] : (isset($field['default']) ? $field['default'] : '' )) }}"

            @include('crud::inc.field_attributes')
            
        >
        <span class="input-group-btn">
	        <button class="btn btn-success" type="button"><i class="fa fa-search"> Scan Now</i></button>
	    </span> --}}
        <div class="input-group">
          <span class="input-group-btn">
            <button class="btn btn-default" type="button" id="btnRfidScan"
            data-toggle="modal" data-target="#scanning_modal" data-rfid=""
            >Scan Now</button >
          </span>
          <input type="text" 
            name="{{ $field['name'] }}"
            value="{{ old($field['name']) ? old($field['name']) : (isset($field['value']) ? $field['value'] : (isset($field['default']) ? $field['default'] : '' )) }}"
          class="form-control" readonly="true" id="rfidVal" style="z-index: 0">
        </div>
        @if(isset($field['suffix'])) <div class="input-group-addon">{!! $field['suffix'] !!}</div> @endif
    @if(isset($field['prefix']) || isset($field['suffix'])) </div> @endif

    {{-- HINT --}}
    @if (isset($field['hint']))
        <p class="help-block">{!! $field['hint'] !!}</p>
    @endif
</div>

<div class="modal fade bd-example-modal-sm" id="scanning_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">RFID Scanner</h5>
        
      </div>
      <div class="modal-body">
        <input type="text" name="rfid_scanner" id="rfid_scanner" class="form-control" autofocus="true" style="opacity: 0;" autocomplete="off">
        <div class="progress">
          <div class="progress-bar progress-bar-striped active" role="progressbar"
          aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width:100%">
            Scanning...
          </div>
        </div>
      </div>
      
    </div>
  </div>
</div>


{{-- FIELD EXTRA CSS  --}}
{{-- push things in the after_styles section --}}

    {{-- @push('crud_fields_styles')
        <!-- no styles -->
    @endpush --}}


{{-- FIELD EXTRA JS --}}
{{-- push things in the after_scripts section --}}

    @push('after_scripts')
        <script>
            var rfid;

            $('#scanning_modal').on('shown.bs.modal', function (event) {
              $(this).find('[autofocus]').focus();
              $('#rfidVal').val("");
            })

            $('#scanning_modal').on('hidden.bs.modal', function (event) {
              
              var rfid = $('#rfid_scanner').val();
              $('#rfidVal').val(rfid);
            })

            $(document).keypress(function(e) {
                if(e.which == 13) {
                    e.preventDefault();
                    $('#scanning_modal').modal('hide');

                }
            });


        </script>
    @endpush


{{-- Note: you can use @if ($crud->checkIfFieldIsFirstOfItsType($field, $fields)) to only load some CSS/JS once, even though there are multiple instances of it --}}