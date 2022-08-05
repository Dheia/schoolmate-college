<div class="dropdown" style="display: initial;">
	<a href="#" class="btn btn-xs btn-default dropdown-toggle text-primary pl-1 action-btn dropdown-toggle-more" data-toggle="dropdown" title="More" id="dropdownMenu{{ $entry->id }}" aria-haspopup="true" aria-expanded="false">
	    {{-- More --}} <i class="fa fa-ellipsis-v"></i>
	</a>
	@if(isset($crud->data['dropdownButtons']))
		<ul class="dropdown-menu" aria-labelledby="dropdownMenu{{ $entry->id }}" style="right: 0 !important; left: auto !important;">
			@foreach($crud->data['dropdownButtons'] as $item)
				@if($item === "divider")
					<li class="divider"></li>
				@else
					@include('vendor.backpack.crud.buttons.' . $item)
				@endif
			@endforeach
		</ul>

		<script>
			$('.dropdown-toggle-more').dropdown();
		</script>
	@endif
</div>
