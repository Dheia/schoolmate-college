@extends('backpack::layout')

@section('header')
	<section class="content-header">
	  <h1>
        {{-- <span class="text-capitalize">{{ $crud->entity_name_plural }}</span> --}}
        {{-- <small>{{ trans('backpack::crud.add').' '.$crud->entity_name }}.</small> --}}
	  </h1>
	  <ol class="breadcrumb">
	    {{-- <li><a href="{{ url(config('backpack.base.route_prefix'), 'dashboard') }}">{{ trans('backpack::crud.admin') }}</a></li> --}}
	    {{-- <li><a href="{{ url($crud->route) }}" class="text-capitalize">{{ $crud->entity_name_plural }}</a></li> --}}
	    {{-- <li class="active">{{ trans('backpack::crud.add') }}</li> --}}
	  </ol>
	</section>
@endsection

@section('content')
<div class="row">
	<div class="col-md-12">
		<!-- Default box -->
		{{-- @if ($crud->hasAccess('list')) --}}
			{{-- <a href="{{ url($crud->route) }}"><i class="fa fa-angle-double-left"></i> {{ trans('backpack::crud.back_to_all') }} <span>{{ $crud->entity_name_plural }}</span></a><br><br> --}}
		{{-- @endif --}}

		{{-- @include('crud::inc.grouped_errors') --}}

		  <form method="post"
		  		action="{{ url('admin/quickbooks/payment') }}"
				{{-- @if ($crud->hasUploadFields('create')) --}}
				{{-- enctype="multipart/form-data" --}}
				{{-- @endif --}}
		  		>
		  {!! csrf_field() !!}
		  <div class="box col-md-12 padding-10 p-t-20">

		    <div class="box-header with-border">
		      {{-- <h3 class="box-title">{{ trans('backpack::crud.add_a_new') }} {{ $crud->entity_name }}</h3> --}}
		    </div>
		    {{-- <div class="box-body row display-flex-wrap" style="display: flex; flex-wrap: wrap;"> --}}
		      <!-- load the view from the application if it exists, otherwise load the one in the package -->
		  {{--     @if(view()->exists('vendor.backpack.crud.form_content'))
		      	@include('vendor.backpack.crud.form_content', [ 'fields' => $crud->getFields('create'), 'action' => 'create' ])
		      @else
		      	@include('crud::form_content', [ 'fields' => $crud->getFields('create'), 'action' => 'create' ])
		      @endif --}}
		    {{-- </div> --}}
		    <!-- /.box-body -->
			
			<div class="form-group col-xs-12 required">
				<label for="CustomerRef">Customer Ref</label>
				{{-- <input type="text" name="CustomerRef['value']" class="form-control" required> --}}
				<select class="form-control" name="CustomerRef_value" id="">
					@foreach($customers as $customer)
						<option value="{{ $customer->Id }}">{{ $customer->DisplayName }}</option>
					@endforeach
				</select>
			</div>
			<div class="form-group col-xs-12 required">
				<label for="TotalAmt">Total Amount</label>
				<input type="text" name="TotalAmt" class="form-control" required>
			</div>
			

		    <div class="box-footer">
				<button class="btn btn-success">Save Payment</button>
				<a href="{{ url()->previous() }}" class="btn btn-default"><span class="fa fa-ban"></span> &nbsp;{{ trans('backpack::crud.cancel') }}</a>
                {{-- @include('crud::inc.form_save_buttons') --}}

		    </div><!-- /.box-footer-->

		  </div><!-- /.box -->
		  </form>
	</div>
</div>

@endsection
