<ul>
@foreach($childs as $child)
	<li>
	
		@if($child->hierarchy_type == "Group")
    		<i class="indicator glyphicon glyphicon-plus"></i>
	    	<a href="javascript:void(0)" class="context-menu-one" dataId="{{ $child->id }}" itemType="{{ $child->hierarchy_type }}">
    			{{ $child->name }}
    		</a>
    	@else
	    	<a href="javascript:void(0)" class="context-menu-one" dataId="{{ $child->id }}" itemType="{{ $child->hierarchy_type }}">
    			{{ $child->name }}
    		</a>
		@endif
	@if(count($child->childs))
            @include('manageChild',['childs' => $child->childs])
        @endif
	</li>
@endforeach
</ul>