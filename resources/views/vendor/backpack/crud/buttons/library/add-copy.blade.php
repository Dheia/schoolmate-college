@if ($crud->hasAccess('add-copy'))
	<a href="{{ url($crud->route.'/#addModal') }}"  
		data-route="{{ url($crud->route.'/'.$entry->getKey()) }}" 
		data-toggle="modal" 
		data-id="{{ $entry->getKey() }}" 
		data-title="{{$entry->getAttributeValue('title') }}"
		data-call-number="{{$entry->getAttributeValue('call_number') }}" 
		data-isbn="{{$entry->getAttributeValue('isbn') }}" 
		data-code="{{$entry->getAttributeValue('code') }}"
		data-accession-number="{{$entry->getAttributeValue('accession_number') }}"  
		data-target="#addModal" 
		class="btn btn-xs btn-default action-btn">
		<i class="fa fa-plus"></i> 
		Make Copy
	</a>
@endif