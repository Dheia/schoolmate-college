@if ($crud->hasAccess('qr'))
	<a href="#" class="btn btn-xs btn-default" data-toggle="modal" data-target="#myModal{{ $entry->getKey() }}"><i class="fa fa-edit" ></i> QR</a>


	<!-- Modal -->
	<div class="modal fade" id="myModal{{ $entry->getKey() }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title" id="myModalLabel">QR</h4>
	      </div>
	      {{-- {{ Request::url().'/'.$entry->getKey() }}/qrcode --}}
	      <div class="modal-body" id="dataToPrint">

				
				<div style="">
					<center>
						
						<br>
						{!! QrCode::size(150)->generate(Request::root() . '/inventory/' . $entry->getKey() . '/show'); !!} <br>
						@php 
							$room = json_decode(\App\Models\Room::where('id', $entry->room_id)->get());
							$getBuildingId = $room[0]->	building_id;

							$building = json_decode(\App\Models\Building::where('id', $getBuildingId)->get());

						@endphp
						<p id="desc"><b>{{ $entry->name }} - {{ $room[0]->name }}</b></p>
					</center>
				</div>
				{{-- <img src="{!! QrCode::generate('Make me into a QrCode!'); !!}"> --}}
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	        <button type="button" class="btn btn-default" onclick="print()">Print</button>
	        {{-- <button type="button" class="btn btn-primary">Save changes</button> --}}
	      </div>

	      <div class="clearfix"></div>
	    </div>
	  </div>
	</div>


	<script>
		function print () {
			var divToPrint = document.getElementById('dataToPrint');
			var popupWin = window.open('', '_blank', 'width=400,height=400');

			popupWin.document.open();
			popupWin.document.write(
				'<html><style>#desc { margin: 0; margin-top: 40px; } img { vertical-align: middle; } #sizer{ width: 140px; height: 95px; padding: 5px; box-sizing: border-box; }</style><body onlod="window.print()">' + divToPrint.innerHTML + '</html>'
				);
			popupWin.document.close();
		};
	</script>
@endif