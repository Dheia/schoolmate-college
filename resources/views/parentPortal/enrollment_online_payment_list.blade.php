@extends('backpack::layout_parent')

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

	@media only screen and (min-width: 768px) {
        .jconfirm-holder {
            padding-top: 50px !important;
        }
    }

    .w-200
    {
        max-width: 700px !important;
        margin-left: auto;
        margin-right: auto;
    }
    .jconfirm-box-container {
        max-width: 700px !important;
        margin-left: auto;
        margin-right: auto;
    }

    .jconfirm .jconfirm-cell {
        vertical-align: baseline !important;
    }

    .jconfirm-title-c {
        text-align: center !important;
    }

    .jconfirm-title {
        padding: 0 20px !important;
    }

    .jconfirm-buttons {
        padding: 0 20px 20px 20px !important;
    }

    .form-control {
        display: block !important;
        width: 100% !important;
        padding: 0.375rem 0.75rem !important;
        font-size: 1rem !important;
        line-height: 1.5 !important;
        color: #495057 !important;
        background-color: #fff !important;
        background-clip: padding-box !important;
        border: 1px solid #ced4da !important;
        border-radius: 0.25rem !important;
        transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out !important;
        margin-bottom: 0 !important;
    }

    .has_error {
        border: 1px solid red!important;
    }

    .error {
        color: red;
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
			    <li><a href="{{ url( '/parent/dashboard') }}">Dashboard</a></li>
			    <li><a href="{{ url( '/parent/student-enrollments' . $student->studentnumber) }}">Enrollments</a></li>
			    <li class="active">Online Payment</li>
			  </ol>
			</section>
    	</div>
    </div>
    <!-- HEADER END -->

	<div class="container-fluid" id="Content" style="display: none;">

        <!-- STUDENT INFO -->
        <div class="col-md-12 m-r-15">
            <div class="box">
                <div class="box-body">
                    <div class="col-md-3 col-xs-5" style="padding-right: 0;">
                        <h5><b>Student ID:</b></h5>
                    </div>
                    <div class="col-md-3 col-xs-7" style="padding-left: 0;">
                        <h5>{{ $student->studentnumber }}</h5>
                    </div>
                    <div class="col-md-3 col-xs-5" style="padding-right: 0;">
                        <h5><b>Fullname:</b></h5>
                    </div>
                    <div class="col-md-3 col-xs-7" style="padding-left: 0;">
                        <h5>{{ $student->fullname }}</h5>
                    </div>
                </div>
            </div>
        </div>
        <!-- END OF STUDENT INFO -->

		<!-- ENROLLMENT INFORMATION -->
		<div class="col-md-3">
			<div class="box">
				<div class="box-header text-center">
					<h4 style="color: #0e6ea6;">Enrollment Information</h4>
				</div>
				<div class="box-body text-center">
					<h4>
						{{ $enrollment->school_year_name ?? '-' }}
					</h4>
					<h4> 
				        {{ $enrollment->department_name ?? '-' }}
				    </h4>
					<h4> 
						{{ $enrollment->level_name }} {{ $enrollment->track_name ? '| ' . $enrollment->track_name : '' }}
					</h4>
				    <h4> 
						{{ $enrollment->term_type ? $enrollment->term_type . ' Term' : '-'  }}
				    </h4>
				</div>
				<div class="box-footer text-center">
					<h4>
						Balance: <b style="{{ $enrollment->remaining_balance > 0 ? 'color: red;' : '' }}">
						<!-- Peso Sign (&#8369;) -->
						&#8369; {{ number_format((float)$enrollment->remaining_balance, 2) }}
						</b>
					</h4>
				</div>
			</div>
		</div>
		<!-- ENROLLMENT INFORMATION END -->

		<!-- LIST OF PAYMENT METHOD -->
		<div class="col-md-9">
			<div class="box">
				<div class="box-header text-center">
					<h4 style="color: #0e6ea6;">Online Payments</h4>
				</div>
				<div class="box-body">
					<div class="col-md-12">

						<div class="table-responsive">
							<table class="table table-sm table-bordered" id="enrollments_table">
								<thead>
									<th>Date</th>
									<th>Time</th>
									<th>Amount</th>
									<th>Method</th>
									<th>Status</th>
									<th>Actions</th>
								</thead>
								<tbody>
									@foreach($paynamicsPayments->sortByDesc('timestamp') as $paynamicsPayment)
										<tr>
											<td>
												{{ \Carbon\Carbon::parse($paynamicsPayment->timestamp)->format('F d, Y') }}
											</td>
											<td>
												{{ \Carbon\Carbon::parse($paynamicsPayment->timestamp)->format('h:i A') }}
											</td>
											<td>
												<b>&#8369; {{ number_format((float)$paynamicsPayment->amount, 2) }}</b>
											</td>
											<td>
												{{ $paynamicsPayment->payment_method_name }}
											</td>
											<td>
												{{ $paynamicsPayment->response_message }}
											</td>
											<td>
												@if(is_array(json_decode($paynamicsPayment->direct_otc_info)))
													<a  href="{{ url('parent/online-payment/' . $paynamicsPayment->id . '/information') }}" class="btn btn-xs btn-primary" style="margin-bottom: 0 !important;">
														<i class="fas fa-arrow-circle-right"></i> Select
													</a>
												@else
													<a href="{{ $paynamicsPayment->direct_otc_info ?? $paynamicsPayment->payment_action_info }}" class="btn btn-xs btn-primary" style="margin-bottom: 0 !important;">
														<i class="fas fa-arrow-circle-right"></i> Select
													</a>
												@endif
											</td>
										</tr>
									@endforeach
								</tbody>
							</table>
						</div>

					</div>
				</div>
			</div>
		</div>
		<!-- LIST OF PAYMENT METHOD END -->

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

	<script>
		var status = 'ready';
		$(document).ready(function () {
			$("#Content").show();
		});	
	</script>
@endpush
