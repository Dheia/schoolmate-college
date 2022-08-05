@if ($crud->hasAccess('create'))
	<a href="javascript:void(0)" onclick="bulkCloneEntries(this)" class="btn btn-default"><i class="fa fa-clone"></i> Clone</a>
@endif

@push('after_scripts')
<script>
	if (typeof bulkCloneEntries != 'function') {
	  function bulkCloneEntries(button) {

	      if (typeof crud.checkedItems === 'undefined' || crud.checkedItems.length == 0)
	      {
	      	new PNotify({
	              title: "{{ trans('backpack::crud.bulk_no_entries_selected_title') }}",
	              text: "{{ trans('backpack::crud.bulk_no_entries_selected_message') }}",
	              type: "warning"
	          });

	      	return;
	      }

	      var message = "Are you sure you want to clone these :number entries?";
	      message = message.replace(":number", crud.checkedItems.length);

	      // show confirm message
	      if (confirm(message) == true) {
	      		var ajax_calls = [];

		        // for each crud.checkedItems
		        crud.checkedItems.forEach(function(item) {
	      		  var clone_route = "{{ url($crud->route) }}/"+item+"/clone";

		      	  // submit an AJAX delete call
	      		  ajax_calls.push($.ajax({
		              url: clone_route,
		              type: 'POST',
		              success: function(result) {
		                  // Show an alert with the result
		                  new PNotify({
		                      title: "Entry cloned",
		                      text: "A new entry has been added, with the same information as this one.",
		                      type: "success"
		                  });
		              },
		              error: function(result) {
		                  // Show an alert with the result
		                  new PNotify({
		                      title: "Cloning failed",
		                      text: "The new entry could not be created. Please try again.",
		                      type: "warning"
		                  });
		              }
		          }));

		      });

		      $.when.apply(this, ajax_calls).then(function ( ajax_calls ) {
		      		crud.checkedItems = [];
		      		crud.table.ajax.reload();
				});
	      }
      }
	}
</script>
@endpush