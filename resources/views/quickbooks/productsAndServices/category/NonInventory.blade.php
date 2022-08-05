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
				      
				        	<img style="color: red; display: block; margin: auto; margin-top: 15px;" class="img-fluid" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADUAAAA1CAYAAADh5qNwAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAzZJREFUeNrsWottpDAQXaItgBKcDihhO7h0ENIBHRwdUAJJBVwq8F4F0AFJBVwHnH0aR5OJv9hk4cRIFhJrxvM8Hz/bezodcsghKSVbU/k8zxfxeBSNoddvor1kWXbd1UwJMKVo42wXDqB3B2YSrZHGo9ZqwD3sBUwtWm7ozwDchL6R35e3BpKD4dQwI5gAHeVWwJSoTwGewN7rTMYadE4hE7QaGOhXeRSIPFUox4Chyd3rZh0Mwp4pUA7hCZHAWMo8DQFUEM8YyzCAV30rT33O/AFwHNuQClDvWlNQ2PWeRmJwjYc9H9GyFBAjgHIAycH43DJoFTBOZQMF41aagqMKEQsB1WFAmgKg4ptpvrkkyGNmWi7g91oB9OZrSBHzoD8tGJHHAkKL8uyosAz9fvFRrJKydQDnqbjcEn0IPPehOyq8cs9i4lXuLeP1Gs8XnkuNCs/S1lGFVr0gbBpNDnwpKobkV8SXBY6rcmt0dli6wCHWQQ3u4H0XwxUN4+kd4e3KdHuqMfE4X1NG7mesboxnJSV4pPTJlwVjqMn7ty87w3sVy0Py84IsG9bQS2QADIzOppLkdB+R4noFvTWyvaAdmrXoPmEjZUIwbv64Ft0ns9mvvaf7FnAEVDA/TLrt16z2U+AZRA2VlYJqI8BEeRvzwCbUc2j94AhUayLLnp5pfPjenSe+V1Ga78XzSTR5sirB/BTNxgge1bfo3Tt8L6U0gZF6QX8O/Z9g/NcUVYvrckDDqj95jmwNGPJUjRb6CfXXeYYbxo3e0nNbYhvA4aOxjhSKmjCAUXOoyR3jrQvKsR8aVd5oQBWa5Oee4zhBnRNRIRn3V1jRH4C2XMX7PybqJPreQ18J/BfQqSRyPiWUEJ4HgJ/XIIJ3p/9QfEGxjdj7A55vMaB+kzXnZgJFpyR2LQKlYl5dluU3AiTBcFiMB5GPz9EKY4ktLekBvI8S6z7ZxAIL0B5ipgaV4jJvidf4gkXTCermt4qGC+neclNoBLW5+1+LQZ9CRQfKcG6+nZt6yyFmgy8P0JVQu6v/VBjOx3swnL7vdvEHEQdzD66cizjod4SmeODzuMHE3g855JBtyl8BBgA6ROd+rgqYXgAAAABJRU5ErkJggg==" alt="non-inventory">
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
				
				<div class="form-group col-xs-12 required">
					<input type="hidden" name="Type" value="{{ $category_selected }}" class="form-control">
				</div>

				<div class="form-group col-xs-6 required">
					<label for="Name">Name*</label>
					<input type="text" name="Name" value="{{ old('Name') }}" class="form-control" required>
				</div>

				<div class="form-group col-xs-6">
					<label for="Sku">SKU</label>
					<input type="text" name="Sku" value="{{ old('Sku') }}" class="form-control">
				</div>

				<div class="form-group col-xs-12">
					<div class="checkbox">
					  <label for="IsSubProductService">
					  	<input type="checkbox" name="SubItem" @if(old('SubItem')) checked @endif id="IsSubProductService">
					  	Is Sub-Product/Service
					  </label>
					</div>

					<select id="ParentRef" class="form-control" @if(old('SubItem'))  @else disabled="true" @endif>
						@foreach($accounts->items as $item)
							<?php
								$value = ['name' => $item->Name, 'value' => $item->Id];
							?>
							@if(old('ParentRef') == json_encode($value))
							    <option value="{{ json_encode($value) }}" selected>{{ $item->Name }}</option>
							@else
							    <option value="{{ json_encode($value) }}">{{ $item->Name }}</option>
							@endif
						@endforeach
					</select>
				</div>


				<div class="form-group col-xs-12">
					<hr class="m-b-0 m-t-0">
				</div>
				
				{{-- SALES IFORMATION --}}
				
				<div class="form-group col-xs-12">
					<div class="checkbox">
						<h4><b>Sales Information</b></h4>
						<label for="IsSales">
							<input checked="true" type="checkbox" id="IsSales" name="IsSales" class="">
							Sell This Product/Service To My Customers.
						</label>
					</div>					
				</div>

				<div id="sales">

						<div class="form-group col-xs-12">
							<textarea name="Description" value="{{ old('Description') }}" class="form-control" placeholder="Description on sales forms"></textarea>
						</div>
						
						<div class="form-group col-xs-6">
							<label for="salesPriceRate">Sales Price/Rate</label>
							<input type="number" value="{{ old('UnitPrice') }}" name="UnitPrice" class="form-control">
						</div>

						<div class="form-group col-xs-6">
							<label for="salesPriceRate">Income Account</label>
							<select name="IncomeAccountRef" id="salesPriceRate" class="form-control">
								@foreach($accounts->accountRefs as $key => $accnts)
									<optgroup label="{{ $key }}">
										@foreach($accnts as $account)
											<?php
												$value = ['name' => $account->Name, 'value' => $account->Id];
											?>
											@if(old('IncomeAccountRef') == json_encode($value))
												<option value="{{ json_encode($value) }}" selected>{{ $account->Name }}</option>
											@else
												<option value="{{ json_encode($value) }}">{{ $account->Name }}</option>
											@endif
										@endforeach
									</optgroup>	
								@endforeach
							</select>
						</div>

				</div> <!-- #sales -->
				

				<div class="form-group col-xs-12">
					<hr class="m-b-0 m-t-0">
				</div>


				{{-- Purchasing Information --}}
					
				<div class="form-group col-xs-12">
					<div class="checkbox">
						<h4><b>Purchasing Information</b></h4>
						<label for="IsPurchasing">
							<input type="checkbox" id="IsPurchasing" @if(old('IsPurchasing')) checked="checked" @endif"  name="IsPurchasing" class="">
							I Purchase This Product/Service From A Vendor.
						</label>
					</div>					
				</div>

				<div id="purchasing" style="display: none;">

						<div class="form-group col-xs-12">
							<textarea name="PurchaseDesc" value="{{ old('PurchaseDesc') }}" class="form-control" placeholder="Description on sales forms"></textarea>
						</div>
						
						<div class="form-group col-xs-6">
							<label for="PurchaseCost">Cost</label>
							<input name="PurchaseCost" type="number"  value="{{ old('PurchaseCost') }}" class="form-control">
						</div>

						<div class="form-group col-xs-6">
							<label for="purchasingExpense">Expense Account</label>
							<select name="ExpenseAccountRef" id="purchasingExpense" class="form-control">
								@foreach($accounts->accountRefs as $key => $accnts)
									<optgroup label="{{ $key }}">
										@foreach($accnts as $account)
											<?php
												$value = ['name' => $account->Name, 'value' => $account->Id];
											?>
											@if(old('ExpenseAccountRef') == json_encode($value))
												<option value="{{ json_encode($value) }}" selected>{{ $account->Name }}</option>
											@else
												<option value="{{ json_encode($value) }}">{{ $account->Name }}</option>
											@endif
										@endforeach
									</optgroup>	
								@endforeach
							</select>
						</div>

				</div> <!-- #sales -->


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

@section('after_scripts')
	<script>
		$(document).ready(function () {

			$('#IsSubProductService').change(function() {
		        if($(this).is(":checked")) {
		        	setTimeout(function () {
		        		$('#ParentRef').removeAttr('disabled');
		        		$('#ParentRef').attr('name', 'ParentRef');
		        		$('#ParentRef').prop('required', true);
		        	}, 100);
		        }
		        $('#ParentRef').attr('disabled', true);
		        $('#ParentRef').removeAttr('name', 'ParentRef');
		        $('#ParentRef').removeAttr('required');        
		    });


			var IsSales 	 		= $('#IsSales');
			var IsPurchasing 		= $('#IsPurchasing');
			var IsSubProductService = $('#IsSubProductService');

			if(IsPurchasing.is(":checked")) {
				setTimeout(function () {
					$('#purchasing').css('display', 'block');
				}, 100);
			}

			if(IsSubProductService.is(":checked")) {
				setTimeout(function () {
					$('#ParentRef').removeAttr('disabled');
	        		$('#ParentRef').attr('name', 'ParentRef');
	        		$('#ParentRef').prop('required', true);
				}, 100);	
			}


			IsSales.change(function() {
		        if($(this).is(":checked")) {
		        	setTimeout(function () {
			        	// $('#purchasing').css('display', 'none');
			        	$('#sales').css('display', 'block');

			        	IsSales.prop('checked', true);
			        	// IsPurchasing.prop('checked', false);

		        	}, 100);
		        } else {

		        	if(IsPurchasing.is(":checked") === false && $(this).is(":checked") === false) {
		        		console.log("Test");
		        		$('#purchasing').css('display', 'block');
			        	$('#sales').css('display', 'none');

			        	IsSales.prop('checked', false);
			        	IsPurchasing.prop('checked', true);
			        	return;
		        	}

		        	$('#sales').css('display', 'none');
		        	IsSales.prop('checked', false);

		        }

		    });


		    IsPurchasing.change(function() {
		        if($(this).is(":checked")) {
		        	setTimeout(function () {

			        	$('#purchasing').css('display', 'block');
			        	IsPurchasing.prop('checked', true);

		        	}, 100);
		        } else {

		        	if(IsSales.is(":checked") === false && $(this).is(":checked") === false) {
		        		$('#purchasing').css('display', 'none');
			        	$('#sales').css('display', 'block');

			        	IsSales.prop('checked', true);
			        	IsPurchasing.prop('checked', false);
			        	return;
		        	}

		        	$('#purchasing').css('display', 'none');
		        	IsPurchasing.prop('checked', false);

		        }   
		    });

		})
	</script>
@endsection
