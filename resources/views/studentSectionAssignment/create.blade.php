@extends('backpack::layout')

@section('header')
	

<section class="content-header">
  <h1>
    <span class="text-capitalize">{!! $crud->getHeading() ?? $crud->entity_name_plural !!}</span>
    {{-- <small>{!! $crud->getSubheading() ?? trans('backpack::crud.add').' '.$crud->entity_name !!}.</small> --}}
    <small>
		<b>{{ $section->level->year }} - {{ $section->name }}, {{ $schoolYear }}</b>
	</small>
    <small id="datatable_info_stack"></small>
  </h1>
  <ol class="breadcrumb">
    <li><a href="{{ url(config('backpack.base.route_prefix'), 'dashboard') }}">{{ trans('backpack::crud.admin') }}</a></li>
    <li><a href="{{ url($crud->route) }}" class="text-capitalize">{{ $crud->entity_name_plural }}</a></li>
    <li class="active">{{ trans('backpack::crud.add') }}</li>
  </ol>
</section>


@endsection

@section('content')
<!-- Default box -->
 {{-- {!! dd(backpack_auth()->user()) !!} --}}



<div class="row m-t-20">
	<div class="{{ $crud->getCreateContentClass() }}">
		<!-- Default box -->
		@if ($crud->hasAccess('list'))
			<a href="{{ url($crud->route) }}"><i class="fa fa-angle-double-left"></i> {{ trans('backpack::crud.back_to_all') }} <span>{{ $crud->entity_name_plural }}</span></a><br><br>
		@endif

		@include('crud::inc.grouped_errors')

		  <form method="post"
		  		action="{{ url($crud->route) }}"
				@if ($crud->hasUploadFields('create'))
				enctype="multipart/form-data"
				@endif
		  		>
		  {!! csrf_field() !!}
		  <div class="col-md-12">

		    <div class="row display-flex-wrap">
		   
		      	<input type="hidden" name="http_referrer" value={{ old('http_referrer') ?? \URL::previous() ?? url($crud->route) }}>

				@if ($crud->model->translationEnabled())
				<input type="hidden" name="locale" value={{ $crud->request->input('locale')?$crud->request->input('locale'):App::getLocale() }}>
				@endif

				{{-- See if we're using tabs --}}
				@if ($crud->tabsEnabled() && count($crud->getTabs()))
				    @include('crud::inc.show_tabbed_fields')
				    <input type="hidden" name="current_tab" value="{{ str_slug($crud->getTabs()[0], "") }}" />
				@else
				    <div class="box col-md-12 padding-10 p-t-20">
				    @include('crud::inc.show_fields', ['fields' => $crud->getFields('create')])
				    </div>
				@endif
		    </div>
		    {{-- /.box-body --}}
		    <div class="">

                {{-- @include('crud::inc.form_save_buttons') --}}
				
    <a href="{{ $crud->hasAccess('create') ? url($crud->route) : url()->previous() }}" class="btn btn-default"><span class="fa fa-arrow-left"></span> &nbsp; Back</a>
		    </div> 
		    {{-- /.box-footer --}}

		  </div>  
		  {{-- /.box --}}
		  </form>
	</div>
</div>

@endsection

@section('after_styles')
  <!-- DATA TABLES -->
  <link href="https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap.min.css" rel="stylesheet" type="text/css" />
  <link rel="stylesheet" href="https://cdn.datatables.net/fixedheader/3.1.5/css/fixedHeader.dataTables.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.1/css/responsive.bootstrap.min.css">


  <link rel="stylesheet" href="{{ asset('vendor/backpack/crud/css/crud.css') }}">
  <link rel="stylesheet" href="{{ asset('vendor/backpack/crud/css/form.css') }}">
  <link rel="stylesheet" href="{{ asset('vendor/backpack/crud/css/list.css') }}">

  <link rel="stylesheet" href="{{ asset('vendor/backpack/crud/css/reorder.css') }}">
  <link rel="stylesheet" href="{{ asset('vendor/backpack/nestedSortable/nestedSortable.css') }}">

  <!-- CRUD LIST CONTENT - crud_list_styles stack -->
  @stack('crud_list_styles')
@endsection

@section('after_scripts')
	{{-- @include('crud::inc.datatables_logic') --}}
{{-- 
  <script src="{{ asset('vendor/backpack/crud/js/crud.js') }}"></script>
  <script src="{{ asset('vendor/backpack/crud/js/form.js') }}"></script>
  <script src="{{ asset('vendor/backpack/crud/js/list.js') }}"></script> --}}


  <!-- CRUD LIST CONTENT - crud_list_scripts stack -->
  <script src="https://code.jquery.com/ui/1.11.3/jquery-ui.min.js" type="text/javascript"></script>
  <script src="{{ asset('vendor/backpack/crud/js/reorder.js') }}"></script>
  <script src="{{ url('vendor/backpack/nestedSortable/jquery.mjs.nestedSortable2.js') }}" type="text/javascript"></script>
	
	<script>
		$('form').on('keyup keypress', function(e) {
			var keyCode = e.keyCode || e.which;
			if (keyCode === 13) { 
				e.preventDefault();
				return false;
			}
		});

	</script>

	@yield('custom_script')
	@stack('crud_list_scripts')
@endsection

