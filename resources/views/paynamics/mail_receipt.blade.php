<style type="text/css">
	* {
		box-sizing: border-box;
	}

	body {
		margin: 0;
		font-family: Arial, Helvetica, sans-serif;

	}

	.text-center {
		text-align: center;
	}

	.text-right {
		text-align: right;
	}

	.d-block {
		display: block;
	}
	.m-0 {
		margin: 0;
		margin-bottom: 4px;
	}

	.wrapper {
		height: 100%; 
		min-width: 600px;
		width: auto; 
		background: #c3ced1; 
		padding: 60px;
		overflow-y: auto;
	}

	.second-wrapper {
		height: auto; 
		width: 80%; 
		background: #FFF; 
		margin: auto;
		position: relative;
	}
	
	.header {
		color: #FFF;
	}

	.table {
		width: 100%;
		/*border: 1px solid #ccc;*/
		padding: 10px;
	}

	.table td {
		font-size: 13px;
		vertical-align: top;
	}

	.items {
		background: #f4f6f7;
		margin-top: 50px;
	}
	
	.items table td {
		font-size: 12px;
		padding-top: 3px;
		padding-bottom: 3px;
	}

	.body {
		background: #FFF;
	}

	.footer {
		/*position: absolute;*/
		background: #FFF;
		padding: 20px;
		width: 100%;
		}
</style>

<div class="wrapper">
	
	<div class="second-wrapper">
		
		<div class="header" style="padding: 20px; background: #156dcc">
			<img src="{{ env('APP_URL') . '/' . config('settings.schoollogo') }}" alt="school logo" width="100" style="display: block; margin: auto;">
			<h1 class="text-center m-0">{{ config('settings.schoolname') }}</h1>
			<p class="text-center m-0" style="font-size: 12px;">{{ config('settings.schooladdress') }}</p>
		</div>

		<div class="body" style="padding: 20px;">

			<!-- TRANSACTION MESSAGE (STATUS) -->
			<div class="items" style="padding: 20px; background: #f4f6f7; margin-top: 30px;">
				<table class="table" style=" width: 100% !important; padding: 10px; !important;">
					<tbody>
						<tr>
							<td class="text-center" style="text-align: center !important;
								padding-top: 3px !important;
								padding-bottom: 3px !important;">
									<b>{{ $payment->response_message }}</b>
							</td>
						</tr>
					</tbody>
				</table>
			</div>

			<table class="table">
				<tbody>
					<tr>
						<td style="width: 50%;">
							<p class="m-0"><b>{{ $payment->studentnumber }}</b></p> 
							<h3 class="m-0">{{ $payment->student->full_name }}</h3> 
							<!-- <p class="m-0" style="font-size: 11px; font-weight: 100;">
								{{ $payment->student->residentialaddress }}
							</p>  -->
						</td>
						<td  style="width: 50%;" class="text-right">
							<p>Date</p>
							<p><b>{{ \Carbon\Carbon::parse($payment->timestamp)->format('F d, Y'); }}</b></p>
						</td>
						<td  style="width: 50%;" class="text-right">
							<p>Time</p>
							<p><b>{{ \Carbon\Carbon::parse($payment->timestamp)->format('h:i A'); }}</b></p>
						</td>
						<td  style="width: 50%;" class="text-right">
							<p>Exipiry Limit</p>
							<p><b>{{ \Carbon\Carbon::parse($payment->expiry_limit)->format('F d, Y - h:i A'); }}</b></p>
						</td>
					</tr>
				</tbody>
			</table>

			<div class="items">
				<table class="table">
					<tbody>
						<tr>
							<td>Request ID</td>
							<td class="text-right"><b>{{ $payment->request_id }}</b></td>
						</tr>
						<tr>
							<td>Reference No.</td>
							<td class="text-right"><b>{{ $payment->pay_reference }}</b></td>
						</tr>
						<tr>
							<td>Email Recipient</td>
							<td class="text-right"><a href="#">{{ $payment->email }}</a></td>
						</tr>
						<tr>
							<td>Payment Method</td>
							<td class="text-right"><b>{{ $payment->paymentMethod->name }}</b></td>
						</tr>
						<tr>
							<td>Amount Paid</td>
							<td class="text-right"><b>PHP {{ number_format($payment->amount, 2) }}</b></td>
						</tr>
						<tr>
							<td>Convenience Fee</td>
							<td class="text-right"><b>PHP {{ number_format($payment->fee, 2) }}</b></td>
						</tr>
						<tr>
							<td colspan="2"><hr></td>
						</tr>
						<tr>
							<td>Total</td>
							<td class="text-right"><b>PHP {{ number_format($payment->amount + $payment->fee, 2) }}</b></td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>

		<div class="footer">
			<img src="{{ asset('images/smo_logo.png') }}" alt="" width="150" style="display: block; margin: auto;">
		</div>

	</div>

</div>