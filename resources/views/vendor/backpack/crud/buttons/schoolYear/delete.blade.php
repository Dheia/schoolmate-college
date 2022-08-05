@if ($crud->hasAccess('delete') && $entry->isActive == 0 && $entry->total_enrollments <= 0 && backpack_auth()->user()->hasRole('Administrator'))
	<a href="javascript:void(0)" onclick="deleteEntry(this)" data-route="{{ url($crud->route.'/'.$entry->getKey()) }}" class="btn btn-xs btn-info action-btn" data-button-type="delete"><i class="fa fa-trash"></i> {{-- Delete --}}</a>

	<script>
		if (typeof deleteEntry != 'function') {
		  $("[data-button-type=delete]").unbind('click');

		  function deleteEntry(button) {

			// e.preventDefault();
			var button = $(button);
			var route = button.attr('data-route');
			var row = $("#crudTable a[data-route='"+route+"']").closest('tr');

		  	$.confirm({
			    title: 'Warning!',
			    content: '' +
			    '<form action="" class="formName">' +
				    '<div class="form-group">' +
					    '<label>You need to enter your password to allow this</label>' +
					    '<input type="password" name="password" placeholder="password" class="password form-control" required />' +
				    '</div>' +
			    '</form>',
			    buttons: {
			        formSubmit: {
			            text: 'Delete',
			            btnClass: 'btn-blue',
			            action: function () {
			                var password = this.$content.find('.password').val();
			                if(!password){
			                    $.alert('Enter Your Password');
			                    return false;
			                }

			                $.ajax({
								url: route,
								type: 'DELETE',
								data: {
									password: password
								},
								success: function(result) {

									if (result.error) {
										// Show an error alert
										new PNotify({
											title: "{{ trans('backpack::crud.delete_confirmation_not_title') }}",
											text: result.message,
											type: "warning"
										});
									} else {
										// Show a success alert with the result
										new PNotify({
											title: "{{ trans('backpack::crud.delete_confirmation_title') }}",
											text: result.message,
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
									}
									
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
			            }
			        },
			        cancel: function () {
			            new PNotify({
							title: "{{ trans('backpack::crud.delete_confirmation_not_deleted_title') }}",
							text: "{{ trans('backpack::crud.delete_confirmation_not_deleted_message') }}",
							type: "info"
						});
			        },
			    },
			    onContentReady: function () {
			        // bind to events
			        var jc = this;
			        this.$content.find('form').on('submit', function (e) {
			        //     // if the user submits the form by pressing enter in the field.
			            e.preventDefault();
			        //     jc.$$formSubmit.trigger('click'); // reference the button and click it
			        });
			    }
			});

		      // ask for confirmation before deleting an item
		      // e.preventDefault();
		      // var button = $(button);
		      // var route = button.attr('data-route');
		      // var row = $("#crudTable a[data-route='"+route+"']").closest('tr');

		      // if (confirm("{{ trans('backpack::crud.delete_confirm') }}") == true) {
				
		      // } else {
		      // 	  // Show an alert telling the user we don't know what went wrong
		      //     new PNotify({
		      //         title: "{{ trans('backpack::crud.delete_confirmation_not_deleted_title') }}",
		      //         text: "{{ trans('backpack::crud.delete_confirmation_not_deleted_message') }}",
		      //         type: "info"
		      //     });
		      // }
	      }
		}

		// make it so that the function above is run after each DataTable draw event
		// crud.addFunctionToDataTablesDrawEventQueue('deleteEntry');
	</script>
	
@endif

