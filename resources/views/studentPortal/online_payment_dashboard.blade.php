@extends('backpack::layout_student')

@section('after_styles')
  <style scoped>
    .padding-left-15 {
      padding-left: 15px;
    }
    .pad-top {
      margin-top: 5px;
      padding-top: 5px;
    }

    .control-labels{
        margin: 0px;
        padding: 0px;
    }
    .nav-pills>li {
      margin-top: 5px;
    }
    .nav-pills>li>a {
      border-radius: 10px;
    }
    .nav-pills>li.active>a {
      border-top-color: #007bff !important;
      color: #ffffff;
      background-color: #007bff !important;
    }
    .box-primary {
      border-top-color: #007bff !important;
    }

    .tab-content {
	    box-shadow:  none !important;
	}

	.form-control {
		border-radius: .25rem;
	}

	#form-container {
		margin-left: 33.333333%;
	}

	@media (max-width: 768px) {
	    #form-container {
			margin-left: 0;
		}
  	}
	  @media only screen and (min-width: 768px) {
          /* For desktop phones: */
        .oc-header-title {
          margin-top: 80px;
        }
        .content-wrapper{
            border-top-left-radius: 50px;
            }
        .sidebar-toggle{
          margin-left:30px;
        }
        .main-footer{
        border-bottom-left-radius: 50px;
        padding-left: 80px;
      }
    }
  </style>
@endsection

@section('content')
<body style="background: #3c8dbc;">
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
    	<div class="box">
	    	<div class="box-header text-center">
	    		<h3 style="color: #0e6ea6;">Enrollments Information and Balances</h3>
	    	</div>
	        <div class="box-body">
	        	@if( count($enrollments) > 0)
	        		@foreach($enrollments as $enrollment)
		        	<div class="col-md-4 col-sm-12" id="form-container">
		        		<div class="small-box bg-primary">
				            <div class="inner">
				              <p>
				                {{ $enrollment->school_year_name ?? '-' }}
				                <br> 
				                {{ $enrollment->department_name ?? '-' }}
				                <br>
				                {{ $enrollment->level_name }} {{ $enrollment->track_name ? '| ' . $enrollment->track_nam : '' }}
				                <br>
				                {{ $enrollment->term_type ? $enrollment->term_type . ' Term' : '-'  }}
				                <br>
				                Balance: <b>
					                     	<!-- Peso Sign (&#8369;) -->
					                     	&#8369; {{ number_format((float)$enrollment->remaining_balance, 2) }}
					                     </b>
				              </p>
				            </div>
				            <div class="icon">
				              <i class="fas fa-money"></i>
				            </div>
				            {{-- <a id="btnPay-{{ $enrollment->id }}" href="javascript:void(0)" class="small-box-footer" style="font-size: 16px;" data-id="{{ $enrollment->id }}" data-backdrop="static" data-keyboard="false" data-sy="{{ $enrollment->school_year_id }}" data-amount="{{ $enrollment->remaining_balance }}" data-toggle="modal" data-target="#paymentModal">
				              Online Payment <i class="fas fa-arrow-circle-right"></i>
				            </a> --}}
							@if( $enrollment->invoice_no )
								<a id="btnPay-{{ $enrollment->id }}" href="{{ url('student/online-payment/'.$enrollment->id) }}" class="small-box-footer" style="font-size: 16px;">
									Online Payment <i class="fas fa-arrow-circle-right"></i>
							  	</a>
							@endif
			          	</div>
		        	</div>
	        		@endforeach
	        		<!--TOTAL BALANCE -->
	        		@php $total_balance = $enrollments->sum('remaining_balance'); @endphp
	        		@if($total_balance > 0)
	        		<div class="col-md-4 col-sm-12" id="form-container">
	        			<h3>
	        				Total Balance: <b style="{{ $total_balance > 0 ? 'color: red;' : '' }}">&#8369; {{ number_format((float)$total_balance, 2) }}</b>
	        			</h3>
	        		</div>
	        		@endif
	        	@endif
	        </div>
	    </div>
    </div>
</body>
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
	<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
  	<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap.min.js"></script>
@endpush
