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
			<img src="{{ env('APP_URL') . '/' . $schoolLogo }}" alt="school logo" width="100" style="display: block; margin: auto;">
			<h1 class="text-center m-0">{{ $schoolName }}</h1>
			<p class="text-center m-0" style="font-size: 12px;">{{ $schoolAddress }}</p>
		</div>
		<h3 class="text-center">Payment Reminder</h3>
		<div class="body" style="padding: 20px;">
			<table class="table">
				<tbody>
					<tr>
						<td>
							<p class="m-0"><b>{{ $schoolAbbr . ' - ' . $enrollment->studentnumber }}</b></p> 
							<h3 class="m-0">{{ $enrollment->full_name }}</h3> 
						</td>
						<td class="text-right">
							<p style="margin-bottom: 5px;">Date</p>
							<p style="margin-top: 0;"><b>{{ date('F d, Y') }}</b></p>
						</td>
					</tr>

					<tr>
						<td>
							<p style="margin-bottom: 5px;">Grade Level</p>
							<p style="margin-top: 0;"><b>{{ $enrollment->level_name }} {{ $enrollment->track_name ? '- '.$enrollment->track_name : '' }}</b></p>
						</td>
						<td class="text-right">
							<p style="margin-bottom: 5px;">Term | School Year</p>
							<p style="margin-top: 0;"><b>{{ $enrollment->term_type ? $enrollment->term_type.' Term' : '' }} | {{ $enrollment->school_year_name }}</b></p>
						</td>
					</tr>
				</tbody>
			</table>

			<!-- Payment Reminder -->
			@if(count($payment_schemes)>0)
				<div class="items">
					<table class="table">
						<thead>
							<tr>
								<td><b>Due Date</b></td>
								<td class="text-right"><b>Amount</b></td>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td colspan="2"><hr></td>
							</tr>
							@foreach($payment_schemes as $payment_scheme)
								<tr>
									<td><b>{{ date('F d, Y', strtotime($payment_scheme->scheme_date)) }}</b></td>
									<td class="text-right"><b>PHP {{ number_format($payment_scheme->$payment_snake, 2) }}</b></td>
								</tr>
							@endforeach

							
							{{-- <tr>
								<td><b>Grand Total</b></td>
								<td class="text-right">
									<b>PHP {{ number_format($enrollment->total_tuition, 2) }}</b>
								</td>
							</tr> --}}
						</tbody>
					</table>
				</div>
			@endif
			<!-- Payment Reminder -->
			<br>
			{{-- <p>Note: </p> --}}

		</div>

		<div class="footer">
			<img src="{{ asset('images/smo_logo.png') }}" alt="" width="150" style="display: block; margin: auto;">
		</div>

	</div>

</div>