@section('after_scripts')

	<script>
		{{-- @if($crud->getActionMethod() === "edit") --}}
		$(document).ready(function () {

				var building = $('select[name="building_id"]');
				var room 	 = $('select[name="room_id"]');
				
				$.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content'), } });
				
				function watchBuilding () {
					var building_id = building.find('option:selected').val();
					if  ( building_id == '' ) return;
					$.ajax({
						url: '/{{ $crud->route }}/building/' + building_id + '/rooms',
						type: 'post',
						success: function (response) {
							var roomOptions = '';
							var old_room_id = '{{ $crud->getActionMethod() === "edit" ? $entry->room_id : "" }}'
							if(response.length > 0) {
								$.each(response, function (key, val) {
									if(old_room_id == val.id) {
										roomOptions += '<option value="' + val.id + '" selected>' + val.name + '</option>';
									} else {
										roomOptions += '<option value="' + val.id + '">' + val.name + '</option>';
									}
								});
							} else {
								roomOptions += '<option value="">-</option>';
							}

							room.html(roomOptions);
						}
					});
				}
				watchBuilding();

				building.change(function () { watchBuilding(); });

				@if($crud->getActionMethod() === "edit")
					console.log({{ $entry->room_id }});
				@endif
		});
		{{-- @endif --}}
	</script>
@endsection