@extends('backpack::layout')

@section('header')
	{{-- <section class="content-header">
	  <h1>
      <span class="text-capitalize">{!! $crud->getHeading() ?? $crud->entity_name_plural !!}</span>
      <small id="datatable_info_stack">{!! $crud->getSubheading() ?? trans('backpack::crud.all').'<span>'.$crud->entity_name_plural.'</span> '.trans('backpack::crud.in_the_database') !!}.</small>
	  </h1>
	  <ol class="breadcrumb">
	    <li><a href="{{ url(config('backpack.base.route_prefix'), 'dashboard') }}">{{ trans('backpack::crud.admin') }}</a></li>
	    <li><a href="{{ url($crud->route) }}" class="text-capitalize">{{ $crud->entity_name_plural }}</a></li>
	    <li class="active">{{ trans('backpack::crud.list') }}</li>
	  </ol>
	</section> --}}
@endsection

@section('content')
	<!-- HEADER -->
  	<div class="row" style="padding: 15px;">
	    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 smo-search-group"> 
	      	<section class="content-header">
		        <ol class="breadcrumb">
		          	<li>
		            	<a href="{{ url(config('backpack.base.route_prefix'), 'dashboard') }}">{{ trans('backpack::crud.admin') }}</a>
		          	</li>
		          	<li class="active">{{ $crud->entity_name_plural }}</li>
		        </ol>
	      	</section>
	      	<h1 class="smo-content-title">
	        	<span class="text-capitalize">{!! $crud->getHeading() ?? $crud->entity_name_plural !!}</span>
	      	</h1>
	      	<div class="col-xs-6">
	          	<div id="datatable_search_stack" class="pull-left"></div>
	      	</div>
	    </div>
  	</div>
  <!-- END OF HEADER -->

<!-- Default box -->
  <div class="row">

    <!-- THE ACTUAL CONTENT -->
    <div class="{{ $crud->getListContentClass() }}">
      <div class="">

        

        <div class="overflow-hidden">
			@include('crud::inc.grouped_errors')
			<form method="post"
					target="_blank"
			  		action="{{ url($crud->route) }}/generate-report"
					@if ($crud->hasUploadFields('create'))
					enctype="multipart/form-data"
					@endif
			  		>
				  {!! csrf_field() !!}
				  <div class="col-md-12">

				    <div class="row display-flex-wrap">
						<!-- load the view from the application if it exists, otherwise load the one in the package -->
						{{-- @if(view()->exists('vendor.backpack.crud.form_content')) --}}
							{{-- @include('vendor.backpack.crud.form_content', [ 'fields' => $crud->getFields('create'), 'action' => 'create' ]) --}}
						{{-- @else --}}
							{{-- @include('crud::form_content', [ 'fields' => $crud->getFields('create'), 'action' => 'create' ]) --}}
						{{-- @endif --}}

						<input type="hidden" name="http_referrer" value={{ old('http_referrer') ?? \URL::previous() ?? url($crud->route) }}>

						@if ($crud->model->translationEnabled())
						<input type="hidden" name="locale" value={{ $crud->request->input('locale')?$crud->request->input('locale'):App::getLocale() }}>
						@endif

						{{-- See if we're using tabs --}}
						@if ($crud->tabsEnabled() && count($crud->getTabs()))
						    @include('crud::inc.show_tabbed_fields')
						    <input type="hidden" name="current_tab" value="{{ str_slug($crud->getTabs()[0], "") }}" />
						@else
						    <div class="box col-md-12 padding-10 p-t-20">
						    @include('crud::inc.show_fields', ['fields' => $crud->getFields('create')])
						    </div>
						@endif

						<button class="btn btn-primary"><i class="fa fa-list"></i> Generate Report</button>
				  	</div><!-- /.box -->
			</form>

        </div><!-- /.box-body -->

      </div><!-- /.box -->
    </div>

  </div>

@endsection

@section('after_styles')

@endsection
