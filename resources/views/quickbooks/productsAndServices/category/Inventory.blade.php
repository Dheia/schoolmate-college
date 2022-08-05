@extends('backpack::layout')

@section('header')
	<section class="content-header">
        {{-- <span class="text-capitalize">{{ $crud->entity_name_plural }}</span> --}}
        {{-- <small>{{ trans('backpack::crud.add').' '.$crud->entity_name }}.</small> --}}
        <table class="table m-b-0" style="width: auto;">
	        <tbody>
		        <tr>
		        	<td style="vertical-align: middle;">
				        <div class="circle" style="background: #0077c5;
				                                   background-position: 14px;
				                                   display: inline-block;
				                                   width: 84px;
				                                   height: 84px;
				                                   border-radius: 50%;
				                                   position: relative;
				                                   transition: all .08s ease-in;
				                                   left: 50%;
				                                   transform: translate(-50%);">
				      
				        	<img style="color: red; display: block; margin: auto; margin-top: 15px;" class="img-fluid" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADUAAAA1CAYAAADh5qNwAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAnhJREFUeNrsWYFxgzAMBI4B2KBsULIBmaDJBE0maDJByQSkE6QbkE5AOgHZgG5ANkilO/uquBiMMYXmrDsdCRjZL0sv2ziOFStWJiXX63UBegCtQFNyH/93lYrZWowBJCZAbgbVE1QdwHhoMAFoJnRe4gyxK8qqh/1IsMUF+wyGAlSQjtCLEXmeEJCBgf4i1geXwjgwMJiTsIgkbQoygFDRUWi3aAl1HsqZSUBpGyDi3Yq0TevakzCrxFxssMtlY4rduCwU2odkVsXcK2vuF02OInZX5J24D6CQeDPVYMhMwoJa1E1yrNLKL4EYCkOJHxkkq1zHQD+vDFdWIq3oMRa/w65ilPNc9EQy4eUZZeSwLWbLpprAyCPrs3LoGDHS1YRQFwOZkaxtVcBYTS9R9Qt+3DAJPKoO/L5HvQIXHp9L13UvU98hsDEu2d8Vzy+PtHlm1x00Pv+XrQ+M9QSXPcXgk+d8Zh502AguLx1f+wL9gEEdDWALBQy1NJnLGKUup2q2I10k6ZFTkbBziG9mCj0GN9fwEwsaPsTGa0VPbkHfOnr3CXTDZjjRiA589xU0YDO0ZqEopWxx3xQMwX68gy4zVbNRzVW2OeKmj1N8NDYoYWEgDV2vgVXwhTlL6JDQ/ZjCww3HNGNj/CV+G12CN2ZYA0CPEwC1A/0EfW+qo75igdtPpCZh/Wytod49nkXeJSjfhBFGqV1X7Y+1q4CpgAI5sIKtI9upgtpqUv5JugoYG5QqK1misKAsKAvKgrKgLCgLyoKyoKYEKlQ+mtLbo+HpldaXR1ezw9L5OeodWs6wC5j9xUzhl4ahT5curI+5Y8WKlVHlW4ABABOTdFCPc9wyAAAAAElFTkSuQmCC" alt="inventory">
				        </div>
		        	</td>
		        	<td style="vertical-align: middle;">
						<h1 class="m-b-0 m-t-0" style="display: inline-block;">{{ $category_selected }}</h1>
						<p>Products you buy and/or sell and that you track quantities of.</p>
		        	</td>
		        </tr>
	        </tbody>
        </table>
      	
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
		{{-- Show the errors, if any --}}
		@if ($errors->any())
		    <div class="callout callout-danger">
		        <h4>{{ trans('backpack::crud.please_fix') }}</h4>
		        <ul>
		            @foreach($errors->all() as $error)
		                <li>{{ $error }}</li>
		            @endforeach
		        </ul>
		    </div>
		@endif

		  <form method="post" action="{{ url('admin/quickbooks/products-and-services') }}">
				{{-- @if ($crud->hasUploadFields('create')) --}}
				{{-- enctype="multipart/form-data" --}}
				{{-- @endif --}}
		  		{{-- > --}}
			  {!! csrf_field() !!}
			  <div class="box col-md-12 padding-10">

			    {{-- <div class="box-header with-border"> --}}
			      {{-- <h3 class="box-title">{{ trans('backpack::crud.add_a_new') }} {{ $crud->entity_name }}</h3> --}}
			    {{-- </div> --}}
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
					<input type="hidden" name="Type" value="{{ $category_selected }}" class="form-control">
				</div>

				<div class="form-group col-xs-6 required">
					<label for="Name">Name*</label>
					<input type="text" name="Name" class="form-control" required>
				</div>

				<div class="form-group col-xs-6">
					<label for="Sku">Sku</label>
					<input type="text" name="Sku" class="form-control">
				</div>

				<div class="form-group col-xs-12">
					<hr class="m-b-0 m-t-0">
				</div>

				<div class="form-group col-xs-6 required">
					<label for="QtyOnHand">Initial Quantity On Hand*</label>
					<input type="text" name="QtyOnHand" class="form-control" required>
				</div>

				<div class="form-group col-xs-6 required">
					<label for="InvStartDate">As Of Date*</label>
					<input type="date" placeholder="MM/DD/YYYY" name="InvStartDate" class="form-control" required>
				</div>
			
				<div class="form-group col-xs-12">
					<hr class="m-b-0 m-t-0">
				</div>

				<div class="form-group col-xs-12 required">
					<label for="InvStartDate">Inventory Asset Account*</label>
					<select name="AssetAccountRef" id="" class="form-control" required>
						@foreach($accounts->assets as $asset)
							<?php
								$value = ['name' => $asset->Name, 'value' => $asset->Id];
							?>
							<option value="{{ json_encode($value) }}">{{ $asset->Name }}</option>
						@endforeach
					</select>
				</div>
				
				<div class="form-group col-xs-12">
					<hr class="m-b-0 m-t-0">
				</div>
				
				<div class="form-group col-xs-6">
					<label for="UnitPrice">Sales Price/Rate</label>
					<input type="text" name="UnitPrice" id="UnitPrice" class="form-control">
				</div>
				
				<div class="form-group col-xs-6 required">
					<label for="InvStartDate">Income Account*</label>
					<select name="IncomeAccountRef" id="" class="form-control" required>
						@foreach($accounts->revenues as $income)
							<?php
								$value = ['name' => $income->Name, 'value' => $income->Id];
							?>
							<option value="{{ json_encode($value) }}">{{ $income->Name }}</option>
						@endforeach
					</select>
				</div>	
			
				<div class="form-group col-xs-12">
					<hr class="m-b-0 m-t-0">
				</div>

				<div class="form-group col-xs-12 required">
					<label for="InvStartDate">Expense Account*</label>
					<select name="ExpenseAccountRef" id="" class="form-control" required>
						@foreach($accounts->expenses as $expense)
							<?php
								$value = ['name' => $expense->Name, 'value' => $expense->Id];
							?>
							<option value="{{ json_encode($value) }}">{{ $expense->Name }}</option>
						@endforeach
					</select>
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
