@extends('backpack::layout')

@section('header')
	<title>Setup Quiz</title>
	<style>
		.div-shadow{
			width: 5em;
			height: 2em;
			margin: 0 1em 1em 0;
			transition: all 100ms ease-in-out;
		}
		#div-container {
			display: flex;
			flex-wrap: wrap;
			width: 100%;
			margin: 2em 0em;
		}
	</style>
@endsection

@section('content')
	<!-- HEADER -->
  	<div class="row" style="padding: 15px;">
	    <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12 smo-search-group"> 
	     	<section class="content-header">
				
				<ol class="breadcrumb">
				    <li><a href="{{ url(config('backpack.base.route_prefix'), 'dashboard') }}">{{ trans('backpack::crud.admin') }}</a></li>
				    <li><a href="{{ url($crud->route) }}" class="text-capitalize">{{ $crud->entity_name_plural }}</a></li>
				    <li class="active">{{ trans('backpack::crud.add') }}</li>
				</ol>
			</section>
			<h1 class="smo-content-title">
		        <span class="text-capitalize">{!! $crud->getHeading() ?? $crud->entity_name_plural !!}</span>
		        <small>{!! $crud->getSubheading() ?? mb_ucfirst(trans('backpack::crud.add')).' '.$crud->entity_name !!}.</small>
		    </h1>
		</div>
	</div>
	<!-- END OF HEADER -->


@if ($crud->hasAccess('list'))
	<a href="{{ url($crud->route) }}" class="hidden-print"><i class="fa fa-angle-double-left"></i> {{ trans('backpack::crud.back_to_all') }} <span>{{ $crud->entity_name_plural }}</span></a>

	<!-- <a href="javascript: window.print();" class="pull-right hidden-print"><i class="fa fa-print"></i></a> -->
@endif
<div class="row">
	<div class="{{ $crud->getShowContentClass() }}">

	<!-- Default box -->
	  <div class="m-t-20" style="display: grid;">
	  	@if ($crud->model->translationEnabled())
	    <div class="row">
	    	<div class="col-md-12 m-b-10">
				<!-- Change translation button group -->
				<div class="btn-group pull-right">
				  <button type="button" class="btn btn-sm btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				    {{trans('backpack::crud.language')}}: {{ $crud->model->getAvailableLocales()[$crud->request->input('locale')?$crud->request->input('locale'):App::getLocale()] }} &nbsp; <span class="caret"></span>
				  </button>
				  <ul class="dropdown-menu">
				  	@foreach ($crud->model->getAvailableLocales() as $key => $locale)
					  	<li><a href="{{ url($crud->route.'/'.$entry->getKey()) }}?locale={{ $key }}">{{ $locale }}</a></li>
				  	@endforeach
				  </ul>
				</div>
			</div>
	    </div>
	    @else
	    @endif
	    <div class="col-md-12">
	    	<div class="box no-padding no-border">
				<div class="box" style="border-radius: 5px;">
	              <div class="box-body with-border" style="padding: 0 20px !important;">
	                <h3>
	                   {{ $entry->title }}
	                </h3>
	                <h5>{!! $entry->description !!}</h5>
	              </div>
	            </div>
		    </div><!-- /.box-body -->
	    </div>

		<!-- LIST OF QUESTION -->
		<question-quiz quiz_id="{{ $entry->id }}" > </question-quiz>
     
	  </div><!-- /.box -->
	</div>
	</form>
</div>

@endsection


@section('after_styles')
	<!-- <link href="https://unpkg.com/survey-vue/survey.min.css" type="text/css" rel="stylesheet"/> -->

	<style>
		.content-wrapper {
			padding-left: 0 !important;
			padding-right: 0 !important;
		}
	</style>
@endsection

@push('before_scripts')
   {{-- VUE JS --}}
  <script src="{{ mix('js/onlineclass/quiz.js') }}"></script>
@endpush

@section('after_scripts')
	<!-- <script src="{{ asset('vendor/backpack/crud/js/crud.js') }}"></script>
	<script src="{{ asset('vendor/backpack/crud/js/show.js') }}"></script> -->
	
	{{-- VUE JS --}}
	<!-- <link rel="stylesheet" href="{{ mix('css/app.css') }}"> -->
    {{-- <script src="{{ mix('js/onlineclass/quiz.js') }}"></script> --}}
@endsection
