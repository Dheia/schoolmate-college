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
<div class="row">
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

	    			@php
	    				$payroll = json_decode($entry->payroll);

    				@endphp

			  		<div class="box-header" style="overflow: auto;">
			  			<table id="table_id" class="table table-striped table-bordered nowrap">
		        			<thead>
		        				<th><small>Employee #</small></th>
		        				<th><small>Full Name</small></th>
		        				<th><small>Monthly</small></th>
		        				<th><small>Basic Salary</small></th>
		        				<th><small>Absent/Late</small></th>
		        				<th><small>Admin Fee</small></th>
		        				<th><small>Allowances</small></th>
		        				<th><small>Gross Pay</small></th>
		        				@if($payroll->items->includes->sss) <th><small>SSS</small></th> @endif
		        				@if($payroll->items->includes->sss_loan) <th><small>SSS Loan</small></th> @endif
		        				@if($payroll->items->includes->philhealth) <th><small>Philhealth</small></th> @endif
		        				@if($payroll->items->includes->hdmf) <th><small>HDMF</small></th> @endif
		        				@if($payroll->items->includes->hdmf_loan) <th><small>PagIbig Loan</small></th> @endif
		        				@if($payroll->items->includes->tax) 
		        					<th><small>Total Taxable <br>Income</small></th>
		        					<th><small>W. Tax</small></th> 
	        					@endif
		        				<th><small>Total Adjustment</small></th>
		        				<th><small>Net Pay <br>After Tax</small></th>
		        			</thead>
							{{-- 16 cols --}}
							<tbody>
								<tr>
									<td><small>{{ $payroll->employee->employee_id }}</small></td>
			        				<td><small>{{ $payroll->employee->full_name }}</small></td>
			        				<td><small>{{ number_format($payroll->items->basic_salary, 2) }}</small></td>
			        				<td><small>{{ number_format($payroll->items->basic_salary / 2, 2)}}</small></td>
			        				<td class='text-red'><small>{{ number_format($payroll->items->total_late_and_absent, 2) }}</small></td>
			        				<td><small>{{ number_format($payroll->items->admin_pay, 2) }}</small></td>
			        				<td><small>{{ number_format($payroll->items->other_pay, 2) }}</small></td>
			        				<td><small>{{ number_format($payroll->items->gross_pay, 2) }}</small></td>
			        				@if($payroll->items->includes->sss) <td class='text-red'><small>{{ number_format($payroll->items->government_services->sss, 2) }}</small></td> @endif
			        				@if($payroll->items->includes->sss_loan) <td class='text-red'><small>{{ number_format($payroll->items->government_services->loans->sss, 2) }}</small></td> @endif
			        				@if($payroll->items->includes->philhealth) <td class='text-red'><small>{{ number_format($payroll->items->government_services->philhealth, 2) }}</small></td> @endif
			        				@if($payroll->items->includes->hdmf) <td class='text-red'><small>{{ number_format($payroll->items->government_services->hdmf, 2) }}</small></td> @endif
			        				@if($payroll->items->includes->hdmf_loan) <td class='text-red'><small>{{ number_format($payroll->items->government_services->loans->hdmf, 2) }}</small></td> @endif
			        				@if($payroll->items->includes->tax) 
			        					<td><small>{{ number_format($payroll->items->government_services->taxable_income, 2) }}</small></td>
			        					<td><small>{{ number_format($payroll->items->government_services->tax, 2) }}</small></td>
		        					@endif
			        				<td><small>{{ number_format($payroll->items->total_adjustment, 2)}}</small></td>
			        				<td><small>{{ number_format($payroll->items->net_pay, 2)}}</small></td>
								</tr>
							</tbody>
		        		</table>
				  	</div>

			  	</div><!-- /.box -->
	    </div><!-- /.box-body -->
	  </div><!-- /.box -->

	</div>
</div>
@endsection


@section('after_styles')
	<link rel="stylesheet" href="{{ asset('vendor/backpack/crud/css/crud.css') }}">
	<link rel="stylesheet" href="{{ asset('vendor/backpack/crud/css/show.css') }}">

	<style>
		.text-red {
			color: red;
		}
	</style>
@endsection

@section('after_scripts')
	<script src="{{ asset('vendor/backpack/crud/js/crud.js') }}"></script>
	<script src="{{ asset('vendor/backpack/crud/js/show.js') }}"></script>
@endsection
