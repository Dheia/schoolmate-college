@extends('backpack::layout')

@section('header')
	<section class="content-header">
	  <h1>
        <span class="text-capitalize">Payment Method</span>
        <small>{{ trans('backpack::crud.edit').' Payment Method' }}</small>
	  </h1>
	  <ol class="breadcrumb">
	    <li><a href="{{ url(config('backpack.base.route_prefix'),'admin/quickbooks/payment-method') }}">Payment Method</a></li>
	    <li class="active">{{ trans('backpack::crud.edit') }}</li>
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

		  <form method="post" action="{{ action('QuickBooks\PaymentMethodController@update', $paymentMethod->Id) }}">
		  {!! csrf_field() !!}
		  {!! method_field('PUT') !!}
		  <div class="box">
		    <div class="box-header with-border">

		    </div>
		    <div class="box-body row display-flex-wrap" style="display: flex;flex-wrap: wrap;">
		    
				<div class="form-group col-xs-12 required">
					<label for="Name">Name</label>
					<input type="text" name="Name" value="{{ $paymentMethod->Name }}" id="Name" class="form-control">
					<div class="checkbox">
						<label for="IsCredit">
							@if($paymentMethod->Type === "CREDIT_CARD")
								<input type="checkbox" checked name="IsCredit" id="IsCredit"> 
							@else
								<input type="checkbox" checked name="IsCredit" id="IsCredit"> 
							@endif
								This Is A Credit Card
						</label>
					</div>
				</div>

		    </div><!-- /.box-body -->

            <div class="box-footer">
				
				<div id="saveActions" class="form-group">

						<button type="submit" class="btn btn-success">
							<span class="fa fa-save" role="presentation" aria-hidden="true"></span> &nbsp;Save
						</button>
					{{-- </div> --}}

					<a href="/admin/quickbooks/payment-method" class="btn btn-default"><span class="fa fa-ban"></span> &nbsp;Cancel</a>
				</div>
	
		    </div><!-- /.box-footer-->
		  </div><!-- /.box -->
		  </form>
	</div>
</div>
@endsection
