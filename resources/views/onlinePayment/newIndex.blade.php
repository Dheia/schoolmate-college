@extends('onlinePayment.new_layout')

@section('after_styles')
	<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />

	<style type="text/css">
		.fee {
			color: #525454;
			font-size: 12px;
			margin-top: 5px;
			display: block;
			margin-right: 10px;
			padding-bottom: 10px;
		}

		/* Chrome, Safari, Edge, Opera */
		input::-webkit-outer-spin-button,
		input::-webkit-inner-spin-button {
		  -webkit-appearance: none;
		  margin: 0;
		}

		/* Firefox */
		input[type=number] {
		  -moz-appearance: textfield;
		}
	</style>

@endsection

@section('content')

	<span class="text-center login100-form-title" style="padding-bottom: 20px;">Online Payment</span>
	<div class="container">
		<div class="row">
			
			<div class="col-md-4 col-sm-12 offset-md-4">
				@if($errors->any())
				    <div class="alert alert-danger" role="alert">
				        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
				            <span aria-hidden="true">×</span>
				        </button>

				        @foreach($errors->all() as $error)
				            {{ $error }}<br/>
				        @endforeach
				    </div>
				@endif

				<form class="paymentForm" role="form" method="POST" action="{{ url()->current() }}" style="margin: auto; <!--width: 350px !important; -->">
					@csrf
					<span class="help-block text-center p-b-12 message-top" style="color: #c58e20; font-size: 12px; display: block;">
			        </span>
			         
					<div class="form-group">
						<select class="form-control" style="outline: none;" name="school_year_id" id="schoolYear">
							<option selected disabled>Select School Year</option>
							@php $flag = false; @endphp
							@foreach($schoolYears as $schoolYear)
								<option value="{{ $schoolYear->id }}" {{ $schoolYear->isActive ? 'selected' : '' }}>{{ $schoolYear->schoolYear }}</option>
							@endforeach
			            </select>
					</div>
					<div class="form-group">
						<input class="form-control" type="number" name="studentnumber" value="{{ old('studentnumber') }}" placeholder="Student Number" autocomplete="off">
					</div>
					<div class="form-group">
						<input class="form-control" type="number" step="any" name="amount" value="{{ old('amount') }}" placeholder="Amount" autocomplete="off">
						<span class="fee text-right">Fee: <span class="amount-fee">0</span></span>
					</div>
					<div class="form-group">
						<input class="form-control" type="text" name="email" value="{{ old('email') }}" placeholder="E-mail">
					</div>
					<div class="form-group">
						<textarea class="form-control" type="text" name="description" placeholder="Description">{{ old('description') }}</textarea>
					</div>
					<div class="form-group" style="margin-bottom: 0;">
						<select class="payment-method form-control" style="outline: none;" name="payment_method_id" id="schoolYear">
							@if( (env('PAYMENT_GATEWAY') !== null || env('PAYMENT_GATEWAY') !== "") && strtolower(env('PAYMENT_GATEWAY')) == "paynamics")
								<option selected disabled>Select Payment Method</option>
								@foreach($paymentMethods as $paymentMethod)
									@if(strtolower($paymentMethod->name) === "cash")
									@else
										<option value="{{ $paymentMethod->id }}" {{ $paymentMethod->id == old("payment_method_id") ? 'selected' : '' }} 
												fee="{{ $paymentMethod->fee }}" 
												fixed-amount="{{ $paymentMethod->fixed_amount }}">
												{{ $paymentMethod->name }}
										</option>
									@endif
								@endforeach
							@else
								@foreach($paymentMethods as $paymentMethod)
									@if(strtolower($paymentMethod->name) === "paypal")
										<option value="{{ $paymentMethod->id }}">{{ $paymentMethod->name }}</option>
									@endif
								@endforeach
							@endif
			            </select>
					</div>
					@if(config('settings.paymentnotes') !== '')
						<span style="color: orange; font-size: 11px;">NOTE: 
							<span>{{ config('settings.paymentnotes') }}</span>
						</span>
					@endif

					<div class="container-login100-form-btn">
			            <button class="btn btn-primary btn-block" style="z-index: 99;">
			            	Make Payment
			            </button>
			        </div>
		        </form>
			</div>
		</div>
	</div>

@endsection

@section('after_scripts')
	<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
	<script type="text/javascript">

	    $.ajaxSetup({
	        headers: {
	            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	        }
	    });


		// $('.payment-method').select2({
		// 	theme: "classic"
		// });
		function delay(callback, ms) {
			var timer = 0;
			return function() {
				var context = this, args = arguments;
				clearTimeout(timer);
					timer = setTimeout(function () {
					callback.apply(context, args);
				}, ms || 0);
			};
		}

		$('#schoolYear').change(function ( ) { 
			$('input[name="studentnumber"]').val("");
			$('.message-top').text("");
			getFee();
		});

		// Example usage:
		function getStudent () {
			var studentnumber = $('input[name="studentnumber"]').val();

			if(studentnumber !== "") {
				$.ajax({
					url: '{{ url()->current() }}/student/' + studentnumber + '/tuition',
					type: 'post',
					data: {
						school_year_id: $('#schoolYear').val()  
					},
					success: function (response) {
						if(!response.error) {
							$('.message-top').html("<strong>" + response.data.full_name + "</strong><br><strong>Remaining Balance: " + Intl.NumberFormat('en', {style: 'currency' , currency: 'PHP'}).format(response.data.remaining_balance) + "</strong>")
						} else {
							$('.message-top').html('<strong>' + response.message + '</strong>');
						}
					}
				});
			} else {
				$('input[name="studentnumber"]').val("");
				$('.message-top').text("");
			}
		}

		$('input[name="studentnumber"]').keyup(delay(function (e) {
			getStudent();
		}, 800));

		getStudent();

		function getFee () {
			var amount = $('input[name="amount"]');
			var paymentMethod = $('select[name="payment_method_id"]');

			var fee = paymentMethod.find('option:selected').attr('fee');
			var fixedAmount = paymentMethod.find('option:selected').attr('fixed-amount');

			fee = typeof fee === "undefined" ? 0 : parseFloat(fee);
			fixedAmount = typeof fixedAmount === "undefined" ? 0 : parseFloat(fixedAmount);

			if(isNaN(amount.val())) {
				$('.amount-fee').text( Intl.NumberFormat('en', {style: 'currency', currency: 'PHP'}).format(fixedAmount));
				return;
			}

			$('.amount-fee').text( Intl.NumberFormat('en', {style: 'currency', currency: 'PHP'}).format(  ( amount.val() * (fee/100) ) +  fixedAmount) );
		}

		getFee();

		$('input[name="amount"]').keyup(function () { getFee(); });
		$('select[name="payment_method_id"]').change(function () { getFee(); });

	</script>

@endsection