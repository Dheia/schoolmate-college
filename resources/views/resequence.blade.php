@extends('backpack::layout')

@section('header')
    <section class="content-header buttons-header">
      <h1>Resequence</h1>
    </section>
@endsection


@section('content')
	{{-- <section class="row"> --}}

		<!-- Default box -->	

		<div class="col-md-12" >
			<div class="box">

				<div class="box-header with-border">
					<a id="save" href="#" class="btn btn-primary">Save</a>
					<a id="cancel" href="{{ url($crud->route) }}" class="btn btn-default">Back</a>
				</div>

				<div class="box-body">
					<ul id="listItems" style="padding: 0;">
						@foreach($items as $item)
							<li class="list-group-item" data-id="{{ $item->id }}" data-sequence="{{ $item->sequence }}">
								<span class="glyphicon glyphicon-move my-handle" aria-hidden="true"></span>
								&nbsp;&nbsp;{{ $item->{ $attribute } }}
							</li>
						@endforeach
					</ul>
				</div>

				<form id="form" action="{{ url($crud->route . '/resequence/save') }}" method="POST">
					@csrf()
					<input type="hidden" name="sequence" value="">
				</form>

			</div>

		</div>

	{{-- </section> --}}
@endsection


@section('after_styles')
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
@endsection

@section('after_scripts')
	<script src="https://raw.githack.com/SortableJS/Sortable/master/Sortable.js"></script>
	<script>
		var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
		var sortList = Sortable.create(listItems, {
			animation: 150,
		});

		$('#save').click(function () {
			var order = sortList.toArray();
			$('input[name="sequence"]').val("[" + order + "]");
			$('#form').submit();
			// $.ajax({
			// 	// type: 'PUT',
			// 	type: 'POST',
			// 	url: location.protocol + '//' + location.host + '/admin/year-management/resequence/save',
			// 	data: { _token: CSRF_TOKEN, sequence: order },
			// 	// contentType: "application/json",
			// });
		})
	</script>
@endsection