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
				      
				        	<img style="color: red; display: block; margin: auto; margin-top: 15px;" class="img-fluid" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADUAAAA1CAYAAADh5qNwAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAA3hpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuNS1jMDIxIDc5LjE1NTc3MiwgMjAxNC8wMS8xMy0xOTo0NDowMCAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wTU09Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9tbS8iIHhtbG5zOnN0UmVmPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvc1R5cGUvUmVzb3VyY2VSZWYjIiB4bWxuczp4bXA9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC8iIHhtcE1NOk9yaWdpbmFsRG9jdW1lbnRJRD0ieG1wLmRpZDo4YWIzYTY5ZS0zODBkLTQwOTQtYTA1NC1mN2RhNWM0ZGUwOGYiIHhtcE1NOkRvY3VtZW50SUQ9InhtcC5kaWQ6NTFBQjBDRjBBQjJEMTFFNEJFOEVDMjRDMzE5MTZDODgiIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6NTFBQjBDRUZBQjJEMTFFNEJFOEVDMjRDMzE5MTZDODgiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENDIDIwMTQgKE1hY2ludG9zaCkiPiA8eG1wTU06RGVyaXZlZEZyb20gc3RSZWY6aW5zdGFuY2VJRD0ieG1wLmlpZDo4YWIzYTY5ZS0zODBkLTQwOTQtYTA1NC1mN2RhNWM0ZGUwOGYiIHN0UmVmOmRvY3VtZW50SUQ9InhtcC5kaWQ6OGFiM2E2OWUtMzgwZC00MDk0LWEwNTQtZjdkYTVjNGRlMDhmIi8+IDwvcmRmOkRlc2NyaXB0aW9uPiA8L3JkZjpSREY+IDwveDp4bXBtZXRhPiA8P3hwYWNrZXQgZW5kPSJyIj8+5jYGCgAABAdJREFUeNrsmWlIVUEUx322W5l+sMUWLUMqMivC0hYtykwKKySwjRZIK4qKINskhSiyaCFLggqyDTJoEaOsD6WtVEpY9KWMqEyIFtMyLV//wf+DYXrvvsV7n2h34Me998zMnXtmOefMXIvVavVpa8nXpw0mUylTqaYUA/aBn+A3CJbysoFVgwaQC6YAi1utCkNhALGgzPpvWiSV2Wt1Pb0Eia62b4RCm0EjP6YG5IEzfC4HflLZbiBAoTcIAgngOPgqKZcNLN5Waq30AeKDAin3B+8pvwuG2KnbF5xkmRRF8QPSezO9qdRE8JsNb7WTH6X0eim4AE5R0T+Ui2uSnfqpUt1Z3lBKTJlKNnhOo9xgcEWanmoqATEa9Q+z3BcwyFE5iw4RRXtwE8SCF2AsqHFSpzctY38+fwCPQYWTeh1BMYgCZSAa1Blh/faw976DoQZZU5n+4BPbPGHE9BMj3cAG5hmoSAjoID1Pk9bgMiPW1ALF/+jNFH78EUWeQXkdGGm0n9KbJfz4S4rcFxQy756c526YJMKVcQx96khnKf+ok9CnFuwGo3UIwRrBat5HeRr7TQXPwH2wAXQi46Uy3Z28ww9sAk/AI1pAT5PozG28/+iJ9cuSfMl3hj4FfC5WFnEPO6FPEH1ZMjgPfkiOdr0H0y+SIZctLXB3TW2XKufwI4W8D6imvIBhjlq3H+M+EWnES/IgdowtrXZRKQs74RdlVYwR3bJ+CZL3t9dwIq2PrddFuHOacV+JEvrEaXRYPYh2Qam5UkdcZee45aeEb/jMFxzTKDcK3NbYNtxh3OfIz+Wz3DvQ04lSYeA6SNOK1ts7WITCAOSDQC7qNRoLtpQhUhiNRi8p9BFG5bXWdg4sBREgHJwF08EfB+VfMd+jTWIue0eMVKgXfNFwUMs2d7ropxxiz6QvBqnsxYXgDeXtQB8dt/rB0ja9HKzg/RYws1lvVrSMkMxtlpJ3iPJYHUZmDt+1Q5Hn2NlaNHukjoAuoAhkKnkDeB2owyiF8BqqyNfTKQeA/XqdJkXzmqaxWI1M9VLoE6OXUlW8bqQF9HbqytGyWU9dlErndSV4CIZ5UaEx4CmYz2A1Qy+l8sBs8AlE0ket8sKBajp9WjhHKB5c1vOEVrxsBLjBSDgHJBmolHAhu3jWcZGO+JYRx86VIAGsAyXguYFKiSl3FywHyeCzHidBWiHMQWJkEnu0CeZfj2aMVEv8gfFXZCI0i+N9dWtU6r561mDHMre66ffDgbyCFrKoNY7UZNNQmEqZSplKmUqZSjXDT9XyOonbE0//q4rQJ5b334xQyp1/vjNAoY5ti92tOPx80JLT75pP09lcpQ7tvgUpRijk7kiZhsJUylTqP1XqrwADAJlpcr3kIV/2AAAAAElFTkSuQmCC" alt="bundle">
				        </div>
		        	</td>
		        	<td style="vertical-align: middle;">
						<h1 class="m-b-0 m-t-0" style="display: inline-block;">{{ $category_selected }}</h1>
						<p>A collection of Products and/or services that you sell together for example, a gift basket of fruit, cheese, and wine.</p>
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
					<input type="text" name="Name" class="form-control" required>
				</div>

				<div class="form-group col-xs-6">
					<label for="Sku">Sku</label>
					<input type="text" name="Sku" class="form-control">
				</div>

				<div class="form-group col-xs-12">
					<hr class="m-b-0 m-t-0">
				</div>


				{{-- SALES IFORMATION --}}
				
				<div class="form-group col-xs-12">
					{{-- <div class="checkbox"> --}}
						<h4><b>Sales Information</b></h4>
						<textarea id="SalesInformation" name="SalesInformation" class="form-control" placeholder="Description on Sales Form"></textarea>
				</div>					
				{{-- </div> --}}
	
				<div class="form-group col-xs-12">
					<div class="checkbox">
					  	<h4><b>Products/services included in the bundle</b></h4>
					  <label for="IsSubProductService">
					  	<input type="checkbox" name="IsSubProductService" @if(old('SubItem')) checked @endif id="IsSubProductService">
					  	Display bundle components when printing or sending transactions
					  </label>
					</div>
				</div>
				{{-- {{ dd(get_defined_vars()) }} --}}
				<div class="form-group col-md-12">
					<input type="hidden" id="productServiceJson" name="product_service" value="[]">
					<table class="table table-striped" id="productServiceTable">
						<thead>
							<th>PRODUCT/SERVICE</th>
							<th>QTY</th>
							<th></th>
						</thead>
						<tbody>
							
						</tbody>
					</table>

					<a href="javascript:void(0)" id="addProductService" class="btn btn-default">Add Product/Service</a>
				</div>
	
				<div class="col-md-12">
					<hr>
				</div>

			    <div class="box-footer">
					<button class="btn btn-success">Save Bundle</button>
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
	
		function json() {

			// TableData.shift();
			// $('#productServiceJson').val(JSON.stringify(TableData));
			// console.log("TDAta = ", TableData);
		}

		function parseFormToJson () {
			var TableData = new Array();

			tableRows = $('#productServiceTable tbody tr')

			for (var i = 0; i < tableRows.length; i++) {
				console.log("a = ", $(tableRows[i]).find('td:eq(0) select option:selected').val());
				console.log("b = ", $(tableRows[i]).find('td:eq(1) input').val());

				TableData[i] =
					{
				        "product_service" : $(tableRows[i]).find('td:eq(0) select option:selected').val(), 
				        "quantity" 	  	  : $(tableRows[i]).find('td:eq(1) input').val(),
				    }
			}
			console.log(TableData);
			$('#productServiceJson').val(JSON.stringify(TableData));
			

		}


		$('#addProductService').click(function () {
			var productService = '<tr>\
									<td>\
										<select onchange="parseFormToJson()" class="form-control" id="productService">\
												<option value="" selected disabled>-</option>\
											@foreach($accounts->items as $item)\
												<?php
													$value = ['name' => $item->Name, 'value' => $item->Id];
												?>\
											    <option value="{{ json_encode($value) }}">{{ $item->Name }}</option>\
											@endforeach\
										</select>\
									</td>\
									<td>\
										<input onkeyup="parseFormToJson()" id="quantity" type="number" class="form-control">\
									</td>\
									<td>\
										<a href="javascript:void(0)" onclick="deleteRow(this)" class="btn btn-default"><i class="fa fa-trash"></i></a>\
									</td>\
								</tr>';

			$('#productServiceTable tbody').append(productService);
		});

		function deleteRow (btn) {
			var row = btn.parentNode.parentNode;
  				row.parentNode.removeChild(row);
		}

	</script>
@endsection