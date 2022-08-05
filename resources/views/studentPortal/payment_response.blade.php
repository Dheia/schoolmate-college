@extends('backpack::layout_student')

@section('after_styles')
  <style scoped>
  	pre {
  		overflow: hidden; 
  		white-space: break-spaces; 
  		word-break: break-word;
  	}

  	hr {
  		border-top: 1px solid #ccc;
  	}

  	.payment_info {
  		background-color: #f5f5f5; 
  		border: 1px solid #ccc; 
  		border-radius: 4px;
  	}
  </style>
@endsection

@section('content')
	<!-- HEADER START -->
  	<div class="row" style="padding: 15px;">
    	<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 smo-search-group"> 
			<section class="content-header">
			  <ol class="breadcrumb">
			    <li><a href="{{ url( '/student/dashboard') }}">Student</a></li>
			    <li class="active">Online Payment</li>
			  </ol>
			</section>
    	</div>
    </div>
    <!-- HEADER END -->

    <div class="row p-l-10 p-r-10">
		
        <div class="col-md-12">
            <!-- TRANSACTION MESSAGE (STATUS) START -->
            <div class="box">
				<div class="box-body">
					<h5 class="text-center">
						<b>{{ $paynamics_payment->response_message }}</b>
					</h5>
                    <h5 class="text-center">
                        Kindly check your inbox or spam folder in your email for additional copy
                    </h5>
				</div>
			</div>
            <!-- TRANSACTION MESSAGE (STATUS) END -->

            <!-- PAYMENT INFORMATION START -->
            <div class="box">
		        <div class="box-body">
		        	<div class="container-fluid payment_info">
		        		<h5 class="text-center">
	        				<b>{{ $payment_method ? $payment_method->name : '-' }}</b>
	        			</h5>
	        			<hr>
		        		<h5 class="text-center">
	        				<b>{{ $paynamics_payment->pay_reference }}</b>
	        				<br>
	        				{{'Reference Number'}}
	        			</h5>
		        		<h5 class="text-center">
	        				<b>{{ \Carbon\Carbon::parse($paynamics_payment->expiry_limit)->format('F d, Y - h:i A') }}</b>
	        				<br>
	        				{{'Expiry Limit'}}
	        			</h5>
	        			<h5 class="text-center">
	        				<b>PHP {{ $amount }}</b>
	        				<br>
	        				{{'Amount'}}
	        			</h5>
		        	</div>
		        </div>
		    </div>
            <!-- PAYMENT INFORMATION END -->
		</div>

    </div>
@endsection

@section('after_styles')
  <link href="https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap.min.css" rel="stylesheet" type="text/css" />
  <link rel="stylesheet" href="https://cdn.datatables.net/fixedheader/3.1.5/css/fixedHeader.dataTables.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.1/css/responsive.bootstrap.min.css">

  <link rel="stylesheet" href="{{ asset('vendor/backpack/crud/css/crud.css') }}">
  <link rel="stylesheet" href="{{ asset('vendor/backpack/crud/css/form.css') }}">
  <link rel="stylesheet" href="{{ asset('vendor/backpack/crud/css/list.css') }}">
@endsection

@push('after_scripts')

@endpush