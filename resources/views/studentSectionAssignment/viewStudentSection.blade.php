@extends('backpack::layout')

@section('header')
	<section class="content-header">
	{{--   <h1>
      <span class="text-capitalize">{!! $crud->getHeading() ?? $crud->entity_name_plural !!}</span>
      <small id="datatable_info_stack">{!! $crud->getSubheading() ?? trans('backpack::crud.all').'<span>'.$crud->entity_name_plural.'</span> '.trans('backpack::crud.in_the_database') !!}.</small>
	  </h1>
 --}}	  
 	<ol class="breadcrumb">
	    <li><a href="{{ url(config('backpack.base.route_prefix'), 'dashboard') }}">{{ trans('backpack::crud.admin') }}</a></li>
	    <li><a href="{{ url($crud->route) }}" class="text-capitalize">{{ $crud->entity_name_plural }}</a></li>
	    <li class="active">{{ trans('backpack::crud.list') }}</li>
	</ol>
	</section>
@endsection

@section('content')
	<!-- Default box -->
	
	<div class="box">
	    <div class="box-header with-border">
			

	    	<div class="col-md-12 m-b-30 m-t-20">
    			<table  style="margin: auto;">
					<tbody>
						<tr>
							<td>
	    						<img width="100" src="/{{ Config::get('settings.schoollogo') }}" alt="School Logo">									
							</td>
							<td>
					    		<h1 class="m-b-0"><b>{{ Config::get('settings.schoolname') }}</b></h1>
					    		<p class="m-b-0 text-center">{{ Config::get('settings.schooladdress') }}</p>
					    		<p class="m-b-0 text-center">{{ Config::get('settings.schoolcontactnumber') }}</p>
							</td>
						</tr>
					</tbody>	    				
    			</table>
	    	</div>

	    	<div class="clearfix"></div>
			
			<div class="col-md-12 m-b-0" style="padding: 0;">
					<div class="col-md-4 p-t-5 p-b-5"  style="border: 1px solid #f4f4f4; background-color: #ecf0f5;">
						<b>Level:</b> {{ $section->level->year }} 
					</div>
					<div class="col-md-4 p-t-5 p-b-5"  style="border: 1px solid #f4f4f4; background-color: #ecf0f5;">
						<b>Section:</b> {{ $section->name }}
					</div>
					<div class="col-md-4 p-t-5 p-b-5"  style="border: 1px solid #f4f4f4; background-color: #ecf0f5;">
						<b>School Year:</b> {{ $schoolYear->schoolYear }}
					</div>
			</div>
	    	{{-- MALE --}}
	    	<div class="col-md-6" style="padding: 0;">
		    	<table class="table table-striped table-bordered">
		    		<thead>
		    			<tr>
		    				<th colspan="2" class="text-center">MALE</th>
		    			</tr>
		    			<tr>
			    			<th>Student No.</th>
			    			<th>Fullname</th>
		    			</tr>
		    		</thead>
		    		<tbody>
		    			@if($sections !== null)
			    			@if(array_key_exists('Male',json_decode($sections, true)))
				    			@foreach($sections['Male'] as $section)
									<tr>
										<td>{{ Config::get('settings.schoolabbr') }} - {{ $section->student->studentnumber }}</td>
										<td>{{ $section->student->fullname }}</td>
									</tr>
				    			@endforeach
			    			@endif
		    			@endif
		    		</tbody>
		    	</table>
	    	</div>

	    	{{-- FEMALE --}}
	    	<div class="col-md-6" style="padding: 0">
		    	<table class="table table-striped table-bordered">
		    		<thead>
		    			<tr>
		    				<th class="text-center" colspan="2">FEMALE</th>
		    			</tr>
		    			<tr>
			    			<th>Student No.</th>
			    			<th>Fullname</th>
		    			</tr>
		    		</thead>
		    		<tbody>
		    			@if($sections !== null)
			    			@if(array_key_exists('Female',json_decode($sections, true)))
				    			@foreach($sections['Female'] as $section)
									<tr>
										<td>{{ Config::get('settings.schoolabbr') }} - {{ $section->student->studentnumber }}</td>
										<td>{{ $section->student->fullname }}</td>
									</tr>
				    			@endforeach
			    			@endif
		    			@endif
		    		</tbody>
		    	</table>
	    	</div>

	    </div><!-- /.box-footer-->
	</div><!-- /.box -->

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
	@include('crud::inc.datatables_logic')

  <script src="{{ asset('vendor/backpack/crud/js/crud.js') }}"></script>
  <script src="{{ asset('vendor/backpack/crud/js/form.js') }}"></script>
  <script src="{{ asset('vendor/backpack/crud/js/list.js') }}"></script>


  <!-- CRUD LIST CONTENT - crud_list_scripts stack -->
  <script src="https://code.jquery.com/ui/1.11.3/jquery-ui.min.js" type="text/javascript"></script>
  <script src="{{ asset('vendor/backpack/crud/js/reorder.js') }}"></script>
  <script src="{{ url('vendor/backpack/nestedSortable/jquery.mjs.nestedSortable2.js') }}" type="text/javascript"></script>

  @yield('custom_script')
  
  @stack('crud_list_scripts')
@endsection

