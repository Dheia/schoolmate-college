@extends('backpack::layout')

@section('header')
	<section class="content-header">
	  <h1>
      <span class="text-capitalize">{!! $crud->getHeading() ?? $crud->entity_name_plural !!}</span>
      <small id="datatable_info_stack">{!! $crud->getSubheading() ?? trans('backpack::crud.all').'<span>'.$crud->entity_name_plural.'</span> '.trans('backpack::crud.in_the_database') !!}.</small>
	  </h1>
	  <ol class="breadcrumb">
	    <li><a href="{{ url(config('backpack.base.route_prefix'), 'dashboard') }}">{{ trans('backpack::crud.admin') }}</a></li>
	    <li><a href="{{ url($crud->route) }}" class="text-capitalize">{{ $crud->entity_name_plural }}</a></li>
	    <li class="active">{{ trans('backpack::crud.list') }}</li>
	  </ol>
	</section>
@endsection

@section('content')
<!-- Default box -->
 {{-- {!! dd(backpack_auth()->user()) !!} --}}

<div class="">
   
    <!-- THE ACTUAL CONTENT -->
    <div class="col-md-8">
        <div class="box m-b-50">

			<h1 class="text-center">
				<i class="fa fa-money fa-3x text-center"></i>
			</h1>

			<h2 class="text-center">NEXT RUN PAYROLL</h2>
			{{-- <h3 class="text-center">{{ Config::get('settings.firstcutoff') }} - {{ Config::get('settings.secondcutoff') }}</h3> --}}

			<form class="col-md-8 col-md-offset-2" action="{{ url($crud->route . '/run/report') }}" method="POST">
				{{ csrf_field() }}
				<div class="form-group col-md-6">
					<label for="from">From</label>
					<input type="date" name="from" id="from" class="form-control" required>
				</div>
				<div class="form-group col-md-6">
					<label for="from">To</label>
					<input type="date" name="to" id="to" class="form-control" required>
				</div>

				<div class="col-md-6">
					<div style="padding: 0;" class="col-md-12">
						<label for="includes">Included: </label><br>
						<div class="checkbox" style="display: inline !important; padding-right: 15px;">
					    	<label for="tax"><input type="checkbox" name="tax" id="tax">Tax</label>
						</div>
						<div class="radio tax-radio" style="margin-bottom: 30px;">
					    	<label class="radio-inline"><input type="radio" name="tax_type" value="full" checked>Full Deduction</label>
							<label class="radio-inline"><input type="radio" name="tax_type" value="half">Half Deduction</label>
						</div>
					</div>
					<div style="padding: 0;" class="col-md-12 includes">
						<div class="checkbox" style="display: inline !important; padding-right: 15px;">
					    	<label for="sss"><input type="checkbox" name="sss" id="sss">SSS</label>
						</div>
						<div class="radio sss-radio" style="margin-bottom: 30px;">
					    	<label class="radio-inline"><input type="radio" name="sss_type" value="full" checked>Full Deduction</label>
							<label class="radio-inline"><input type="radio" name="sss_type" value="half">Half Deduction</label>
						</div>
					</div>
					<div style="padding: 0;" class="col-md-12">
						<div class="checkbox" style="display: inline !important; padding-right: 15px;">
					    	<label for="philhealth"><input type="checkbox" name="philhealth" id="philhealth">Philhealth</label>
						</div>
						<div class="radio philhealth-radio" style="margin-bottom: 30px;">
					    	<label class="radio-inline"><input type="radio" name="philhealth_type" value="full" checked>Full Deduction</label>
							<label class="radio-inline"><input type="radio" name="philhealth_type" value="half">Half Deduction</label>
						</div>
					</div>
					<div style="padding: 0;" class="col-md-12">
						<div class="checkbox" style="display: inline !important; padding-right: 15px;">
					    	<label for="hdmf"><input type="checkbox" name="hdmf" id="hdmf">HDMF</label>
						</div>
						<div class="radio hdmf-radio" style="margin-bottom: 30px;">
					    	<label class="radio-inline"><input type="radio" name="hdmf_type" value="full" checked>Full Deduction</label>
							<label class="radio-inline"><input type="radio" name="hdmf_type" value="half">Half Deduction</label>
						</div>
					</div>
				</div>

				{{-- LOANS --}}
				<div class="col-md-6">
					<div style="padding: 0;" class="col-md-12 m-t-10">
						<label for="loans">Loans: </label><br>
						<div class="checkbox" style="display: inline !important; padding-right: 15px;">
					    	<label for="sss-loan"><input type="checkbox" name="sss_loan" id="sss-loan">SSS</label>
						</div>
						<div class="radio sss-loan-radio" style="margin-bottom: 30px;">
					    	<label class="radio-inline"><input type="radio" name="sss_loan_type" value="full" checked>Full Deduction</label>
							<label class="radio-inline"><input type="radio" name="sss_loan_type" value="half">Half Deduction</label>
						</div>
					</div>
					<div style="padding: 0;" class="col-md-12">
						<div class="checkbox" style="display: inline !important; padding-right: 15px;">
					    	<label for="hdmf-loan"><input type="checkbox" name="hdmf_loan" id="hdmf-loan">HDMF</label>
						</div>
						<div class="radio hdmf-loan-radio" style="margin-bottom: 30px;">
					    	<label class="radio-inline"><input type="radio" name="hdmf_loan_type" value="full" checked>Full Deduction</label>
							<label class="radio-inline"><input type="radio" name="hdmf_loan_type" value="half">Half Deduction</label>
						</div>
					</div>
				</div>

				<div class="col-md-12 m-t-20">
					<button class="btn btn-primary" style="display: block; margin: auto;">RUN PAYROLL</button>
				</div>
			</form>
			<div class="clearfix"></div>

			<br>

    	</div>

  	</div>

  	<div class="col-md-4">
  		<div class="box m-b-50">

		<div class="panel panel-default">
			
			<!-- Default panel contents -->
			<div class="panel-heading"><b>SIGNIFICANT DATE</b></div>
			<div class="panel-body">
				@if(count($crud->data['holidays']) > 0)
					@foreach($crud->data['holidays'] as $holiday)
					<div class="list-group">
					  	<a href="#" class="list-group-item">
					    	<h4 class="list-group-item-heading m-t-10">{{ $holiday->name }} &nbsp; <small>{{ $holiday->date->format('l | F d, Y') }}</small></h4>
					    	<p class="list-group-item-text">{{ str_limit($holiday->description, $limit = 150, $end = '...') }}</p>
					  	</a>
					</div>
					@endforeach
				@else
					<div>
						No Important Dates Found
					</div>
				@endif
			</div>
			
		</div>


	  	</div>
  	</div>
</div>

@endsection

@section('after_styles')
  <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.1/css/responsive.bootstrap.min.css">
  <!-- CRUD LIST CONTENT - crud_list_styles stack -->
  @stack('crud_list_styles')
@endsection

@section('after_scripts')

  <!-- CRUD LIST CONTENT - crud_list_scripts stack -->
  <script src="https://code.jquery.com/ui/1.11.3/jquery-ui.min.js" type="text/javascript"></script>

  @yield('custom_script')
  	<script>
  		function checkSSS () { $('#sss').prop('checked') ? $('.sss-radio').css('display', 'block') : $('.sss-radio').css('display', 'none'); }
  		checkSSS();
  		$('#sss').change(function () { checkSSS(); });

  		function checkPhilHealth () { $('#philhealth').prop('checked') ? $('.philhealth-radio').css('display', 'block') : $('.philhealth-radio').css('display', 'none'); }
  		checkPhilHealth();
  		$('#philhealth').change(function () { checkPhilHealth(); });

  		function checkHDMF () { $('#hdmf').prop('checked') ? $('.hdmf-radio').css('display', 'block') : $('.hdmf-radio').css('display', 'none'); }
  		checkHDMF();
  		$('#hdmf').change(function () { checkHDMF(); });

  		function checkTax () { $('#tax').prop('checked') ? $('.tax-radio').css('display', 'block') : $('.tax-radio').css('display', 'none'); }
  		checkTax();
  		$('#tax').change(function () { checkTax(); });

  		// LOANS
  		function checkSSSLoan () { $('#sss-loan').prop('checked') ? $('.sss-loan-radio').css('display', 'block') : $('.sss-loan-radio').css('display', 'none'); }
  		checkSSSLoan();
  		$('#sss-loan').change(function () { checkSSSLoan(); });

  		function checkPhilhealthLoan () { $('#philhealth-loan').prop('checked') ? $('.philhealth-loan-radio').css('display', 'block') : $('.philhealth-loan-radio').css('display', 'none'); }
  		checkPhilhealthLoan();
  		$('#philhealth-loan').change(function () { checkPhilhealthLoan(); });

  		function checkHDMFLoan () { $('#hdmf-loan').prop('checked') ? $('.hdmf-loan-radio').css('display', 'block') : $('.hdmf-loan-radio').css('display', 'none'); }
  		checkHDMFLoan();
  		$('#hdmf-loan').change(function () { checkHDMFLoan(); });
  	</script>
  @stack('crud_list_scripts')
@endsection

