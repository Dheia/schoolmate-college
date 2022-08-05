<?php 
	$isExist = App\Models\SchoolStoreInventoryQuantity::whereDate('created_at', \Carbon\Carbon::today())->exists();
	$isStartSet = false;
	$isEndSet = false;
	if($isExist) {
		if(App\Models\SchoolStoreInventoryQuantity::whereDate('created_at', \Carbon\Carbon::today())->where('is_start_quantity_set', 1))
		{ $isStartSet = true; }



		if(App\Models\SchoolStoreInventoryQuantity::whereDate('created_at', \Carbon\Carbon::today())
													  ->where('is_end_quantity_set', 0)
													  ->first())
		{ $isEndSet = true; }
	}
?>
@if($isStartSet && $isEndSet)
	<a href="{{ url('admin/schoolstore/inventory/end-item-inventory-for-today') }}" class="btn btn-primary">
		<i class="fa fa-plus"></i>&nbsp; Add End Inventory For Today
	</a>
@else
	<a href="javascript:void(0)" class="btn btn-success disabled">
		<i class="fa fa-plus"></i>&nbsp; End Inventory Is Set
	</a>
@endif