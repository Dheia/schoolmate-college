@if ($entry->is_favorite == 0)
    <a href="{{ backpack_url('schoolstore/inventory/favorite/' . $entry->id) }}" class="btn btn-xs btn-default" id="lookup" data-style="zoom-in">
        <i class="fa fa-star"></i>&nbsp;Favorite
    </a>
@else
	<a href="{{ backpack_url('schoolstore/inventory/favorite/' . $entry->id) }}" class="btn btn-xs btn-default" id="lookup" data-style="zoom-in">
        <i class="fa fa-star"></i>&nbsp;Remove
    </a>
@endif