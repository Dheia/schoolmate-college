@extends('backpack::layout')

@section('header')
	<section class="content-header">
	  <h1>
        <span class="text-capitalize">{!! $crud->getHeading() ?? $crud->entity_name_plural !!}</span>
        <small>{!! $crud->getSubheading() ?? trans('backpack::crud.add').' '.$crud->entity_name !!}.</small>
	  </h1>
	  <ol class="breadcrumb">
	    <li><a href="{{ url(config('backpack.base.route_prefix'), 'dashboard') }}">{{ trans('backpack::crud.admin') }}</a></li>
	    <li><a href="{{ url($crud->route) }}?teacher_id={{ Request::get('teacher_id') }}" class="text-capitalize">{{ $crud->entity_name_plural }}</a></li>
	    <li class="active">{{ trans('backpack::crud.add') }}</li>
	  </ol>
	</section>
@endsection

@section('content')
@if ($crud->hasAccess('list'))
	<a href="{{ url($crud->route) }}?teacher_id={{ Request::get('teacher_id') }}" class="hidden-print"><i class="fa fa-angle-double-left"></i> {{ trans('backpack::crud.back_to_all') }} <span>{{ $crud->entity_name_plural }}</span></a>
@endif

<div class="row m-t-20">
	<div class="{{ $crud->getCreateContentClass() }}">
		<!-- Default box -->

		@include('crud::inc.grouped_errors')

		<form method="post"
  		action="{{ url($crud->route) }}?teacher_id={{ Request::get('teacher_id') }}"
		@if ($crud->hasUploadFields('create'))
		enctype="multipart/form-data"
		@endif
  		>
		    {!! csrf_field() !!}
		    <div class="col-md-12">

			    <div class="row display-flex-wrap">
			        <!-- load the view from the application if it exists, otherwise load the one in the package -->
			        @if(view()->exists('vendor.backpack.crud.form_content'))
			      		@include('vendor.backpack.crud.form_content', [ 'fields' => $crud->getFields('create'), 'action' => 'create' ])
			        @else
			      		@include('crud::form_content', [ 'fields' => $crud->getFields('create'), 'action' => 'create' ])
			        @endif
			    </div><!-- /.box-body -->
			    <div class="box-footer" style="margin-left: 10px;">
	                <button id="btnPost" style="margin-right: 10px;" type="submit" class="btn btn-success" >
	                	<span class="fa fa-save" role="presentation" aria-hidden="true"></span> Create
	              	</button>
	              	<a href="{{ url($crud->route) }}?teacher_id={{ Request::get('teacher_id') }}" style="margin-right: 10px;" type="button" class="btn btn-secondary" data-dismiss="modal">
	                	<span class="fa fa-ban"></span> Cancel
	              	</a>
			    </div><!-- /.box-footer-->

		    </div><!-- /.box -->
		</form>
	</div>
</div>

@endsection
