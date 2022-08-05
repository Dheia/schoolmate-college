@extends('backpack::layout')

@section('header')
	<section class="content-header">
	  <h1>
        <span class="text-capitalize">Dropping / Transferring</span>
        {{-- <small>{{ trans('backpack::crud.add').' '.$crud->entity_name }}.</small> --}}
	  </h1>
	  <ol class="breadcrumb">
	    <li><a href="{{ url(config('backpack.base.route_prefix'), 'dashboard') }}">{{ trans('backpack::crud.admin') }}</a></li>
	    <li><a href="{{ url($crud->route) }}" class="text-capitalize">{{ $crud->entity_name_plural }}</a></li>
	    <li class="active">Dropping / Transferring</li>
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
		  		action="{{ url()->current() }}"
				@if ($crud->hasUploadFields('create'))
				enctype="multipart/form-data"
				@endif
		  		>
		  {!! csrf_field() !!}
		  <div class="box">

		  	<div class="box-header">
		  		
			  	<div class="col-xs-12">
			  		<div>
			  			<p class="m-b-0">Full Name:</p>
			  			<h1 style="margin: 0;">{{ $entry->student->full_name }}</h1>
			  		</div>
			  	</div>

			  	
		  	</div>
		   {{--  <div class="box-header with-border">
		      <h3 class="box-title">{{ trans('backpack::crud.add_a_new') }} {{ $crud->entity_name }}</h3>
		    </div> --}}
		    {{-- <div class="box-body row display-flex-wrap" style="display: flex; flex-wrap: wrap;"> --}}
		      <!-- load the view from the application if it exists, otherwise load the one in the package -->

		      @if(view()->exists('vendor.backpack.crud.form_content'))
		      	@include('vendor.backpack.crud.form_content', [ 'fields' => $crud->getFields('create'), 'action' => 'create' ])
		      @else
		      	@include('crud::form_content', [ 'fields' => $crud->getFields('create'), 'action' => 'create' ])
		      @endif

		    {{-- </div> --}}
		    <!-- /.box-body -->
		    <div class="box-footer">
				{{-- <input type="hidden" name="save_action" value="{{ $saveAction['active']['value'] }}"> --}}

			    <div class="btn-group">

			        <button type="submit" class="btn btn-success">
			            <span class="fa fa-save" role="presentation" aria-hidden="true"></span> &nbsp;
			            <span data-value="SaveAndClose">Save and close</span>
			        </button>

			        <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aira-expanded="false">
			            <span class="caret"></span>
			            <span class="sr-only">&#x25BC;</span>
			        </button>

			        <ul class="dropdown-menu">
			            <li><a href="javascript:void(0);" data-value="SaveAndSend">Save and send</a></li>
			        </ul>

			    </div>
                {{-- @include('crud::inc.form_save_buttons') --}}
				<a href="{{ $crud->hasAccess('list') ? url($crud->route) : url()->previous() }}" class="btn btn-default"><span class="fa fa-ban"></span> &nbsp;{{ trans('backpack::crud.cancel') }}</a>
		    </div><!-- /.box-footer-->

		  </div><!-- /.box -->
		  </form>
	</div>
</div>

@endsection
