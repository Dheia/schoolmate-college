<!-- select2 -->
<div @include('crud::inc.field_wrapper_attributes') >
    <label>{!! $field['label'] !!}</label>
    @include('crud::inc.field_translatable_icon')
    <?php 
      $entity_model = $crud->getModel(); 
      $class = isset($field['class']) ? $field['class'] : 'col-sm-4';
      $selected_subject_id = [];
      if(isset($field['value']) && count($field['value']) > 0 && $field['value'] !== null) {
        $selected_subject_id = $field['value']->pluck('id')->toArray();
      }

    ?>
        {{-- {{ dd(count($field['value'])) }} --}}
        {{-- {{ dd($field['value']->pluck('id')->toArray()) }} --}}

    <div class="row" id="boxCheckList">
 {{--        @foreach ($field['model']::all() as $connected_entity_entry)
            <div class="{{ $class }}">
                <div class="checkbox">
                  <label>
                    <input type="checkbox"
                      name="{{ $field['name'] }}[]"
                      value="{{ $connected_entity_entry->getKey() }}"

                      @if( ( old( $field["name"] ) && in_array($connected_entity_entry->getKey(), old( $field["name"])) ) || (isset($field['value']) && in_array($connected_entity_entry->getKey(), $field['value']->pluck($connected_entity_entry->getKeyName(), $connected_entity_entry->getKeyName())->toArray())))
                             checked = "checked"
                      @endif > {!! $connected_entity_entry->{$field['attribute']} !!}
                  </label>
                </div>
            </div>
        @endforeach --}}
    </div>

    {{-- HINT --}}
    @if (isset($field['hint']))
        <p class="help-block">{!! $field['hint'] !!}</p>
    @endif
</div>

@section('after_scripts')
  <script>
    var base = window.location.protocol + '//' + window.location.host;
    var section_id = $('select[name="{{ $field['select_name'] }}"]').val();

    $(document).ready(function () {


      function getSectionSubject(sId) {
        $.ajax({
          url: window.location.protocol + '//' + window.location.host +'/admin/section-builder/api/get/section/'+sId+'/subjects',
          success: function (response) {
            subjects = '';
            {{-- var selected_id = {{ json_encode($selected_subject_id) }}; --}}
            
            $.each(response.subjects, function (key, val) {

              var checked = "";
              $.each(response.selected_subjects, function (key, selected_subjects) {
                  $.each(selected_subjects.subjects, function (key, subject) {
                    if(subject.id == val.id) {
                      checked = "checked='checked'";
                    }
                  });
              });

              subjects += '<div class="{{ $class }}">\
                              <div class="checkbox">\
                                <label>\
                                  <input type="checkbox" name="{{ $field['name'] }}[]" value="' + val.id + '" '+ checked +'>\
                                  ' + val.name_and_percent + '\
                                </label>\
                              </div>\
                          </div>';
            });
            $('#boxCheckList').html(subjects);
          }
        });
      }
      
      getSectionSubject(section_id);

      $('select[name="{{ $field['select_name'] }}"]').on('change', function () {
        getSectionSubject($(this).val());
      });
    
    });

  </script>
@endsection