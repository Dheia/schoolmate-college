@if( !$entry->qr_code && $crud->hasAccess('list') && !$entry->is_applicant)
	<li>
		<a href="{{ url($crud->route. '/' . $entry->getKey() .'/qr-code/generate') }}"  data-title="Generate QR Code" class="text-sm generateQRCode action-btn" title="Generate QR Code">
			<i class="fa fa-qrcode"></i> Generate QR Code
		</a>
	</li>
@elseif( $entry->rendered_qr_code && $crud->hasAccess('list') && !$entry->is_applicant)
	<li>
		<a href="javascript:void(0)"  data-route="{{ url($crud->route. '/' . $entry->getKey() .'/qr-code/render') }}"  data-qr="{{ $entry->rendered_qr_code }}"  data-id="{{ $entry->getKey() }}"  data-title="Show QR Code" class="text-sm showRenderedQR action-btn" title="Show QR Code">
			<i class="fa fa-qrcode"></i> Show QR Code
		</a>
	</li>
@endif

@push('after_scripts')
	{{-- <script>
		$('#qr-btn-{{ $entry->getKey() }}').on('click',  function () {
			$('#myModal{{$entry->getKey()}}').modal('show');
		})
	</script> --}}
@endpush