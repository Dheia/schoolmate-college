<?php 
	$isExist = App\Models\SchoolStoreInventoryQuantity::whereDate('created_at', \Carbon\Carbon::today())->exists();
?>

@if(!$isExist)
	<a href="{{ url('admin/schoolstore/inventory/start-item-inventory-for-today') }}" class="btn btn-primary">
		<i class="fa fa-plus"></i>&nbsp; Add Start Inventory For Today
	</a>
@else
	<a href="javascript:void(0)" class="btn btn-success disabled">
		<i class="fa fa-plus"></i>&nbsp; Start Inventory Iss Set
	</a>
@endif