@extends('backpack::layout')

@section('header')
	<section class="content-header">
	  <h1>
        <span class="text-capitalize">{!! $crud->getHeading() ?? $crud->entity_name_plural !!}</span>
        <small>{!! $crud->getSubheading() ?? mb_ucfirst(trans('backpack::crud.preview')).' '.$crud->entity_name !!}.</small>
      </h1>
	  <ol class="breadcrumb">
	    <li><a href="{{ url(config('backpack.base.route_prefix'), 'dashboard') }}">{{ trans('backpack::crud.admin') }}</a></li>
	    <li><a href="{{ url($crud->route) }}" class="text-capitalize">{{ $crud->entity_name_plural }}</a></li>
	    <li class="active">{{ trans('backpack::crud.preview') }}</li>
	  </ol>
	</section>
@endsection

@section('content')
@if ($crud->hasAccess('list'))
	<a href="{{ url($crud->route) }}" class="hidden-print"><i class="fa fa-angle-double-left"></i> {{ trans('backpack::crud.back_to_all') }} <span>{{ $crud->entity_name_plural }}</span></a>

	<a href="javascript: window.print();" class="pull-right hidden-print"><i class="fa fa-print"></i></a>
@endif
<div id="app" class="row">
	<div class="{{ $crud->getShowContentClass() }}">

	<!-- Default box -->
	  <div class="m-t-20">
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
	    <div class="box no-padding no-border">
			
			<submitted-grade transmutation-table="{{ $transmutation_table }}" template-id="{{ $template_id }}" subject-id="{{ $subject_id }}" section-id="{{ $section_id }}" period-id="{{ $period_id }}" school-year-id="{{ $school_year_id }}" term-type="{{ $term_type }}"></submitted-grade>

	    </div><!-- /.box-body -->
	  </div><!-- /.box -->

	</div>
</div>
@endsection


@section('after_styles')
	<link rel="stylesheet" href="{{ asset('vendor/backpack/crud/css/show.css') }}">
	<link rel="stylesheet" href="{{ asset('jqwidgets/styles/jqx.base.css') }}">
	<link rel="stylesheet" href="{{ asset('jqwidgets/styles/jqx.bootstrap.css') }}">
@endsection

@section('after_scripts')

    <script>
    	window.topcolumnheader = {!! json_encode($topcolumnheader) !!};
    	window.subcolumnheader = {!! json_encode($subcolumnheader) !!};
    	window.rows = {!! $rows !!};
      function  getUrlVars() {
          var vars = {};
          var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {
              vars[key] = value;
          });
          return vars;
      }
      let allParameters = '?template_id=' + {{ $template_id }} + '&subject_id=' + {{ $subject_id }} + '&section_id=' + {{ $section_id }} + '&term_type=' + '{{ $term_type }}' + '&school_year_id=' + {{ $school_year_id }};

    </script>


	<script src="{{ \Request::getSchemeAndHttpHost() }}/js/app.js"></script>
	<script src="{{ asset('vendor/backpack/crud/js/show.js') }}"></script>
@endsection
