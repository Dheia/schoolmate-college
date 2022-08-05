@extends('paynamics.layout')

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

	{{-- <span class="text-center login100-form-title" style="padding-bottom: 20px;">Online Payment</span> --}}
	<div class="container">
		<div class="row">
			
			<div class="col-md-6 p-5 mt-5 box-shadow text-center mx-auto payment-info-container" style="background: #FFF; position: relative;">
		      	<i class="fa fa-check fa-5x" style="color: #FFF; background: #60c878; border-radius: 50%; padding: 10px; position: absolute; top: -55px; left: 50%; transform: translateX(-50%);"></i>
		       	<h3 class="mt-5"><b>{{ $data['title'] }}</b></h3>

		       	<div class="col-md-12 mt-5 p-5" style="background: #f8f6f6;">
		          	<div class=" mx-auto profile-card" style="display: table;">
			            <div class="p-1" style="display: table-cell;">
			              	<img src="{{ asset('images/headshot-default.png') }}" alt="" width="80" class="img-fluid box-shadow" style="border-radius: 50%; box-shadow: 3px 3px 3px #ccc;">
			            </div>
			            <div class="p-1" style="display: table-cell; vertical-align: middle;">
			              	<h4 class="text-left">{{ $payment->student->full_name }}</h4>
			              	<p class="text-left" style="font-size: 1.2rem;">{{ $payment->student->studentnumber }}</p>
			            </div>
		          	</div>

		          	<div class="p-5">
		            	<p class="mb-0" style="font-size: 5em; font-weight: bold;"><span style="font-size: 3rem">PHP</span> {{ number_format($payment->amount + $payment->fee, 2) }}</p>
		            	<p style="font-size: 1.5rem;">{{ $payment->description }}</p>
		          	</div>

		          	<div>
		            	<a href="{{ url('online-payment') }}" class="btn btn-primary">Make a payment again</a>
		          	</div>
		       	</div>
		    </div>

{{-- 
			<div class="col-md-12 p-t-100 p-b-100">
				<h1 class="text-center">{{ $data['title'] }}</h1>

				<a href="{{ url('online-payment') }}" class="btn btn-primary m-t-100 m-b-50" style="margin-left: 41%;">Make Payment Again</a>
			</div> --}}
		</div>
	</div>

@endsection

@section('after_scripts')
	<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
	<script type="text/javascript">
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
						_token: "{{ csrf_token() }}",
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