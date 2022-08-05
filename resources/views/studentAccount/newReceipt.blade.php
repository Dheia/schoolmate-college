<html >
	<head>
        <title>{{Config::get('settings.schoolname')}} | Receipt</title>
        <meta name="viewport" content="width=device-width">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>


        <style>
            @include('bootstrap4')
        </style>
        <style type="text/css">
			html{
				background-color: #eee;
				margin:0 auto;
				text-align: center;"
			}

			.page-letter-size{
				width:816px;
				height: 1056px;
				background-color: #fff;
				align-self: center;
				margin:0 auto;"
			}

			.page-margin{
				padding: 36px;
				padding-top: 36px;

			}

			.logo > img{
				width: 45px;
				padding: 5px;

			}

			.header-table{
				width: 100%;

			}

			.header-text{
				/*padding-left: 10px; */
			}
			.header-text > h5{
				font-size: 15px;
				line-height: 15px;
				margin: 0;
			}

			.header-text > p{
				font-size: 9px;
				line-height: 13px;
				margin: 0;
			}

			.receipt-content {
			    border: 1px solid #3c8dbc;
                border-radius: 2px;
                margin-bottom: 10px;
                padding: 0px;
			}

			.receipt-content tbody {
			    font-size: 9px;
			}

			.receipt-content tr td{
				border: none !important;
				/*border-color: #fff;*/
			}

			.signatures tr {
				border: none !important;
				font-size: 9px;
				padding-top: 0px !important;
                padding-bottom: 0px !important;
                margin-top: 0px !important;
                margin-bottom: 0px !important;
			}

			.signatures td {
				border: none !important;
				font-size: 9px;
				padding-top: 0px !important;
                padding-bottom: 0px !important;
                margin-top: 0px !important;
                margin-bottom: 0px !important;
			}

			table thead tr th {
				font-size: 9px;
			}
			
			.pariculars {
				width: 75%;
			}

			.amount {
				width: 25%;
			}
			
			.payment-for {
				padding-left: 40px;
			}

			.pt0 {
				padding-top: 0px !important;
			}

			.pb0 {
				padding-bottom: 0px !important;
			}
		</style>
    </head>
	<body class="page-letter-size" >
		
		<div class="page-margin">
			<br>
			{{-- CASHIER'S COPY --}}
			<section>
				<!-- HEADER -->
				<table class="header-table">
					<tr>
						<td class="td-logo" style="width:60px;">
							<div class="logo">
								<img src="{{ Config::get('settings.schoollogo') }}">
							</div>
						</td>
						<td class="td-text">
							<div  class="header-text">
								<h5 class="mt-4 text-uppercase">{{ Config::get('settings.schoolname') }}</h5>
								<p class="mb-0">{{ Config::get('settings.schooladdress') }}</p>
								<p class="mb-0">{{ Config::get('settings.schoolcontactnumber') }}</p>
								{{-- <p class="mb-0">TIN No. 203-910-363 - Non-Vat</p> --}}
							</div>
						</td>
						<td class="td-receipt pt-0" style="padding-top: 0 !important;">
							<div class="receipt-no" style="padding-top: 0 !important;">
								<p class="text-right mt-0 mb-0" style="padding-top: 0 !important;">No. {{$receipt_no}}</p>
								<small class="text-right mt-0 mb-0" style="float: right !important;">
									{{ \Carbon\Carbon::parse($payment_history->date_received ? $payment_history->date_received : $payment_history->created_at)
										->format('F d, Y') }}
								</small>
							</div>
						</td>
					</tr>
				</table>
				
				
				<div class="col-md-12 p-0 pt-4 text-center">
					<div class="row info-text pt-4 pb-2 text-center">
						<div class="col-12 text-center">
							<p class="text-center mb-0"><b>OFFICIAL RECEIPT</b></p>
						</div>
					</div>
				</div>
				
				<!-- Receipt Content -->
				<div class="receipt-content">
					<table class="table" style="border-top: 1px solid #3c8dbc;font-size:12px !important" border="0">
						<thead style="background-color: rgba(60,141,188,0.1); border-bottom: 0.5px solid #3c8dbc;">
                            <tr>
                                <th style="padding-left: 20px;font-size:10px !important" class="" class="text-uppercase">
                                	PARTICULARS
                                </th>
                                <th></th>
                                <th></th>
                                <th style="padding-left: 20px;font-size:10px !important" class="" class="text-uppercase">
                                	AMOUNT
                                </th>
                            </tr>
                        </thead>
						<tbody style="border-top: 0.5px solid #3c8dbc;">
							<tr>
								<td class="payment-for" style="padding-bottom: 0px; padding-left:40px;" colspan="4">
									<strong>Payment For:</strong>
								</td>
							</tr>
							<tr>
								<td colspan="3" style="padding-top: 0px; padding-bottom: 0px; padding-left:60px;">
									{{ ($payment_history->payment_for) }}
								</td>
								<td colspan="1" style="padding-top: 0px; padding-bottom: 0px; padding-right: 40px;">
									<p style="float: left; padding-left: 8px !important;">Php</p>
									<p style="float: right;">{{ number_format($payment_history->amount, 2)}}</p>
								</td>
							</tr>
							<tr>
								<td colspan="3"></td>
								<td colspan="1">
									<p style="border-top: 0.5px solid; width: 83%; padding: 0;">
										<p style="padding-left: -10px; float: left; font-size: 12px; font-weight: bolder;">
											Php
										</p>
										<p style="float: right; padding-right: 0px; font-size: 12px; font-weight: bolder;">
											{{ number_format($payment_history->amount, 2)}}
										</p>
									</p>
								</td>
							</tr>
							<tr>
								<br>
								<td colspan="4" class="received-from" style="padding-bottom: 0px; padding-left:40px;" colspan="2">
									<strong>Received From:</strong>
								</td>
							</tr>
							<tr>
								{{-- Fullname --}}
								<td colspan="3" style="padding-top: 0px; padding-bottom: 0px; padding-left:60px;">
									{{$payment_history->enrollment->student->lastname}}, {{$payment_history->enrollment->student->firstname}} {{$payment_history->enrollment->student->middlename}}
								</td>
								<td style="padding-top: 0px; padding-bottom: 0px;"></td>
							</tr>
							<tr>
								{{-- Grade and Section --}}
								<td colspan="3" class="font-italic" style="padding-top: 0px; padding-bottom: 0px; padding-left:60px;">
									{{ $payment_history->enrollment->level_name }}
									{{ $payment_history->track_name ? ' - ' . $payment_history->track_name : '' }}
									{{ $payment_history->student_section ? ' - ' . $payment_history->student_section->section->name : '' }}								</td>
								<td style="padding-top: 0px; padding-bottom: 0px;"></td>
							</tr>
							<tr>
								{{-- SchoolYear --}}
								<td colspan="3" class="font-italic" style="padding-top: 0px; padding-bottom: 0px; padding-left:60px;">
									SY {{ $payment_history->enrollment->school_year_name }}
								</td>
								<td style="padding-top: 0px; padding-bottom: 0px;"></td>
							</tr>

							<tr>
								{{-- <td colspan="1" style="padding-bottom: 0px; padding-left:40px;">
									<strong>Date Received</strong>
								</td> --}}
								<td colspan="1" style="padding-bottom: 0px; padding-left:40px;">
									<strong>Payment Method</strong>
								</td>
								{{-- <td colspan="1" style="padding-bottom: 0px; padding-left:40px;">
									<strong>Reference No.</strong>
								</td> --}}
								<td style="padding-bottom: 0px; padding-left:40px;"></td>
							</tr>

							<tr>
								{{-- <td colspan="1" style="padding-top: 0px; padding-bottom: 0px; padding-left:40px;">
									{{ date("m/d/Y", 
										strtotime($payment_history->date_received ? $payment_history->date_received : $payment_history->created_at)) 
									}}
								</td> --}}
								<td colspan="1" style="padding-top: 0px; padding-bottom: 0px; padding-left:40px;">
									{{ $payment_history->paymentMethod->name }}
								</td>
								{{-- <td colspan="1" style="padding-top: 0px; padding-bottom: 0px; padding-left:40px;">
									000000
								</td> --}}
								<td style=" padding-top: 0px;padding-bottom: 0px; padding-left:40px;"></td>
							</tr>
						</tbody>
					</table>
				</div>
				<!-- End for Receipt Content -->
				<div class="signatures">
					<table class="table" style="border: none;">
						<tbody>
							<tr style="padding-bottom: 0px !important;">
								<td style="width: 25%;"><strong>Payment Verified by:</strong></td>
								<td style="width: 25%;"><strong>Payment Received From:</strong></td>
								<td style="width: 25%;"><strong>Date Processed:</strong></td>
								<td style="width: 25%; font-size: 14px;"><small><strong>CASHIER'S COPY</strong></small></td>
							</tr>
							<tr>
								<td>
									<p style="border-bottom: 0.5px solid; width: 83%;">
										{{ $payment_history->user->full_name }}
									</p>
								</td>
								<td>
									<p style="border-bottom: 0.5px solid; width: 83%;">
										{{ $payment_history->enrollment->full_name }}
									</p>
								</td>
								<td>
									<p style="border-bottom: 0.5px solid; width: 83%;">

									{{ date("m/d/Y") }}
								</p>
								</td>
								<td></td>
							</tr>
						</tbody>
					</table>
				</div>

		  	</section>
			
			<br>
		  	<hr style="border-bottom: 1px dashed #333">
		  	<br>
		  	<br>
			
			{{-- STUDENT'S COPY --}}
			<section>
				<!-- HEADER -->
				<table class="header-table">
					<tr>
						<td class="td-logo" style="width:60px;">
							<div class="logo">
								<img src="{{ Config::get('settings.schoollogo') }}">
							</div>
						</td>
						<td class="td-text">
							<div  class="header-text">
								<h5 class="mt-4 text-uppercase">{{ Config::get('settings.schoolname') }}</h5>
								<p class="mb-0">{{ Config::get('settings.schooladdress') }}</p>
								<p class="mb-0">{{ Config::get('settings.schoolcontactnumber') }}</p>
								{{-- <p class="mb-0">TIN No. 203-910-363 - Non-Vat</p> --}}
							</div>
						</td>
						<td class="td-receipt pt-0" style="padding-top: 0 !important;">
							<div class="receipt-no" style="padding-top: 0 !important;">
								<p class="text-right mt-0 mb-0" style="padding-top: 0 !important;">No. {{$receipt_no}}</p>
								<small class="text-right mt-0 mb-0" style="float: right !important;">
									{{ \Carbon\Carbon::parse($payment_history->date_received ? $payment_history->date_received : $payment_history->created_at)
										->format('F d, Y') }}
								</small>
							</div>
						</td>
					</tr>
				</table>
				
				
				<div class="col-md-12 p-0 pt-4 text-center">
					<div class="row info-text pt-4 pb-2 text-center">
						<div class="col-12 text-center">
							<p class="text-center mb-0"><b>OFFICIAL RECEIPT</b></p>
						</div>
					</div>
				</div>
				
				<!-- Receipt Content -->
				<div class="receipt-content">
					<table class="table" style="border-top: 1px solid #3c8dbc;font-size:12px !important" border="0">
						<thead style="background-color: rgba(60,141,188,0.1); border-bottom: 0.5px solid #3c8dbc;">
                            <tr>
                                <th style="padding-left: 20px;font-size:10px !important" class="" class="text-uppercase">
                                	PARTICULARS
                                </th>
                                <th></th>
                                <th></th>
                                <th style="padding-left: 20px;font-size:10px !important" class="" class="text-uppercase">
                                	AMOUNT
                                </th>
                            </tr>
                        </thead>
						<tbody style="border-top: 0.5px solid #3c8dbc;">
							<tr>
								<td class="payment-for" style="padding-bottom: 0px; padding-left:40px;" colspan="4">
									<strong>Payment For:</strong>
								</td>
							</tr>
							<tr>
								<td colspan="3" style="padding-top: 0px; padding-bottom: 0px; padding-left:60px;">
									{{ ($payment_history->payment_for) }}
								</td>
								<td colspan="1" style="padding-top: 0px; padding-bottom: 0px; padding-right: 40px;">
									<p style="float: left; padding-left: 8px !important;">Php</p>
									<p style="float: right;">{{ number_format($payment_history->amount, 2)}}</p>
								</td>
							</tr>
							<tr>
								<td colspan="3"></td>
								<td colspan="1">
									<p style="border-top: 0.5px solid; width: 83%; padding: 0;">
										<p style="padding-left: -10px; float: left; font-size: 12px; font-weight: bolder;">
											Php
										</p>
										<p style="float: right; padding-right: 0px; font-size: 12px; font-weight: bolder;">
											{{ number_format($payment_history->amount, 2)}}
										</p>
									</p>
								</td>
							</tr>
							<tr>
								<br>
								<td colspan="4" class="received-from" style="padding-bottom: 0px; padding-left:40px;" colspan="2">
									<strong>Received From:</strong>
								</td>
							</tr>
							<tr>
								{{-- Fullname --}}
								<td colspan="3" style="padding-top: 0px; padding-bottom: 0px; padding-left:60px;">
									{{$payment_history->enrollment->student->lastname}}, {{$payment_history->enrollment->student->firstname}} {{$payment_history->enrollment->student->middlename}}
								</td>
								<td style="padding-top: 0px; padding-bottom: 0px;"></td>
							</tr>
							<tr>
								{{-- Grade and Section --}}
								<td colspan="3" class="font-italic" style="padding-top: 0px; padding-bottom: 0px; padding-left:60px;">
									{{ $payment_history->enrollment->level_name }}
									{{ $payment_history->track_name ? ' - ' . $payment_history->track_name : '' }}
									{{ $payment_history->student_section ? ' - ' . $payment_history->student_section->section->name : '' }}								</td>
								<td style="padding-top: 0px; padding-bottom: 0px;"></td>
							</tr>
							<tr>
								{{-- SchoolYear --}}
								<td colspan="3" class="font-italic" style="padding-top: 0px; padding-bottom: 0px; padding-left:60px;">
									SY {{ $payment_history->enrollment->school_year_name }}
								</td>
								<td style="padding-top: 0px; padding-bottom: 0px;"></td>
							</tr>

							<tr>
								{{-- <td colspan="1" style="padding-bottom: 0px; padding-left:40px;">
									<strong>Date Received</strong>
								</td> --}}
								<td colspan="1" style="padding-bottom: 0px; padding-left:40px;">
									<strong>Payment Method</strong>
								</td>
								{{-- <td colspan="1" style="padding-bottom: 0px; padding-left:40px;">
									<strong>Reference No.</strong>
								</td> --}}
								<td style="padding-bottom: 0px; padding-left:40px;"></td>
							</tr>

							<tr>
								{{-- <td colspan="1" style="padding-top: 0px; padding-bottom: 0px; padding-left:40px;">
									{{ date("m/d/Y", 
										strtotime($payment_history->date_received ? $payment_history->date_received : $payment_history->created_at)) 
									}}
								</td> --}}
								<td colspan="1" style="padding-top: 0px; padding-bottom: 0px; padding-left:40px;">
									{{ $payment_history->paymentMethod->name }}
								</td>
								{{-- <td colspan="1" style="padding-top: 0px; padding-bottom: 0px; padding-left:40px;">
									000000
								</td> --}}
								<td style=" padding-top: 0px;padding-bottom: 0px; padding-left:40px;"></td>
							</tr>
						</tbody>
					</table>
				</div>
				<!-- End for Receipt Content -->
				<div class="signatures">
					<table class="table" style="border: none;">
						<tbody>
							<tr style="padding-bottom: 0px !important;">
								<td style="width: 25%;"><strong>Payment Verified by:</strong></td>
								<td style="width: 25%;"><strong>Payment Received From:</strong></td>
								<td style="width: 25%;"><strong>Date Processed:</strong></td>
								<td style="width: 25%; font-size: 14px;"><small><strong>STUDENT'S COPY</strong></small></td>
							</tr>
							<tr>
								<td>
									<p style="border-bottom: 0.5px solid; width: 83%;">
										{{ $payment_history->user->full_name }}
									</p>
								</td>
								<td>
									<p style="border-bottom: 0.5px solid; width: 83%;">
										{{ $payment_history->enrollment->full_name }}
									</p>
								</td>
								<td>
									<p style="border-bottom: 0.5px solid; width: 83%;">

									{{ date("m/d/Y") }}
								</p>
								</td>
								<td></td>
							</tr>
						</tbody>
					</table>
				</div>

		  	</section>
		</div>
		
	</body>
</html>