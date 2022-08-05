@push('crud_fields_scripts')
	<script>
		function getClass () {
		    var onlineClasses = {!! json_encode($onlineClasses) !!};
		    var online_class_id     = $('select[name="online_class_id"]');

		    options = '<option value="" style="visibility: hidden;">Select a group...</option>';
		    $.each(onlineClasses, function (key, val) {
		      options += '<option value="' + val.id + '"><span class="dot" style="background-color:'+val.color+';"></span>' + val.name + '</option>';
		    });

		    online_class_id.html(options);
		}
		$(document).ready(function () {
		    getClass();
		});
	</script>
@endpush