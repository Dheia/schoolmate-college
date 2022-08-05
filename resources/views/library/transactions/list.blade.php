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
				    <li><a href="{{ url($crud->route) }}" class="text-capitalize">Library</a></li>
				    <li>{{ $crud->entity_name_plural }}</li>
				</ol>
	      	</section>
		    <h1 class="smo-content-title">
		        <span class="text-capitalize">{!! $crud->getHeading() ?? $crud->entity_name_plural !!}</span>
		    </h1>
	    </div>
  	</div>
  	<!-- END OF HEADER -->

<div class="row">
	<div class="col-md-12">
		<!-- Default box -->
		@if ($crud->hasAccess('list'))
			{{-- <a href="{{ url($crud->route) }}"><i class="fa fa-angle-double-left"></i> {{ trans('backpack::crud.back_to_all') }} <span>{{ $crud->entity_name_plural }}</span></a><br><br> --}}
		@endif

		@include('crud::inc.grouped_errors')

		  <form method="post"
		  		action="{{ url($crud->route) }}"
				@if ($crud->hasUploadFields('create'))
				enctype="multipart/form-data"
				@endif
		  		>
		  {!! csrf_field() !!}
		  {{-- <div class="box" style="padding: 0; margin: 0;"> --}}

{{-- 		    <div class="box-header with-border">
		      <h3 class="box-title">{{ trans('backpack::crud.add_a_new') }} {{ $crud->entity_name }}</h3>
		    </div> --}}
		    <div class="box-body display-flex-wrap" style="display: flex; flex-wrap: wrap; padding: 0;">
		      <!-- load the view from the application if it exists, otherwise load the one in the package -->
		      @if(view()->exists('vendor.backpack.crud.form_content'))
		      	@include('vendor.backpack.crud.form_content', [ 'fields' => $crud->getFields('create'), 'action' => 'create' ])
		      @else
		      	@include('crud::form_content', [ 'fields' => $crud->getFields('create'), 'action' => 'create' ])
		      @endif
		    </div><!-- /.box-body -->
		    {{-- <div class="box-footer"> --}}

                {{-- @include('crud::inc.form_save_buttons') --}}

		    {{-- </div> /.box-footer --}}

		  {{-- </div> --}}
		  {{-- /.box --}}
		  </form>
	</div>
</div>

@endsection
