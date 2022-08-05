
<div @include('crud::inc.field_wrapper_attributes') >
    <label for="{{ $field['name'] }}">{!! $field['label'] !!}</label>
    @include('crud::inc.field_translatable_icon')
	
	{{ old($field['name']) }}

    <select class="form-control" name="currency" id="select-currency">
    	<option><i>Loading Currencies...</i></option>
    </select>

    {{-- HINT --}}
    @if (isset($field['hint']))
        <p class="help-block">{!! $field['hint'] !!}</p>
    @endif
</div>

@push('crud_fields_styles')
	<link href="{{ asset('vendor/backpack/select2/select2.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('vendor/backpack/select2/select2-bootstrap-dick.css') }}" rel="stylesheet" type="text/css" />
@endpush

{{-- FIELD JS - will be loaded in the after_scripts section --}}
@push('crud_fields_scripts')
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="{{ asset('vendor/backpack/select2/select2.js') }}"></script>
	<script>
		$.ajax({
			url: 'https://restcountries.eu/rest/v2/',
			success: function (data) {

				var option = '<option selected disabled>Select Currency</option>';
				var currency_id = 0;

				if($('input[name="currency_id"]').val() !== "") {
					currency_id = $('input[name="currency_id"]').val();
				} else {
					$('input[name="currency_id"').val(currency_id);
				}
				
				$.each(data, function(key, val) {
					if(currency_id == key) {
						option += '<option value="' + key + '" data-currency="' + val.currencies[0].code + '" selected>'+ val.currencies[0].code +' - ' + val.currencies[0].name + '</option>';
					} else {
						option += '<option value="' + key + '" data-currency="' + val.currencies[0].code + '">'+ val.currencies[0].code +' - ' + val.currencies[0].name + '</option>';
					}
				});

				$('#select-currency').html(option).select2();

				$('#select-currency').on('change', function () {
					$('input[name="currency_id"').val(this.value);
				});
			}			
		})
	</script>
@endpush