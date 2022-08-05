@extends('backpack::layout')

@section('header')
	<section class="content-header">
	  <h1>
        <span class="text-capitalize">{!! $crud->getHeading() ?? $crud->entity_name_plural !!}</span>
	  </h1>
	  <ol class="breadcrumb">
	    <li><a href="{{ url(config('backpack.base.route_prefix'), 'dashboard') }}">{{ trans('backpack::crud.admin') }}</a></li>
	    <li class="active"><a href="javascript:void(0)" class="text-capitalize">{{ $crud->entity_name_plural }}</a></li>
	    {{-- <li class="active">{{ trans('backpack::crud.add') }}</li> --}}
	  </ol>
	</section>
@endsection

@section('content')
@if ($crud->hasAccess('list'))
	{{-- <a href="{{ url($crud->route) }}" class="hidden-print"><i class="fa fa-angle-double-left"></i> {{ trans('backpack::crud.back_to_all') }} <span>{{ $crud->entity_name_plural }}</span></a> --}}
@endif

<div class="row m-t-20">
	<div class="{{ $crud->getCreateContentClass() }}">
		<!-- Default box -->

		@include('crud::inc.grouped_errors')

		  {{-- <form method="get"
		  		action="{{ url($crud->route) }}"
				@if ($crud->hasUploadFields('create'))
				enctype="multipart/form-data"
				@endif
		  		> --}}
		  {{-- {!! csrf_field() !!} --}}
		<div class="col-md-12">

		    <div class="row display-flex-wrap">
		
				<div class="box attendance-table-logs col-md-10 padding-10 p-t-20">
					@foreach($curriculum->subjectMappings as $subject_mapping)
						<table class="table table-bordered table-striped">
							<thead>
								<tr>
									<th class="text-center" colspan="6" style="vertical-align: middle;">
										{{$subject_mapping->level->year}}
									</th>
								</tr>
								@if($subject_mapping->term->type == 'Semester')
								<tr>
									<th class="text-center" colspan="6" style="vertical-align: middle;">
										{{$subject_mapping->term_type . ' Term'}}
									</th>
								</tr>
								@endif
								<tr>
									<th class="text-center" rowspan="2" style="vertical-align: middle;">Code</th>
									<th class="text-center" rowspan="2" style="vertical-align: middle;">Subject Description</th>
									<th class="text-center" colspan="2" style="vertical-align: middle;">Number Of Hours</th>
									<th class="text-center" rowspan="2" style="vertical-align: middle;">No. Of Units</th>
									<th class="text-center" rowspan="2" style="vertical-align: middle;">Prerequisites</th>
								</tr>
								<tr>
									<th class="text-center" style="vertical-align: middle;">Lec</th>
									<th class="text-center" style="vertical-align: middle;">Lab</th>
								</tr>
							</thead>

							<tbody>
								@php
									$total_units = 0;
								@endphp
								@foreach($subject_mapping->subjects as $subject)
									@php
										$subject = App\Models\SubjectManagement::where('id', $subject->subject_code)->first();
									@endphp
									@if($subject)
										<tr>
											<td class="text-center">{{ $subject->subject_code }}</td>
											<td class="text-center">{{ $subject->subject_description }}</td>
											<td class="text-center">0</td>
											<td class="text-center">0</td>
											<td class="text-center">{{ number_format( (float) $subject->no_unit, 1, '.', '') }}</td>
											<td class="text-center"></td>
										</tr>

										@php 
											$total_units += $subject->no_unit; 
										@endphp
									@endif
								@endforeach
									<tr>
										<td></td>
										<td class="text-right"><b>Total</b></td>
										<td class="text-center">0</td>
										<td class="text-center">0</td>
										<td class="text-center">{{ $total_units }}</td>
										<td></td>
									</tr>
							</tbody>
						</table>
					@endforeach
				</div><!-- /.box -->

		  	</div>
		  {{-- </form> --}}

		</div>
</div>

@endsection

@section('after_styles')
	<style>
		table > tr th {
			vertical-align: middle;
		}
	</style>
@endsection

@section('after_scripts')

@endsection