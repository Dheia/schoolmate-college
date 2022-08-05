@if ($crud->hasAccess('qr'))
	<a href="#" class="btn btn-xs btn-default" id="qr-btn-{{ $entry->getKey() }}" data-toggle="modal" data-target="#myModal{{ $entry->getKey() }}"><i class="fa fa-edit" ></i> QR</a>

	<div class="modal fade" id="myModal{{ $entry->getKey() }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title" id="myModalLabel">Locker Inventory</h4>
	      </div>
	      {{-- {{ Request::url().'/'.$entry->getKey() }}/qrcode --}}
	      <div class="modal-body{{ $entry->getKey() }}" id="dataToPrint{{ $entry->getKey() }}" >

				<div class="template" style="margin:5px;">
					<center>
						<div class="qr-title">
						{{Config::get('settings.schoolname')}}
						</div>
						<div class="qr-image">
							<h1>Loading...</h1>
						</div>
						<div class="qr-code">
							@php 
								$building = json_decode(\App\Models\Building::where('id', $entry->building_id)->first());
							@endphp
						</div>
						@if($building !== null)
						<div><p id="desc"><b>{{ $building->name ?? null }} - {{ $entry->name }} </b></p>
							</div>
						@endif
					</center>
				</div>
				
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	        <button type="button" class="btn btn-default" id="btnQRPrint{{ $entry->getKey() }}">Print</button>
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

			#crudTable {
				display: none;
			}

		}
		@page {
			margin: 0.7cm;
		}

	</style>
	<script>
		$('#btnQRPrint{{ $entry->getKey() }}').on('click', function(){
		 
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

			// $(".print_section").html(
			// 	$(".modal-body{{ $entry->getKey() }}").html()
			// );
			 
			// window.print();
		 // 	$(".print_section").html("");
		});


		// $(document).ready(function () {
		$('#qr-btn-{{ $entry->getKey() }}').on('click',  function () {
			// $('#myModal{{$entry->getKey()}}').modal('show');
			$.ajax({
				url: '/admin/locker-inventory/qr-view/{{ $entry->getKey() }}',
				success: function (response) {
					$('.modal-body{{ $entry->getKey() }} .qr-image').html(response);
				}
			});
		})

	</script>


	
@endif