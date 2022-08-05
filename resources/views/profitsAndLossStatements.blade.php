@php
	$profits = \App\Models\ProfitsLossStatement::all();

	$categories = \App\Models\ProfitsLossStatement::where('group_id', '=', null)->get();
	$allCategories = \App\Models\ProfitsLossStatement::pluck('name','id')->all();

	// dd($categories);
@endphp
@extends("backpack::layout")

@section('header')
    <section class="content-header">
        <h1>
        	Profits And Loss Statements
        	{{-- <small>{{ trans('backpack::base.first_page_you_see') }}</small> --}}
        </h1>
        <ol class="breadcrumb">
        	<li><a href="{{ url(config('backpack.base.route_prefix', 'admin')) }}">{{ config('backpack.base.project_name') }}</a></li>
        	<li class="active">{{ trans('backpack::base.dashboard') }}</li>
        </ol>
    </section>
@endsection

@push('after_styles')
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.0/jquery-confirm.min.css">
	<link rel="stylesheet" href="{{ asset('css/hierarchyTree.css') }}">
	
@endpush

{{-- FIELD JS - will be loaded in the after_scripts section --}}

@section('content')
	<div class="row">
		<div class="col-md-12">

			<div class="box">

				<div class="box-header with-border">
			      <a href="{{ URL::to('/admin/profits-loss-statement/create') }}" class="btn btn-primary">Add Profit And Loss Statement</a>
			    </div>
				
				<div class="box-body row display-flex-wrap" style="display: flex; flex-wrap: wrap;">
					<ul id="tree1">
				        @foreach($categories as $category)
				            <li>
				            	@if($category->hierarchy_type == "Group")
				            		<i class="indicator glyphicon glyphicon-plus"></i>
				            		<a href="javascript:void(0)" class="context-menu-one" dataId="{{ $category->id }}" itemType="{{ $category->hierarchy_type }}">
				            			{{ $category->name }}
				            		</a>
								@else
									<a href="javascript:void(0)" class="context-menu-one" dataId="{{ $category->id }}" itemType="{{ $category->hierarchy_type }}">
										{{ $category->name }}
									</a>
				            	@endif
				                @if(count($category->childs))
				                    @include('manageChild',['childs' => $category->childs])
				                @endif
				            </li>
				        @endforeach
				    </ul>
				</div>

			</div>
	    </div>
    </div>
@endsection

@push('after_scripts')
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-contextmenu/2.7.0/jquery.contextMenu.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-contextmenu/2.7.0/jquery.ui.position.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.0/jquery-confirm.min.js"></script>
	<script>
		$.fn.extend({
	    treed: function (o) {
		      
		      var openedClass = 'glyphicon glyphicon-minus';
		      var closedClass = 'glyphicon glyphicon-plus';
		      
		      if (typeof o != 'undefined'){
		        if (typeof o.openedClass != 'undefined'){
		        openedClass = o.openedClass;
		        }
		        if (typeof o.closedClass != 'undefined'){
		        closedClass = o.closedClass;
		        }
		      };
		      
		        /* initialize each of the top levels */
		        var tree = $(this);
		        tree.addClass("tree");
		        tree.find('li').has("ul").each(function () {
		            var branch = $(this);
		            branch.prepend("");
		            branch.addClass('branch');
		            branch.on('click', function (e) {
		                if (this == e.target) {
		                    var icon = $(this).children('i:first');
		                    icon.toggleClass(openedClass + " " + closedClass);
		                    $(this).children().children().toggle();
		                }
		            })
		            branch.children().children().toggle();
		        });
		        /* fire event from the dynamically added icon */
		        tree.find('.branch .indicator').each(function(){
		            $(this).on('click', function () {
		                $(this).closest('li').click();
		            });
		        });
		        /* fire event to open branch if the li contains an anchor instead of text */
		        tree.find('.branch > a').each(function () {
		            $(this).on('click', function (e) {
		                $(this).closest('li').click();
		                e.preventDefault();
		            });
		        });
		        /* fire event to open branch if the li contains a button instead of text */
		        tree.find('.branch>button').each(function () {
		            $(this).on('click', function (e) {
		                $(this).closest('li').click();
		                e.preventDefault();
		            });
		        });
		    }
		});
		/* Initialization of treeviews */
		$('#tree1').treed();
	</script>

	<script>
		$(function() {
	        $.contextMenu({
	            selector: '.context-menu-one', 
	            callback: function(key, options) {
	                var id = options.$trigger[0].attributes.dataid.value;
	                var hType = options.$trigger[0].attributes.itemType.value;
	                if(key === "edit") {
	                	window.location.href = window.location.protocol + "//" + window.location.host + "/admin/profits-loss-statement/" + id + "/edit";
	                }
	                else if (key === "delete") {
	                	if(hType == "Group") {
							$.confirm({
							    title: 'Warning!',
							    content: 'Are you sure you want to delete this item? and child item(s) will be also deleted.',
							    buttons: {
							        yes: function () {
		                				window.location.href = window.location.protocol + "//" + window.location.host + "/admin/api/profits-loss-statement/" + id + "/delete";
							        },
							        cancel: function () { },
							    }
							});
						} else {
							$.confirm({
							    title: 'Warning!',
							    content: 'Are you sure you want to delete this item?',
							    buttons: {
							        yes: function () {
		                				window.location.href = window.location.protocol + "//" + window.location.host + "/admin/api/profits-loss-statement/" + id + "/delete";
							        },
							        cancel: function () { },
							    }
							});
						}
	                }
	            },
	            items: {
	                "edit": {name: "Edit", icon: "edit", accesskey: "e"},
	                "delete": {name: "Delete", icon: "delete", accesskey: "d"},
	                "sep1": "---------",
	                "quit": { 
	                	name: "Quit", 
                		icon: function(){
	                    	return 'context-menu-icon context-menu-icon-quit';
	                	},
	                	accesskey: "q"
	            	}
	            }
	        });

	        $('.context-menu-one').on('click', function(e){
	            console.log('clicked', this);
	        })    
	    });
	</script>
@endpush
