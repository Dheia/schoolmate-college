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
		<div class="col-md-4">
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
			@if( count($enrollment->paynamicsPayments) > 0 )
				<div class="small-box bg-primary">
					<div class="inner">
						<p>Online Payments</p>
					</div>
					<div class="icon">
						<i class="fas fa-money" aria-hidden="true"></i>
					</div>
					<a href="{{ url('parent/online-payment/' . $enrollment->id . '/list') }}" class="small-box-footer" style="font-size: 16px;">
						View <i class="fas fa-arrow-circle-right" aria-hidden="true"></i>
					</a>
				</div>
			@endif


		</div>
		<!-- ENROLLMENT INFORMATION END -->

		<!-- LIST OF PAYMENT METHOD -->
		<div class="col-md-8">
			<div class="box">
				<div class="box-header text-center">
					<h4 style="color: #0e6ea6;">Payment Methods</h4>
				</div>
				<div class="box-body">
					<div class="col-md-12">
						@if( count($paymentCategories) > 0)
							@foreach($paymentCategories as $paymentCategory)
								@if(count($paymentMethods->where('payment_method_category_id', $paymentCategory->id))>0)
									<!-- PAYMENT CATETGORY -->
									<div class="row">
										<h4 style="color:#FCA70B">
											{{$paymentCategory->name}}
										</h4>
									</div>
									<div class="row">
										<!-- PAYMENT METHOD -->
										@foreach ($paymentMethods->where('payment_method_category_id', $paymentCategory->id) as $paymentMethod)
											<div class="col-md-3 col-xs-6" style="padding-top: 25px;">
												<a href="javascript:void(0)" class="payment-gateway"
													id="btn-pm-{{ $paymentMethod->id }}"
													data-id="{{ $paymentMethod->id }}" 
													data-name="{{ $paymentMethod->name }}"
													disabled='false'>
													<img src="{{ asset($paymentMethod->logo) }}" 
													alt="{{ $paymentMethod->name }}" style="width: 100%;">
												</a>
											</div>
										@endforeach
									</div>
								@endif
							@endforeach
						@endif
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

		let add_fee, min_fee, fee, total_amount = 0;
		var pmethod_id  = null;
		var email       = null; 

		var has_error   = null;

		var email       = $('input[name=email]');
		var description = $('input[name=description]');
		var amount      = $('input[name=amount]');

		var form =  @include('parentPortal.payment_form');

		$(document).ready(function () {
			$("#Content").show();
			// Payment Methods On Click
            $('.payment-gateway').click(function(event){
                var data_id = $.parseJSON($(this).attr('data-id'));

                // Check Status For Double Clicking
                if(status === 'ready') {
                    paymentForm(data_id);
                } else {
                    return false;
                }
            });

			@if($errors->any())
                
                paymentForm({{old('payment_method_id')}});

                setTimeout(function (){
                    setOldValue();
                    getFee();
                    validateInput();
                }, 1000);
            @endif
		});	
	</script>

<script>
	// Show Payment Form
	function paymentForm(id)
	{
		$('input[name=payment_method_id]').val(pmethod_id);
		status = 'loading';

		// Get Payment Method Form Data
		$.ajax({
			url: '/student/api/payment-method/' + id + '/get',
			success: function (response) {
				pmethod_id = response.id;
				fee     = response.fee;
				add_fee = response.additional_fee;
				min_fee = response.minimum_fee;

				// Show Payment Form
				var payment_modal = $.confirm({

					title: '<img style="max-height: 100px; max-width: 400px;" src="/' + response.logo + '">',
					content: form,
					type: 'dark',
					columnClass: 'w-200',
					buttons: {
						confirm: {
							btnClass: 'btn-dark',
							action: function(){
								// Get Entered Data
								$('#loading').show();
								$('#paymentContainer').hide();
								has_error = null;
								validateInput();

								if(has_error === null) {
									return false;
								} else if(has_error === false) {
									$('#paymentForm').submit();
									$('#loading').show();
									$('#processing').show();
									$('.jconfirm-buttons').hide();
									$('#paymentContainer').hide();
									return false;
								} else {
									return false;
								}
								
							}
						},
						cancel: {
							btnClass: 'btn-red',
							action: function(){
								// $.alert('Cancelled!');
								status = 'ready';
							}
						},
					}

				});

				setTimeout(function (){
					// Assign Payment Method Id
					$('input[name=payment_method_id]').val(pmethod_id);
				}, 1000);
			},
			error: function (error) {
				console.log(error);

				$.alert({
					title: 'Warning',
					type: 'red',
					icon: 'fa fa-warning',
					content: 'Payment Not Available.',
				});
			}
		});

	}

	// Get Fee and Total Amount
	function getFee()
	{
		amount = $('input[name=amount]');

		fee     = typeof fee === "undefined" ? 0 : parseFloat(fee);
		add_fee = typeof add_fee === "undefined" ? 0 : parseFloat(add_fee);
		min_fee = typeof min_fee === "undefined" ? 0 : parseFloat(min_fee);

		let fee_percent = parseFloat(fee) * (12/100); //.27
		let total_tax   = parseFloat(fee) + parseFloat(fee_percent); // 2.25 + .27 = 2.52

		let total_with_tax = -(parseFloat(total_tax) - 100);
		let total_fee   = ((parseFloat(amount.val()) / parseFloat(total_with_tax/100)) - parseFloat(amount.val()));

		total_fee = typeof total_fee === "undefined" ? 0 : parseFloat(total_fee);

		
		total_fee = total_fee > min_fee 
			? total_fee 
			: ((parseFloat(min_fee) - parseFloat({{ env('TNH_MARKUP_FIXED') }})) * parseFloat(1.12)) + parseFloat({{ env('TNH_MARKUP_FIXED') }});
		total_with_fee = total_fee + parseFloat(amount.val());

		if(isNaN(total_with_fee)) {
			$('input[name=amount]').val( Intl.NumberFormat('en', {style: 'currency', currency: 'PHP'}).format( 0 ) );
			$('input[name=fee]').val( Intl.NumberFormat('en', {style: 'currency', currency: 'PHP'}).format( 0 ) );
			$('input[name=total_amount]').val( Intl.NumberFormat('en', {style: 'currency', currency: 'PHP'}).format( 0 ));
			return;
		}

		$('input[name=fee]').val( Intl.NumberFormat('en', {style: 'currency', currency: 'PHP'}).format( total_fee ) );
		$('input[name=total_amount]').val( Intl.NumberFormat('en', {style: 'currency', currency: 'PHP'}).format( total_with_fee ));
	}

	// Validate Inputs || Check If Input Data are Correct
	function validateInput()
	{
		has_error   = false;

		email       = $('input[name=email]');
		description = $('input[name=description]');
		amount      = $('input[name=amount]');

		// if(!address.val()) {
		//     address.addClass('has_error');
		//     $('#address').append('<div class="error">The address field is required.</div>');
		//     has_error = true;
		// }
		if(!email.val()) {
			email.addClass('has_error');
			$('#email').append('<div class="error">The email field is required.</div>');
			has_error = true;
		} else {
			if (!validateEmail(email.val())) {
				email.addClass('has_error');
				$('#email').append('<div class="error">The email must be a valid email address.</div>');
			}
		}

        if(!description.val()) {
			description.addClass('has_error');
			$('#description').append('<div class="error">The description field is required.</div>');
			has_error = true;
		}
		
		if(!amount.val()) {
			amount.addClass('has_error');
			$('#amount').append('<div class="error">The amount field is required.</div>');
			has_error = true;
		}

		// Assign Payment Method Id
		if($('input[name=payment_method_id]').val() === "undefined" || ! $('input[name=payment_method_id]').val()) {
			$('input[name=payment_method_id]').val(pmethod_id);
		}

		$('#loading').hide();
		$('#paymentContainer').show();

	}

	function validateEmail(email) {
		const re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
		return re.test(email);
	}

	function setOldValue()
	{
		enrollment  = $('input[name=enrollment_id]');
		schoolYear  = $('input[name=school_year_id]');
		email       = $('input[name=email]');
		description = $('input[name=description]');
		amount      = $('input[name=amount]');

		$('input[name=payment_method_id]').val('{{ old('payment_method_id') }}');
		$('input[name=enrollment]').val('{{ old('enrollment_id') }}');
		$('input[name=school_year_id]').val('{{ old('school_year_id') }}');
		$('input[name=email]').val('{{ old('email') }}');
		$('input[name=description]').val('{{ old('description') }}');
		$('input[name=amount]').val('{{ old('amount') }}');

		@if($errors->has('email'))
			email.addClass('has_error');
			$('#email').append('<div class="error">{{ $errors->first('email') }}</div>');
		@endif
		
		@if($errors->has('description'))
			description.addClass('has_error');
			$('#description').append('<div class="error">{{ $errors->first('description') }}</div>');
		@endif

		@if($errors->has('amount'))
			amount.addClass('has_error');
			$('#amount').append('<div class="error">{{ $errors->first('amount') }}</div>');
		@endif
	}
</script>
@endpush
