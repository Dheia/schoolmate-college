<!-- bootstrap daterange picker input -->



<div @include('crud::inc.field_wrapper_attributes') >
    <!-- DATE PICKER -->
    <div class="row">
        <div class='col-md-12'>
           <div class="form-group">
              {{-- <input type="text" name="daterange" id="daterange" /> --}}
              <label>{!! $field['label'] !!}</label>
              <div class='input-group date'>
                 <input type='text' class="form-control" name="daterange" id="daterange"/>
                 <span class="input-group-addon">
                 <span class="fa fa-calendar"></span>
                 </span>
              </div>
           </div>
        </div>
    </div>

    {{-- HINT --}}
    @if (isset($field['hint']))
        <p class="help-block">{!! $field['hint'] !!}</p>
    @endif
</div>

{{-- ########################################## --}}
{{-- Extra CSS and JS for this particular field --}}
{{-- If a field type is shown multiple times on a form, the CSS and JS will only be loaded once --}}
{{-- FIELD CSS - will be loaded in the after_styles section --}}
    @push('after_styles')
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker3.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/bower_components/bootstrap-daterangepicker/daterangepicker.css') }}">
    @endpush

    {{-- FIELD JS - will be loaded in the after_scripts section --}}
    @push('after_scripts')
    <script type="text/javascript" src="{{ asset('vendor/adminlte/bower_components/moment/moment.js') }}"></script>
    <script src="{{ asset('vendor/adminlte/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('vendor/adminlte/bower_components/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
    <script>
        $('#daterange').daterangepicker({
          ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
          } 
        });
    </script>
    @endpush
{{-- End of Extra CSS and JS --}}

