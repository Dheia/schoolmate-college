@extends('backpack::layout')

@section('header')
    <section class="content-header buttons-header">
      {{-- <div class="btn-group" role="group" aria-label="Basic example">
			<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addOtherProgram">Add Other Programs</button>
			<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addPayment">Add Payments</button>
			<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addSpecialDiscount">Add Special Discount</button>
		</div> --}}
    </section>
@endsection


@section('content')
			<!-- Default box -->	
		{{-- {{dd($students->getFillable())}} --}}
		
	<div class="row">
		<div class="col-md-2">

			<ul class="nav nav-tabs" id="myTab">
			    <li class="active"><a href=".front-card-wrapper" data-toggle="tab">Front Card</a></li>
			    <li><a href=".front-back-wrapper" data-toggle="tab">Back Card</a></li>
			</ul>
			
			<div class="tab-content">
				{{-- FRONT --}}
				@include('smartCard.front.frontCardPanel')
				{{-- .front-card-wrapper --}}

				{{-- Rear Options --}}
				@include('smartCard.back.backCardPanel')
			</div>
			
			<div class="form-group">
				<label title="Template" for="name"><span class="mdi mdi-image"> Template Name</span></label>
				<input type="text" id="name" name="name" class="form-control" value="{{ $entry->template_name }}" required/>
			</div>

			{{-- <div class="col-md-12">
				<button class="btn btn-block btn-primary" id="btnJson">Save</button>
			</div> --}}
		</div>
		{{-- Front --}}
		<div class="col-md-5" style="margin: auto;">
			<canvas id="canvas_front" width="638" height="1021px" style="border: 1px solid #ccc;"></canvas>
		</div>
		{{-- Back --}}
		<div class="col-md-5" style="margin: auto;">
			<canvas id="card_back" width="638" height="1021px" style="border: 1px solid #ccc;"></canvas>
		</div>
	</div>
	<hr>
	<form id="formEditor" method="post"
		  		action="{{ url($crud->route.'/'.$entry->getKey()) }}"
				@if ($crud->hasUploadFields('update', $entry->getKey()))
				enctype="multipart/form-data"
				@endif
		  		>
		  {!! csrf_field() !!}
		  {!! method_field('PUT') !!}
		  <div class="col-md-12">
		  	{{-- @if ($crud->model->translationEnabled())
		    <div class="row m-b-10">
		    	<!-- Single button -->
				<div class="btn-group pull-right">
				  <button type="button" class="btn btn-sm btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				    {{trans('backpack::crud.language')}}: {{ $crud->model->getAvailableLocales()[$crud->request->input('locale')?$crud->request->input('locale'):App::getLocale()] }} &nbsp; <span class="caret"></span>
				  </button>
				  <ul class="dropdown-menu">
				  	@foreach ($crud->model->getAvailableLocales() as $key => $locale)
					  	<li><a href="{{ url($crud->route.'/'.$entry->getKey().'/edit') }}?locale={{ $key }}">{{ $locale }}</a></li>
				  	@endforeach
				  </ul>
				</div>
		    </div>
		    @endif --}}
		    <input type="hidden" name="http_referrer" value={{ old('http_referrer') ?? \URL::previous() ?? url($crud->route) }}>

			@if ($crud->model->translationEnabled())
			<input type="hidden" name="locale" value={{ $crud->request->input('locale')?$crud->request->input('locale'):App::getLocale() }}>
			@endif
		    <div class="row display-flex-wrap" style="display: none;">
		      <!-- load the view from the application if it exists, otherwise load the one in the package -->
		      {{-- @if(view()->exists('vendor.backpack.crud.form_content')) --}}
		      	@include('smartCard.editScript', ['fields' => $fields, 'action' => 'edit'])
		      {{-- @else --}}
		      	{{-- @include('crud::form_content', ['fields' => $fields, 'action' => 'edit']) --}}
		      {{-- @endif --}}
		    </div><!-- /.box-body -->

            <div class="">

                @include('crud::inc.form_save_buttons')

		    </div><!-- /.box-footer-->
		  </div><!-- /.box -->
	</form>
@endsection
