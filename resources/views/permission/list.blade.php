@extends('backpack::layout')


@section('header')
	<section class="content-header">
	  <h1>
        <span class="text-capitalize">{{ $crud->entity_name_plural }}</span>
        {{-- <small>{{ trans('backpack::crud.add').' '.$crud->entity_name }}.</small> --}}
	  </h1>
	  <ol class="breadcrumb">
	    <li><a href="{{ url(config('backpack.base.route_prefix'), 'dashboard') }}">{{ trans('backpack::crud.admin') }}</a></li>
	    <li><a href="{{ url($crud->route) }}" class="text-capitalize">{{ $crud->entity_name_plural }}</a></li>
	    {{-- <li class="active">{{ trans('backpack::crud.add') }}</li> --}}
	  </ol>
	</section>
@endsection

@section('content')
<div class="row">
	<div class="col-md-12">
		<!-- Default box -->
		@if ($crud->hasAccess('list'))
			<a href="{{ url($crud->route) }}"><i class="fa fa-angle-double-left"></i> {{ trans('backpack::crud.back_to_all') }} <span>{{ $crud->entity_name_plural }}</span></a><br><br>
		@endif

		@include('crud::inc.grouped_errors')

		  <form method="post"
		  		action="{{ url($crud->route) }}"
				@if ($crud->hasUploadFields('create'))
				enctype="multipart/form-data"
				@endif
		  		>
		  {!! csrf_field() !!}
		  <div class="box">

		    <div class="box-header with-border">
		      {{-- <h3 class="box-title">{{ trans('backpack::crud.add_a_new') }} {{ $crud->entity_name }}</h3> --}}
		    </div>
		    <div class="box-body display-flex-wrap" style="display: flex; flex-wrap: wrap;">
		     	
		     	<div class="sidebar-content">
					<table class="table" style="width: 100%;">
						<thead>
							<th>Page Name</th>
							<th>List</th>
							<th>Create</th>
							<th>Update</th>
							<th>Delete</th>
							<th>Additional Actions</th>
						</thead>
						<tbody></tbody>
					</table>
		     	</div>


		    </div><!-- /.box-body -->
		    <div class="box-footer">
				

		    </div><!-- /.box-footer-->

		  </div><!-- /.box -->
		  </form>
	</div>
</div>

@endsection

@push('after_styles')
	{{-- <link rel="stylesheet" href="//cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css"> --}}
	<style>
		td {
			vertical-align: middle;
		}
		.sidebar-content {
			width: 100%;
		}
		.actions ul {
			display: -webkit-flex;
			display: flex;
/*			margin:0; 
			padding:0; */
			list-style:none;
	    }

	    .actions ul li {
		    background: #ccc;
		    padding: 5px;
		    text-align:center;
		    flex: 1;
		    border:1px solid #fff;
	    }

	    table .checkbox {
	    	margin: 0 !important;
	    }
	</style>
@endpush


@push('after_scripts')
	<script src="{{ asset('js/jquery.min.js') }}"></script>
	<script src="{{ asset('js/bootstrap4.min.js') }}"></script>
	<script>
		$(document).ready(function () {
			$('.sidebar-content .treeview').addClass('active');
			$('.sidebar-content li a').click(function (e) {
				e.preventDefault();
				var _this = $(this);
			})

			var sideBarli = $('.sidebar-menu li a');
			var additional_actions  = {!! json_encode(config('additional_actions')) !!}
					console.log(additional_actions);

			sideBarli.each(function () {
				var tr = '';
				var _this = $(this);
				if($(this).attr('href') === '#') {
					tr = '<tr style="background: #f4f4f5;">\
							<td>\
								<a target="_blank" href="' + $(this).attr('href') + '"><b>' + $(this).text() + '</b></a>\
							</td>\
							<td colspan="5"></td>\
						</tr>'
				}
				else {
					var additionals = '';
					$.each(additional_actions, function (key, val) {
							console.log(_this.attr('href')  + ", " +  "{{ backpack_url() }}/" + val.slug);
						if(_this.attr('href')  ===  "{{ backpack_url() }}/" + val.slug) {
							$.each(val.actions, function (aKey, aVal) {
								additionals += '<div class="checkbox">\
													<label>\
														<input type="checkbox" name="' + aVal.label + '[\'list\']">&nbsp;\
														' + aVal.label  + '\
													</label>\
												</div>';
							});
						}
					});

					tr = '<tr>\
							<td><a target="_blank" href="' + $(this).attr('href') + '">' + $(this).text() + '</a></td>\
							<td>\
								<div class="checkbox"><label><input type="checkbox" name="' + $(this).text() + '[\'list\']"><span class="checkmark"></span></label></div>\
							</td>\
							<td>\
								<div class="checkbox"><label><input type="checkbox" name="' + $(this).text() + '[\'create\']"><span class="checkmark"></span></label></div>\
							</td>\
							<td>\
								<div class="checkbox"><label><input type="checkbox" name="' + $(this).text() + '[\'update\']"><span class="checkmark"></span></label></div>\
							</td>\
							<td>\
								<div class="checkbox"><label><input type="checkbox" name="' + $(this).text() + '[\'delete\']"><span class="checkmark"></span></label></div>\
							</td>\
							<td>' + additionals + '</td>\
						</tr>'
				}
				$('table tbody').append(tr);
			});
		});

		$('.custom-checkbox input').change(function () {
			console.log($(this));
		})
	</script>
	{{-- <script src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script> --}}
@endpush
