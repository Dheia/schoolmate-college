@php
	// dd(get_defined_vars());
	// $profits = \App\Models\ProfitsLossStatement::all();

	// $categories = \App\Models\ProfitsLossStatement::where('group_id', '=', null)->get();
	// $allCategories = \App\Models\ProfitsLossStatement::pluck('name','id')->all();

	// dd($categories);
	function tree_element($entry, $key, $all_entries, $crud)
	{
	    if (!isset($entry->tree_element_shown)) {
	        // mark the element as shown
	        
	    	$type = $entry->type == "percent" ? "Percentage (%)" : "Raw Score";
        	$sign = $entry->type == "percent" ? "%" : " pts.";

	        $all_entries[$key]->tree_element_shown = true;
	        $entry->tree_element_shown = true;

	        // show the tree element
	        echo '<li id="list_'.$entry->getKey().'">';
	        echo '<div>
	        		<span class="disclose"><span></span></span>' 
	        		. object_get($entry, $crud->reorder_label) 
	        		. '&nbsp;&nbsp;<span class="max badge"><b>'. $entry->max . $sign . '</b></span>'
	        		. '<span style="float: right;">';
    		
    		if( $crud->hasAccess('update') && 
    			app('request')->input('template_id') !== null &&   
    			app('request')->input('subject_id') !== null  &&   
    			app('request')->input('section_id') !== null) 
    		{		
    				echo '<a href="' . url($crud->route.'/'.$entry->getKey().'/edit?template_id=') . app('request')->input('template_id') . '&subject_id=' . app('request')->input('subject_id') . '&section_id=' . app('request')->input('section_id') . '">Edit</a>';
    		}
			
			echo  ' | '; 

			if($crud->hasAccess('delete')) {
	        	echo '<a href="" onclick="deleteEntry(this)" data-route="' . url($crud->route.'/'.$entry->getKey()) . '">Delete</a>';
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

	        // if it does have children, show them
	        if (count($children)) {
	            echo '<ol>';
	            foreach ($children as $key => $child) {
	                $children[$key] = tree_element($child, $child->getKey(), $all_entries, $crud);
	            }
	            echo '</ol>';
	        }
	        echo '</li>';
	    }

	    return $entry;
	}

@endphp


@push('after_styles')

	<link rel="stylesheet" href="{{ asset('vendor/backpack/nestedSortable/nestedSortable.css') }}">
	{{-- <link rel="stylesheet" href="{{ asset('vendor/backpack/crud/css/crud.css') }}"> --}}
	{{-- <link rel="stylesheet" href="{{ asset('vendor/backpack/crud/css/reorder.css') }}"> --}}
@endpush

{{-- FIELD JS - will be loaded in the after_scripts section --}}


	<div class="col-md-12"  style="padding: 0">

        <div class="panel padding-10">

            <div class="row">
                <div class="col-md-12">
					
					<table class="table table-bordered">
						<tbody>
							<tr>
								<td style="background: #FFF8F8;">
									<label for="">Template Name:</label>
								</td>
								<td>
									<?php 
										if(isset($_GET['template_id']) && $_GET['template_id'] !== 'undefined') {
											echo App\Models\GradeTemplate::where('id', app('request')->input('template_id') )
																			->first()
																			->name;
										}
									?>
								</td>
								<td style="background: #FFF8F8;">
									<label for="">Section:</label>
								</td>
								<td>
									<?php 
										if(isset($_GET['section_id']) && $_GET['section_id'] !== 'undefined') {
											echo App\Models\GradeTemplate::where('id', app('request')->input('section_id') )
																			->first()
																			->name;
										}
									?>
								</td>
								<td style="background: #FFF8F8;">
									<label for="">Subject:</label>
								</td>
								<td>
									<?php
										if(isset($_GET['subject_id']) && $_GET['subject_id'] !== 'undefined') {
											echo App\Models\SubjectManagement::where('id', app('request')->input('subject_id') )
																			->first()
																			->subject_code;
										} 
									?>
								</td>
							</tr>
						</tbody>
					</table>

					<ol class="sortable">
					    <?php
					    	
					    	$selectEntries = $entries->where('template_id', app('request')->input('template_id'))
					    							 ->where('section_id', app('request')->input('section_id'))
					    							 ->where('subject_id', app('request')->input('subject_id'))
					    							 ->where('teacher_id', backpack_auth()->user()->id)
					    							 ->all();

			                $all_entries = collect($selectEntries)->sortBy('lft')->keyBy($crud->getModel()->getKeyName());
			                $root_entries = $all_entries->filter(function ($item) {
			                    return $item->parent_id == 0;
			                });
			                foreach ($root_entries as $key => $entry){			                	
			                    $root_entries[$key] = tree_element($entry, $key, $all_entries, $crud);
			                }
			            ?>
        			</ol>
				</div>
			</div>

		</div>

	</div>

@push('after_scripts')
	<script>
		function deleteEntry(button) {
		// ask for confirmation before deleting an item
		// e.preventDefault();
		var button = $(button);
		var route = button.attr('data-route');
		var row = $("#crudTable a[data-route='"+route+"']").closest('tr');

		if (confirm("{{ trans('backpack::crud.delete_confirm') }}") == true) {
			$.ajax({
				url: route,
				type: 'DELETE',
				success: function(result) {
				// Show an alert with the result
					new PNotify({
						title: "{{ trans('backpack::crud.delete_confirmation_title') }}",
						text: "{{ trans('backpack::crud.delete_confirmation_message') }}",
						type: "success"
					});

					// Hide the modal, if any
					$('.modal').modal('hide');

					// Remove the details row, if it is open
					if (row.hasClass("shown")) {
						row.next().remove();
					}

					// Remove the row from the datatable
					row.remove();
				},
				error: function(result) {
					// Show an alert with the result
					new PNotify({
						title: "{{ trans('backpack::crud.delete_confirmation_not_title') }}",
						text: "{{ trans('backpack::crud.delete_confirmation_not_message') }}",
						type: "warning"
					});
				}
			});
			} else {
				// Show an alert telling the user we don't know what went wrong
				new PNotify({
				title: "{{ trans('backpack::crud.delete_confirmation_not_deleted_title') }}",
				text: "{{ trans('backpack::crud.delete_confirmation_not_deleted_message') }}",
				type: "info"
				});
			}
		}
	
	</script>

	{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script> --}}
	{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-contextmenu/2.7.0/jquery.contextMenu.min.js"></script> --}}
	{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-contextmenu/2.7.0/jquery.ui.position.js"></script> --}}
	{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.0/jquery-confirm.min.js"></script> --}}
	
@endpush
