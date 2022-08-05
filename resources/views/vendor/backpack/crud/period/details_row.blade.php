<div class="col-md-12" >
	{{-- <div class="box"> --}}

		<div class="p-t-20 p-l-10 p-r-10 p-b-10">
			<ul id="listItems{{ $uniqid }}" style="padding: 0;">
				@foreach($items as $item)
					<li class="list-group-item" data-id="{{ $item->id }}" data-sequence="{{ $item->sequence }}">
						<span class="glyphicon glyphicon-move my-handle" aria-hidden="true"></span>
						&nbsp;&nbsp;{{ $item->name }}
						<span style="float: right;">
							<a href="/{{ $crud->route . '/' . $item->id }}/edit" class="btn btn-xs btn-default" id="edit" data-id="{{ $item->id }}"><i class="fa fa-edit" title="Edit"></i></a> &nbsp;
							<a href="#" onclick="deleteEntry(this)" data-route="{{ url($crud->route.'/'.$item->id) }}" class="btn btn-xs btn-default" id="delete" data-id="{{ $item->id }}"><i class="fa fa-trash" title="Delete"></i></a>
						</span>
					</li>
				@endforeach
			</ul>
		</div>

	{{-- </div> --}}

</div>

<style>
	
	#listItems {
		padding: 0;
	}

	#listItems li {
		cursor: pointer;
		-webkit-touch-callout: none; /* iOS Safari */
			    -webkit-user-select: none; /* Safari */
			     -khtml-user-select: none; /* Konqueror HTML */
			       -moz-user-select: none; /* Firefox */
			        -ms-user-select: none; /* Internet Explorer/Edge */
			            user-select: none; /* Non-prefixed version, currently
			                                  supported by Chrome and Opera */
	}
</style>

<script src="{{ asset('js/sortable.js') }}"></script>
<script>
	var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
	var sortList = Sortable.create(listItems{{ $uniqid }}, {
		animation: 150,
		onSort: function (e) {
			$.ajax({
				method: 'post',
				url: '/{{ $crud->route }}/sequence/update',
				data: {
					department_id: {{ $department_id ?? '' }},
					sequence: sortList.toArray()
				},
				success: function (response) {
					new PNotify({
					  text: 'Succesfully Updated Order',
					});
				}
			});
		}
	});
</script>

<script>
	function deleteEntry(button) {
		// ask for confirmation before deleting an item
		// button.preventDefault();
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
