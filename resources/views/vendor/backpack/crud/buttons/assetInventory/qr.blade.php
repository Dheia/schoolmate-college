@if ($crud->hasAccess('qr'))
	<a href="javascript:void(0)" id="qr-btn-{{ $entry->getKey() }}" class="btn btn-xs btn-default action-btn" target-modal="#myModal{{ $entry->getKey() }}" title="QR Code Code"><i class="fa fa-qrcode" ></i> QR</a>
	
	<div class="modal fade" id="myModal{{ $entry->getKey() }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title" id="myModalLabel">QR</h4>
	      </div>
	      {{-- {{ Request::url().'/'.$entry->getKey() }}/qrcode --}}
	      <div class="modal-body{{ $entry->getKey() }}" id="dataToPrint{{ $entry->getKey() }}" >

				<div class="template">
					<center>
						<div class="qr-title">
						{{Config::get('settings.schoolname')}}
						</div>
						<div class="qr-image">
						<h1>Loading...</h1>
						</div>
						<div class="qr-code">
							@php 
								$room = json_decode(\App\Models\Room::where('id', $entry->room_id)->get());
								$getBuildingId = $room->building_id ?? '';
	// 
								$building = json_decode(\App\Models\Building::where('id', $getBuildingId)->get());

							@endphp
						</div>
						<p id="desc"><b>{{ $entry->name ?? '' }} - {{ $room->name ?? '' }}</b></p>
					</center>
				</div>
				
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	        <button type="button" class="btn btn-default" id="btnPrint{{ $entry->getKey() }}">Print</button>
	        {{-- <button type="button" class="btn btn-primary">Save changes</button> --}}
	      </div>

	      <div class="clearfix"></div>
	    </div>
	  </div>
	</div>

	<div class="template_print"></div>
	<style>
		.printable {
			display:none;
		}
	
		

		@media print {
		 	
		 	#main-body {display: none;}
		 	.template_print {display:block; margin: 20px;}
			.qr-code {
				width: 90%;
			}
			.qr-title {
				padding: 10px;
			}

		}
		@page {
			margin: 0.7cm;
		}

	</style>

<script>
		$('#btnPrint{{ $entry->getKey() }}').on('click', function(){
		 // alert("Hello");

			var mywindow = window.open('', 'PRINT', 'height=400,width=600');
		    var elem = "dataToPrint{{ $entry->getKey() }}";

			mywindow.document.write('<html><head><title>' + document.title  + '</title>');
		    mywindow.document.write('</head><body >');
		    // mywindow.document.write('<h1 style="text-align: center;"> QR</h1>');
		    mywindow.document.write(document.getElementById(elem).innerHTML);
		    mywindow.document.write('</body></html>');
 	

		    mywindow.document.close(); // necessary for IE >= 10
		    mywindow.focus(); // necessary for IE >= 10*/

		    mywindow.print();
		    mywindow.close();

		    return true;
		});

		// $(document).ready(function () {
		$('#qr-btn-{{ $entry->getKey() }}').on('click',  function () {
			$('#myModal{{$entry->getKey()}}').modal('show');
			$.ajax({
				url: '/admin/asset-inventory/qr-view/{{ $entry->getKey() }}',
				success: function (response) {
					$('.modal-body{{ $entry->getKey() }} .qr-image').html(response);
				}
			});
		})

</script>
@endif
