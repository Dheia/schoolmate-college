@extends('backpack::layout')


@section('header')
	<section class="content-header">
	  <h1>
        <span class="text-capitalize">{{ $crud->entity_name_plural }}</span>
        {{-- <small>{{ trans('backpack::crud.add').' '.$crud->entity_name }}.</small> --}}
	  </h1>
	  <ol class="breadcrumb">
	    <li><a href="{{ url(config('backpack.base.route_prefix'), 'dashboard') }}">{{ trans('backpack::crud.admin') }}</a></li>
	    <li><a href="{{ url($crud->route) }}" class="text-capitalize">{{ $crud->entity_name_plural }}</a></li>
	    {{-- <li class="active">{{ trans('backpack::crud.add') }}</li> --}}
	  </ol>
	</section>
@endsection

@section('content')
<div class="row">
	<div class="col-md-12">
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
		  <div class="box">

		    <div class="box-header with-border">
		      {{-- <h3 class="box-title">{{ trans('backpack::crud.add_a_new') }} {{ $crud->entity_name }}</h3> --}}
		    </div>
		    <div class="box-body display-flex-wrap" style="display: flex; flex-wrap: wrap;">
		     
					


			<div class="text-center col-md-12 mt-2" style="margin: auto;">
				<img src="/{{\Config::get('settings.schoollogo')}}" width="90">
				<p>
					{{\Config::get('settings.schoolname')}} <br/>
					<small>{{\Config::get('settings.schooladdress')}}</small>
				</p>
			</div>

			<div class="container col-lg-10 mt-2">

			    <h1 class="text-center mt-5"> {{ $locker->name }}</h1>


			    <table class="table table-sm table-bordered table-striped">
					<thead>
						<th colspan="2" class="text-center text-uppercase">Locker Inventory Information</th>
					</thead>
					<tbody>
						<tr>
							<td><b>Name</b></td>
							<td>{{ $locker->name }}</td>
						</tr>
						<tr>
							<td><b>Student Number</b></td>
							<td>WIS - {{ $locker->studentnumber }}</td>
						</tr>
						<tr>
							<td><b>Description</b></td>
							<td>{{ $locker->description }}</td>
						</tr>
						<tr>
							<td><b>Building</b></td>
							<td>{{ $locker->building->name }}</td>
						</tr>
						<tr>
							<td><b>Occupied</b></td>
							<td><input type="checkbox" {{ $locker->studentnumber !== null ? 'checked' : ''}}></td>
						</tr>
					</tbody>
				</table>
		
				<br>
			
				<table class="table table-sm table-bordered table-striped" id="logs">
					<thead>
						<tr>
							<th colspan="5" class="text-center text-uppercase">History Logs</th>
						</tr>
						<tr>
							<th>Date</th>
							<th>Old Student</th>
							<th>New Student</th>
							<th>Description</th>
							<th>Updated By</th>
						</tr>
					</thead>
				<tbody>
					{{-- {{dd($logs)}} --}}
					@foreach($logs as $log)
						<tr>
							<td>{{ $log->created_at->toFormattedDateString() }} {{ $log->created_at->format('g:i A') }}</td>
							<td>{{ Config::get('settings.schoolabbr') }} - {{ $log->oldStudent->studentnumber }}</td>
							<td>{{ Config::get('settings.schoolabbr') }} - {{ $log->newStudent->studentnumber }}</td>
							<td>{{ $log->description }}</td>
							<td>{{ $log->user->email }}</td>
						</tr>
					@endforeach
				</tbody>
				</table>


			</div>



		    </div><!-- /.box-body -->
		    <div class="box-footer">


		    </div><!-- /.box-footer-->

		  </div><!-- /.box -->
		  </form>
	</div>
</div>

@endsection

@push('after_styles')
	<link rel="stylesheet" href="//cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
@endpush


@push('after_scripts')
	<script src="{{ asset('js/jquery.min.js') }}"></script>
	<script src="{{ asset('js/bootstrap4.min.js') }}"></script>
	<script src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
	
	<script>
		$('#logs').DataTable();
	</script>
@endpush
