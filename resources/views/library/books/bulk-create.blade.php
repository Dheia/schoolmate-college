@extends('backpack::layout')

@section('header')
	<section class="content-header">
	  <h1>
        <span class="text-capitalize">{{ $crud['entity_name_plural'] }}</span>
        <small>{{ trans('backpack::crud.add').' '.$crud['entity_name_plural'] }}.</small>
	  </h1>
	  <ol class="breadcrumb">
	    <li><a href="{{ url(config('backpack.base.route_prefix'), 'dashboard') }}">{{ trans('backpack::crud.admin') }}</a></li>
	    <li><a href="{{ url($crud['route']) }}" class="text-capitalize">{{ $crud['entity_name_plural'] }}</a></li>
	    <li class="active">{{ trans('backpack::crud.add') }}</li>
	  </ol>
	</section>
@endsection

@section('content')
<div class="row">
	<div class="col-md-12">
		<!-- Default box -->
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
		<form method="post"
	  		action="{{ url($crud['route']) }}"
	  		>
		  {!! csrf_field() !!}
		  	<div class="box">

			    <div class="box-header with-border pb-3">
			      	<h3 class="box-title">{{ trans('backpack::crud.add_a_new') }} {{$quantity}} {{ $title }}</h3>
			    </div>
			    <div class="container-fluid">
					<h3 class="box-title text-center"><strong>{{ $title }}</strong></h3>
					<div class="col-md-12">
						<div class="col-md-4">
							<h4 class="ml-1"><strong>Call No. : </strong> {{$call_number}}</h4>
						</div>
					</div>
					<div class="col-md-12">
						<div class="col-md-4">
							<h4 class="ml-1"><strong>Edition : </strong> {{$edition}}</h4>
						</div>
						<div class="col-md-4">
							<h4 class="ml-1"><strong>Year Published : </strong> {{$year_published}}</h4>
						</div>
						<div class="col-md-4">
							<h4 class="ml-1"><strong>Publisher : </strong> {{$publisher}}</h4>
						</div>
					</div>
			    </div>
			    <input type="hidden" class="form-control" name="data"  id="data" value="{{$data}}">
				@for ($i = 1; $i <= $quantity; $i++)
				<div class="col-md-12 pt-2">
					<div class="row">
						@if($i == 1)
						<div class="form-group col-md-4">
							<label for="accession_number">Accession No.<span style="color: red"> *</span></label>
							<input readonly type="number" class="form-control" name="accession_number{{$i}}"  id="accession_number{{$i}}" placeholder="Accession No." value="{{$accession_number}}">
						</div>
						<div class="form-group col-md-4">
						    <label for="isbn">ISBN</label>
						    <input readonly type="text" class="form-control" name="isbn{{$i}}" id="isbn{{$i}}" placeholder="ISBN" value="{{$isbn}}">
						</div>
						<div class="form-group col-md-4 required">
						    <label for="code">Code</label>
						    <input readonly type="text" class="form-control" name="code{{$i}}" id="code{{$i}}" placeholder="Code" value="{{$code}}">
						</div>
						@else
						<div class="form-group col-md-4">
							<label for="accession_number">Accession No.<span style="color: red"> *</span></label>
							<input type="number" class="form-control" name="accession_number{{$i}}" id="accession_number{{$i}}" placeholder="Accession No." readonly value="{{$accession_number+$i-1}}">
						</div>
						<div class="form-group col-md-4">
						    <label for="isbn">ISBN</label>
						    <input type="text" class="form-control" name="isbn{{$i}}" id="isbn{{$i}}" placeholder="ISBN">
						</div>
						<div class="form-group col-md-4 required">
						    <label for="code">Code</label>
						    <input type="text" class="form-control" name="code{{$i}}" id="code{{$i}}" placeholder="Code">
						</div>
						@endif
					</div>
				</div>
				@endfor
		   	 	<!-- /.box-body -->
		    	<div class="box-footer">
	                <div class="btn-group open">
				        <button type="submit" class="btn btn-success">
				            <span class="fa fa-save" role="presentation" aria-hidden="true"></span> &nbsp;
				            <span data-value="save_and_back">Save</span>
				        </button>
				    </div>
					<a href="{{ url()->previous() }}" class="btn btn-default">
					 	<span class="fa fa-ban"></span> &nbsp;{{ trans('backpack::crud.cancel') }}
					</a>
			    </div><!-- /.box-footer-->

		  	</div><!-- /.box -->
		</form>
	</div>
</div>
@endsection
