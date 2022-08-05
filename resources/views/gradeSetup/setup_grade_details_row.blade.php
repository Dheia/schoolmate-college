@php
	// dd(get_defined_vars());
	// $profits = \App\Models\ProfitsLossStatement::all();
	
	// $categories = \App\Models\ProfitsLossStatement::where('group_id', '=', null)->get();
	// $allCategories = \App\Models\ProfitsLossStatement::pluck('name','id')->all();
	// dd(backpack_auth()->user()->hasRole("Coordinator"));
	function tree_element($entry, $key, $all_entries, $crud, $u_id, $setupGrade)
	{
	    if (!isset($entry->tree_element_shown)) {
	        // mark the element as shown
	        
	    	$type = $entry->type == "percent" ? "Percentage (%)" : "Raw Score";
        	$sign = $entry->type == "percent" ? "%" : " pts.";

	        $all_entries[$key]->tree_element_shown = true;
	        $entry->tree_element_shown = true;

	        // show the tree element
	        echo '<li id="list_'.$entry->getKey().'">';
	        echo '<div id="wrap-'. $u_id .'">
	        		<span class="disclose"><span></span></span>' 
	        		. object_get($entry, $crud->reorder_label) 
	        		. '&nbsp;&nbsp;<span class="max badge"><b>'. $entry->max . $sign . '</b></span>'
	        		. '<span style="float: right;">';
    		
    		if( $crud->hasAccess('update') && count($setupGrade->encode_grades) < 1) 
    		{		
				echo '<a target="_blank" href="' . url($crud->route.'-item/'.$entry->getKey().'/edit') . '">Edit</a>';
    		}
			

			if($crud->hasAccess('delete')) {
				if(backpack_auth()->user()->hasRole("Coordinator") && count($setupGrade->encode_grades) < 1) {
					echo  ' | '; 
        			echo '<a href="javascript:void(0)" 
        					 class="deleteItem" data-id="' . $entry->getKey() . '" 
        					 data-route="' . url($crud->route.'-item/'.$entry->getKey()) . '?item">
        					 	Delete
    					 </a>';
				}
				else if (backpack_auth()->user()->hasRole("Teacher") && !$setupGrade->is_approved && count($setupGrade->encode_grades) < 1) {
					echo  ' | '; 
        			echo '<a href="javascript:void(0)" 
        					 class="deleteItem" data-id="' . $entry->getKey() . '" 
        					 data-route="' . url($crud->route.'-item/'.$entry->getKey()) . '?item">
        					 	Delete
    					 </a>';
				} 
				else {}
			}
        	echo '</span>'
        	 . '</div>';

	        // see if this element has any children
	        $children = [];
	        foreach ($all_entries as $key => $subentry) {
	            if ($subentry->parent_id == $entry->getKey()) {
	                $children[] = $subentry;
	            }
	        }

	        $children = collect($children)->sortBy('lft');

	        // if does have children, re-iterate tree_element function
	        if (count($children)) {
	            echo '<ol>';
	            foreach ($children as $key => $child) {
	                $children[$key] = tree_element($child, $child->getKey(), $all_entries, $crud, $u_id, $setupGrade);
	            }
	            echo '</ol>';
	        }
	        echo '</li>';
	    }

	    return $entry;
	}

@endphp


{{-- FIELD JS - will be loaded in the after_scripts section --}}

<style>
	.badge:hover {
	  color: #ffffff;
	  text-decoration: none;
	  cursor: pointer;
	}
	.badge-error {
	  background-color: #b94a48;
	}
	.badge-error:hover {
	  background-color: #953b39;
	}
	.badge-warning {
	  background-color: #f89406;
	}
	.badge-warning:hover {
	  background-color: #c67605;
	}
	.badge-success {
	  background-color: #468847;
	}
	.badge-success:hover {
	  background-color: #356635;
	}
	.badge-info {
	  background-color: #3a87ad;
	}
	.badge-info:hover {
	  background-color: #2d6987;
	}
	.badge-inverse {
	  background-color: #333333;
	}
	.badge-inverse:hover {
	  background-color: #1a1a1a;
	}
</style>

	<div class="col-md-12"  style="padding: 0">
			
        <div class="panel padding-10">
						
			@if($setupGrade->is_approved !== "Approved")
				<div class="row">
					<div class="col-md-12">

						<div class="panel panel-warning">
							<div class="panel-heading"><b>Note: </b>
								Please be sure click the save button below when you added a new module or when you reorder module or else you won't see the expected output.
							</div>
						</div>
					</div>
				</div>
			
				<button id="addItem{{ $unique_id }}" class="btn btn-success"><i class="fa fa-plus"></i> &nbsp;Add Item</button>
			@endif
            <div class="row" id="mainContent">
                <div class="col-md-12">
					<ol class="sortable">
					    <?php
			                $all_entries = collect($selectEntries)->sortBy('lft')->keyBy($crud->getModel()->getKeyName());
			                $root_entries = $all_entries->filter(function ($item) {
			                    return $item->parent_id == 0;
			                });
			                foreach ($root_entries as $key => $entry){
			                    $root_entries[$key] = tree_element($entry, $key, $all_entries, $crud, $unique_id, $setupGrade);
			                }
			            ?>
        			</ol>
				</div>
			</div>
				
			{{-- ADD ITEM --}}
			<div class="row p-b-20 p-t-20" id="addItemForm" style="display: none;">
				<form id="form{{ $unique_id }}">
					<div class="form-group col-md-12">
						<label for="name">Component Name</label>
						<input type="text" name="name" id="name" class="form-control" style="display: block; width: 100%;">
					</div>

					<br><br><br><br>

					<div class="form-group col-md-12">
						<label for="type">Type</label>
						<select name="type" id="type" class="form-control" style="display: block; width: 100%;">
							<option selected value>-</option>
							<option value="percent">Percent</option>
							<option value="raw">Raw</option>
						</select>
					</div>

					<br><br><br><br>

					<div class="form-group col-md-12">
						<label for="max">Max</label>
						<input type="number" name="max" id="max" class="form-control" style="display: block; width: 100%;">
					</div>

					<br><br><br><br>

					<div class="form-group col-md-12">
						<label for="description">Description</label>
						<textarea type="text" name="description" id="description" class="form-control" style="display: block; width: 100%;"></textarea>
					</div>
					<div class="clearfix"></div>

					<div class="form-group col-md-12 m-t-20">
						<button id="btnSubmit{{ $unique_id }}" class="btn btn-success">Add</button>
						<a href id="btnCancel{{ $unique_id }}" class="btn btn-warning">Cancel</a>
					</div>
				</form>
				<div class="clearfix"></div>
			</div>
			{{-- .ADD ITEM --}}

			<div class="clearfix"></div>
			
			@if($setupGrade->is_approved !== "Approved")
				<button id="toArray-{{ $unique_id }}" class="btn btn-success ladda-button" data-style="zoom-in">
					<span class="ladda-label"><i class="fa fa-save"></i> {{ trans('backpack::crud.save') }}</span>
				</button>
			@endif
		</div>

	</div>

{{-- @push('after_scripts') --}}

	<script type="text/javascript">
	    jQuery(document).ready(function($) {
	    var unique_id = {{ $unique_id }};
	    // initialize the nested sortable plugin
	    $('.sortable').nestedSortable({
	    	fallbackOnBody: true,
	        forcePlaceholderSize: true,
	        handle: 'div',
	        helper: 'clone',
	        items: 'li',
	        opacity: .6,
	        placeholder: 'placeholder',
	        revert: 250,
	        tabSize: 25,
	        tolerance: 'pointer',
	        toleranceElement: '> div',
	        maxLevels: {{ $crud->reorder_max_level ?? 3 }},

	        isTree: true,
	        expandOnHover: 700,
	        startCollapsed: false
	    });

	    $('#wrap-' + unique_id + ' .disclose').on('click', function() {
	        $(this).closest('li').toggleClass('mjs-nestedSortable-collapsed').toggleClass('mjs-nestedSortable-expanded');
	    });

	    $('#toArray-' + unique_id).click(function(e){
	        // get the current tree order
	        arraied = $('ol.sortable').nestedSortable('toArray', {startDepthCount: 0});

	        // send it with POST
	        $.ajax({
	            url: '{{ url($crud->route) }}/reorder',
	            type: 'POST',
	            data: { tree: arraied },
	        })
	        .done(function() {
	            //console.log("success");
	            new PNotify({
	                        title: "{{ trans('backpack::crud.reorder_success_title') }}",
	                        text: "{{ trans('backpack::crud.reorder_success_message') }}",
	                        type: "success"
	                    });
	          })
	        .fail(function() {
	            //console.log("error");
	            new PNotify({
	                        title: "{{ trans('backpack::crud.reorder_error_title') }}",
	                        text: "{{ trans('backpack::crud.reorder_error_message') }}",
	                        type: "danger"
	                    });
	          })
	        .always(function() {
	            console.log("complete");
	        });

	    });

	    $.ajaxPrefilter(function(options, originalOptions, xhr) {
	        var token = $('meta[name="csrf_token"]').attr('content');

	        if (token) {
	            return xhr.setRequestHeader('X-XSRF-TOKEN', token);
	        }
	    });

	});
	</script>
	{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script> --}}
	{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-contextmenu/2.7.0/jquery.contextMenu.min.js"></script> --}}
	{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-contextmenu/2.7.0/jquery.ui.position.js"></script> --}}
	{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.0/jquery-confirm.min.js"></script> --}}
	
	@php
		$template_id =  $setupGrade->template_id;
		$subject_id  =  $setupGrade->subject_id;
		$section_id  =  $setupGrade->section_id;
	@endphp

	<script>
		var unique_id 	= {{ $unique_id }};
		var template_id = {{ $template_id }};
		var subject_id  = {{ $subject_id }};
		var section_id  = {{ $section_id }};

		$('#addItem{{ $unique_id }}').click(function () {
			$(this).css('display', 'none');
			$('#mainContent, #toArray-' + unique_id).css('display', 'none');
			$('#addItemForm').css('display', 'block');			
		});

		$('#btnCancel' + unique_id).click(function (e) {
			e.preventDefault();
			$(this).css('display', 'inline-block');
			$('#addItem' + unique_id).css('display', 'inline-block');
			$('#mainContent, #toArray-' + unique_id).css('display', 'block');
			$('#addItemForm, #btnSubmit ' + unique_id).css('display', 'none');	
		});


		$('#btnSubmit' + unique_id).click(function (e) {
			e.preventDefault();

			var formData = $('#form{{ $unique_id }}').serialize();
			$.ajax({
				url: "{{ url($crud->route) }}/add-item?id={{ $unique_id }}&" + formData,
				success: function (response) {

					if(response.status == "ERROR") {
						new PNotify({
							title: response.status,
							text: response.message,
						});
					}

					if(response.status == "OK") {
						var max = '';
						if(response.data.type == 'percent') {
							max = response.data.max + '%';
						} else if (response.data.type == 'raw') {
							max = response.data.max + 'pts.';
						} else {
							max =  response.max;
						}
						$('.sortable')
							.append(
								'<li id="list_' + response.data.id + '" class="mjs-nestedSortable-leaf">\
									<div id="wrap-' + response.data.id + '" class="ui-sortable-handle">\
										<span class="disclose"><span></span></span>\
										' + response.data.name + '&nbsp;&nbsp;\
										<span class="max badge">\
											<b>' + max + '</b>\
										</span>\
										<span style="float: right">\
											<a target="_blank" href="{{ url($crud->route) }}/' + response.data.id + '/edit">\
												Edit\
											</a> \
											| \
											<a onclick="deleteEntry(this)" href data-route="' + "{{ url($crud->route) }}/" + response.data.id + '">Delete</a>\
										</span>\
									</div>\
								</li>'
							);

						$('#btnCancel').css('display', 'inline-block');
						$('#addItem' + unique_id).css('display', 'inline-block');
						$('#mainContent, #toArray-' + unique_id).css('display', 'block');
						$('#addItemForm, #btnSubmit ' + unique_id).css('display', 'none');	
					}
				}
			});
		});
	</script>


  <script type="text/javascript">
    $(document).ready(function () {
      $('.deleteItem').click(function (e) {
      	var _this = $(this);
        $.alert({
            title: 'Delete',
            content: 'Grade Items tagged to this will also be deleted. Are you sure want to delete this item?',
            buttons: {
            	yes: function() {
            		$.ajax({
            			url: '/{{ $crud->route }}/' + _this.attr('data-id') + '/item',
            			type: 'DELETE',
            			success: function (response) {
            				if(!response.error) {
            					_this.closest('li').remove();
            				}

	        				$.alert({
        						title:  response.error ? 'Error' : 'Success',
        						content: response.message
        					});
            			},
            			error: function (response) {
            				console.log(response.responseJSON);
            				$.alert({
            					title: 'Error',
            					content: response.responseJSON.error
            				});
            			}
            		});
            	},
            	cancel: function () {}
            }
        });
      });
    });
  </script>
{{-- @endpush --}}