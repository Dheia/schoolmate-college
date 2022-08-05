@extends('backpack::layout')

@section('header')
@endsection

@section('content')
	<!-- HEADER -->
    <div class="row" style="padding: 15px;">
      <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 smo-search-group"> 
        <section class="content-header">
          <ol class="breadcrumb">
            <li><a href="{{ url(config('backpack.base.route_prefix'), 'dashboard') }}">{{ trans('backpack::crud.admin') }}</a></li>
		    <li><a href="{{ url('admin/teacher-online-class') }}" class="text-capitalize">Online Class</a></li>
		    <li><a href="{{ url('admin/teacher-online-post?class_code=' . $class->code) }}" class="text-capitalize">{{$class->name}}</a></li>
		    <li><a href="{{ url('/admin/online-class/assignment?class_code=' . $class->code) }}" class="text-capitalize">Assignment</a></li>
		    <li class="active">{{ trans('backpack::crud.add') }}</li>
          </ol>
        </section>
        <h1 class="smo-content-title">
          	<span class="text-capitalize">{{ $class->name }}</span>
	        <small>{!! $crud->getSubheading() ?? trans('backpack::crud.add').' '.$crud->entity_name !!}.</small>
        </h1>
      </div>
    </div>
  	<!-- END OF HEADER -->
  	
	@if ($crud->hasAccess('list'))
		<a href="{{ url($crud->route . '?class_code=' . $class->code) }}" class="hidden-print"><i class="fa fa-angle-double-left"></i> {{ trans('backpack::crud.back_to_all') }} <span>{{ $crud->entity_name_plural }}</span></a>
	@endif

	<div class="row m-t-20">
		<div class="{{ $crud->getCreateContentClass() }}">
			<!-- Default box -->

			@include('crud::inc.grouped_errors')

			<form method="post"
		  		action="{{ url(request()->class_code ? $crud->route . '?class_code=' . request()->class_code :  $crud->route) }}"
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
				    <div class="row">

		                {{-- @include('crud::inc.form_save_buttons') --}}
		                <div id="saveActions" class="form-group">

						    <input type="hidden" name="save_action" value="{{ $saveAction['active']['value'] }}">

						    <div class="btn-group">

						        <button type="submit" class="btn btn-success">
						            <span class="fa fa-save" role="presentation" aria-hidden="true"></span> &nbsp;
						            <span data-value="{{ $saveAction['active']['value'] }}">{{ $saveAction['active']['label'] }}</span>
						        </button>

						        <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aira-expanded="false">
						            <span class="caret"></span>
						            <span class="sr-only">&#x25BC;</span>
						        </button>

						        <ul class="dropdown-menu">
						            @foreach( $saveAction['options'] as $value => $label)
						            <li><a href="javascript:void(0);" data-value="{{ $value }}">{{ $label }}</a></li>
						            @endforeach
						        </ul>

						    </div>

						    <a href="{{ $crud->hasAccess('list') ? url($crud->route . '?class_code=' . $class->code) : url()->previous() . '?class_code=' . $class->code }}" class="btn btn-default"><span class="fa fa-ban"></span> &nbsp;{{ trans('backpack::crud.cancel') }}</a>
						</div>


				    </div><!-- /.box-footer-->

			  	</div><!-- /.box -->
			</form>
		</div>
	</div>

@endsection
