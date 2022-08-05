<!DOCTYPE html>	
<head>
	<title>{{ $schoolName }} |  Statement of Accounts</title>
	<meta charset="utf-8" />
    <meta name="viewport" content="width=device-width">


	<style>

		.d-block {
			display: block;
		}
		.m-0 {
			margin: 0;
			margin-bottom: 4px;
		}

		.text-center {
			text-align: center;
		}

		.text-right {
			text-align: right;
		}
		.table {
			width: 100%;
			/*border: 1px solid #ccc;*/
			padding: 10px;
		}

		.table td {
			font-size: 13px;
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
		body {
			font-size: 9px;
			margin-bottom: 50px !important;
			margin-top: 280px !important;
		}
		header {
			position: fixed;
			top: 0px;
			height: 100px;
		}
		footer { 
			position: fixed;
			bottom: 0px; 
			height: 50px;
		}
	</style>


</head>
<body>
	<header>
		<center>	
			<img width="80" src="{{ $schoolLogo }}" alt="IMG" align="center" style="">
			<h1 class="text-center m-0">{{ $schoolName }}</h1>
			<p class="text-center m-0" style="font-size: 12px;">{{ $schoolAddress }}</p>
		</center>
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
	</header>
	<footer>
		<center>
			<img width="40" src="images/logo_schoolmate.png" alt="schoolmate_logo">
		</center>
		<center>
			<p class="mb-0" style="font-size: 12px; margin-bottom: 0;">Copyright &copy; 2019</p>
			<p class="pt-0" style="font-size: 12px; margin-top: 0;">Powered by: Tigernet Hosting and IT Services</p>
		</center>
	</footer>	
	<main>
		<div class="items" style="margin-top: 0px;">
				<table class="table">
					<tbody>
						<tr>
							<td><b>Tuition Form</b></td>
							<td class="text-right"><b>{{ $tuition->form_name }}</b></td>
						</tr>
						<tr>
							<td><b>Payment Basis</b></td>
							<td class="text-right">
								<b>
									{{ $enrollment->commitmentPayment ? $enrollment->commitmentPayment->name : '-' }}
								</b>
							</td>
						</tr>
						<tr>
							<td><b>Tuition Fee</b></td>
							<td class="text-right">
								<b>
									@php
                						$upon = collect($tuition->total_payable_upon_enrollment)
                									->where('payment_type', $enrollment->commitment_payment_id)
                									->first()['amount'];

                						$pScheme = collect($tuition->total_payment_scheme)
                									->where('payment_type', $enrollment->commitment_payment_id)
                									->first()['amount'];
									@endphp

									PHP {{ number_format(($upon + $pScheme), 2) }}
								</b>
							</td>
						</tr>
						
						{{-- UPON ENROLLMENT --}}
						<tr>
                            <td style="text-indent: 10px; padding: 0;">
                                <small style="font-size: 90%;">- Upon Enrollment</small>
                            </td>
                            <td class="text-right" style="padding: 0;">
                                <small style="font-size: 90%;">
                                	P 	{{
                                		 	number_format(
                                		 		collect($tuition->total_payable_upon_enrollment)
	                                		 	->where('payment_type', $enrollment->commitment_payment_id)
	                                		 	->first()['amount'],
                            		 	 	2) 
                                		}}
                                </small>
                            </td>
                        </tr>
						{{-- UPON ENROLLMENT --}}

                        <!-- TOTAL PAYMENT SCHEME -->
                        <tr>
                            <td style="text-indent: 10px; padding: 0;">
                                <small style="font-size: 90%;">- Balance</small>
                            </td>
                            <td class="text-right" style="padding: 0;">
                                <small style="font-size: 90%;">
                                    PHP 	{{
	                                		 	number_format(
	                                		 		collect($tuition->total_payment_scheme)
		                                		 	->where('payment_type', $enrollment->commitment_payment_id)
		                                		 	->first()['amount'],
	                            		 	 	2) 
	                                		}}
                                </small>
                            </td>
                        </tr>
                        <!-- TOTAL PAYMENT SCHEME -->


						<tr>
							<td><b>Activity Fees</b></td>
							<td class="text-right"><b>PHP {{ number_format($tuition->total_activities, 2) }}</b></td>
						</tr>
						
                        <!-- ACTIVTY FEES ITEM -->
                        @if($tuition->activities_fee !== null)
	                        @foreach ($tuition->activities_fee as $activity)
		                        <tr>
		                            <td style="text-indent: 10px; padding: 0;">
		                                <small style="font-size: 90%;">- {{ $activity->code . ' ' . $activity->description }}</small>
		                            </td>
		                            <td class="text-right" style="padding: 0;">
		                                <small style="font-size: 90%;">
		                                    PHP {{ number_format($activity->amount, 2) }}
		                                </small>
		                            </td>
		                        </tr>
	                        @endforeach
                        @endif
                        <!-- ACTIVTY FEES ITEM -->


						<tr>
							<td><b>Miscellaneous Fees</b></td>
							<td class="text-right"><b>PHP {{ number_format($tuition->total_miscellaneous, 2) }}</b></td>
						</tr>

                        <!-- MISCELLANEOUS FEES ITEM -->
                        @if($tuition->miscellaneous !== null)
	                        @foreach ($tuition->miscellaneous as $misc)
		                        <tr>
		                            <td style="text-indent: 10px; padding: 0;">
		                                <small style="font-size: 90%;">- {{ $misc->code . ' ' . $misc->description }}</small>
		                            </td>
		                            <td class="text-right" style="padding: 0;">
		                                <small style="font-size: 90%;">
		                                    PHP {{ number_format($misc->amount, 2) }}
		                                </small>
		                            </td>
		                        </tr>
	                        @endforeach
                        @endif
                        <!-- MISCELLANEOUS FEES ITEM -->


						<tr>
							<td><b>Other Fees</b></td>
							<td class="text-right"><b>PHP {{ number_format($tuition->total_other_fees, 2) }}</b></td>
						</tr>
						
						<!-- OTHER FEES ITEM -->
                        @if($tuition->other_fees !== null)
	                        @foreach ($tuition->other_fees as $otherFee)
		                        <tr>
		                            <td style="text-indent: 10px; padding: 0;">
		                                <small style="font-size: 90%;">- {{ $otherFee->code . ' ' . $otherFee->description }}</small>
		                            </td>
		                            <td class="text-right" style="padding: 0;">
		                                <small style="font-size: 90%;">
		                                    PHP {{ number_format($otherFee->amount, 2) }}
		                                </small>
		                            </td>
		                        </tr>
	                        @endforeach
                        @endif
                        <!-- OTHER FEES ITEM -->

                        <!-- OTHER PROGRAM(s) -->
                        <tr>
							<td><b>Other Program(s)</b></td>
							<td class="text-right">
								<b>
									PHP {{ number_format($enrollment->selectedOtherPrograms->sum('otherProgramWithTrashed.amount'), 2) }}
								</b>
							</td>
						</tr>
						@if( count($enrollment->selectedOtherPrograms) > 0 )
							<!-- OTHER PROGRAM(s) ITEM -->
	                        @foreach ($enrollment->selectedOtherPrograms as $selectedOtherProgram)
	                        	@php $otherProgram = $selectedOtherProgram->otherProgramWithTrashed @endphp
	                        	@if($otherProgram)
			                        <tr>
			                            <td style="text-indent: 10px; padding: 0;">
			                                <small style="font-size: 90%;">- {{ $otherProgram->name }}</small>
			                            </td>
			                            <td class="text-right" style="padding: 0;">
			                                <small style="font-size: 90%;">
			                                    PHP {{ number_format($otherProgram->amount, 2) }}
			                                </small>
			                            </td>
			                        </tr>
			                    @endif
	                        @endforeach
	                        <!-- OTHER PROGRAM(s) ITEM -->
						@endif
                        <!-- OTHER PROGRAM(s) -->

                        <!-- OTHER SERVICE(s) -->
                        <tr>
							<td><b>Other Service(s)</b></td>
							<td class="text-right">
								<b>
									PHP {{ number_format($enrollment->selectedOtherServices->sum('otherServiceWithTrashed.amount'), 2) }}
								</b>
							</td>
						</tr>

						@if( count($enrollment->selectedOtherServices) > 0 )
							<!-- OTHER SERVICE(s) ITEM -->
	                        @foreach ($enrollment->selectedOtherServices as $selectedOtherService)
	                        	@php $otherService = $selectedOtherService->otherServiceWithTrashed @endphp
	                        	@if($otherService)
			                        <tr>
			                            <td style="text-indent: 10px; padding: 0;">
			                                <small style="font-size: 90%;">- {{ $otherService->name }}</small>
			                            </td>
			                            <td class="text-right" style="padding: 0;">
			                                <small style="font-size: 90%;">
			                                    PHP {{ number_format($otherService->amount, 2) }}
			                                </small>
			                            </td>
			                        </tr>
			                    @endif
	                        @endforeach
	                        <!-- OTHER SERVICE(s) ITEM -->
						@endif
                        <!-- OTHER SERVICE(s) -->

                        <!-- ADDITIONAL FEE(s) -->
                        <tr>
							<td><b>Additional Fee(s)</b></td>
							<td class="text-right">
								<b>
									PHP {{ number_format($enrollment->additionalFees->sum('amount'), 2) }}
								</b>
							</td>
						</tr>

						@if( count($enrollment->additionalFees) > 0 )
							<!-- ADDITIONAL FEE(s) ITEM -->
	                        @foreach ($enrollment->additionalFees as $additionalFee)
	                        	<tr>
		                            <td style="text-indent: 10px; padding: 0;">
		                                <small style="font-size: 90%;">- {{ $additionalFee->name }}</small>
		                            </td>
		                            <td class="text-right" style="padding: 0;">
		                                <small style="font-size: 90%;">
		                                    PHP {{ number_format($additionalFee->amount, 2) }}
		                                </small>
		                            </td>
		                        </tr>
	                        @endforeach
	                        <!-- ADDITIONAL FEE(s) ITEM -->
						@endif
                        <!-- ADDITIONAL FEE(s) -->

						<tr>
							<td colspan="2"><hr></td>
						</tr>
						<tr>
							<td><b>Grand Total</b></td>
							<td class="text-right">
								<b>PHP {{ number_format($enrollment->total_tuition, 2) }}</b>
							</td>
						</tr>
					</tbody>
				</table>
			</div>

			<!-- LESS - DISCOUNTS & DISCREPANCIES -->
			@if( count($enrollment->specialDiscounts) > 0 || count($enrollment->discrepancies) > 0)
				<div class="items">
					<table class="table">
						<tbody>
							<tr>
								<td colspan="2"><b style="color: red;">Less</b></td>
							</tr>

							@if(count($enrollment->specialDiscounts)>0)
								<tr>
									<td colspan="2"><b>Special Discounts</b></td>
								</tr>
								@foreach($enrollment->specialDiscounts as $special_discount)
									<tr>
			                            <td style="text-indent: 10px; padding: 0;">
			                                <small style="font-size: 90%;">- {{ $special_discount->apply_to }}</small>
			                            </td>
			                            <td class="text-right" style="padding: 0;">
			                                <small style="font-size: 90%;">
			                                    PHP {{ number_format($special_discount->amount, 2) }}
			                                </small>
			                            </td>
			                        </tr>
								@endforeach
							@endif

							@if(count($enrollment->discrepancies)>0)
								<tr>
									<td colspan="2"><b>Discrepancies</b></td>
								</tr>
								@foreach($enrollment->discrepancies as $discrepancy)
									<tr>
			                            <td style="text-indent: 10px; padding: 0;">
			                                <small style="font-size: 90%;">- {{ $discrepancy->description }}</small>
			                            </td>
			                            <td class="text-right" style="padding: 0;">
			                                <small style="font-size: 90%;">
			                                    PHP {{ number_format($discrepancy->amount, 2) }}
			                                </small>
			                            </td>
			                        </tr>
								@endforeach
							@endif
							
							<tr>
								<td colspan="2"><hr></td>
							</tr>
							<tr>
								<td><b>Total Less</b></td>
								<td class="text-right">
									<b style="color: red;">
										( PHP {{ number_format($enrollment->specialDiscounts->sum('amount') - $enrollment->discrepancies->sum('amount'), 2) }} )
									</b>
								</td>
							</tr>
							
						</tbody>
					</table>
				</div>
			@endif
			<!-- LESS - DISCOUNTS & DISCREPANCIES -->

			<!-- PAYMENT HISTORY -->
			<div class="items">
				<table class="table">
					<tbody>
						<tr>
							<td colspan="2"><b>Payment History</b></td>
						</tr>

						@if(count($payment_histories)>0)
							@foreach($payment_histories as $payment_history)
								<tr>
									<td>{{ date_format($payment_history->created_at, 'F d, Y - h:i A') }}</td>
									<td class="text-right">
										<b>
											PHP {{ number_format($payment_history->amount, 2) }}
										</b>
									</td>
								</tr>
							@endforeach
						
							<tr>
								<td colspan="2"><hr></td>
							</tr>
							<tr>
								<td><b>Total Payment History</b></td>
								<td class="text-right">
									<b>PHP {{ number_format($payment_histories->sum('amount'), 2) }}</b>
								</td>
							</tr>
						@endif
					</tbody>
				</table>
			</div>
			<!-- PAYMENT HISTORY -->

			<!-- TOTAL SUMMARY -->
			<div class="items">
				<table class="table">
					<tbody>
						<tr>
							<td colspan="2"><b>Payment History</b></td>
						</tr>

						<tr>
							<td><b>Grand Total</b></td>
							<td class="text-right">
								<b>PHP {{ number_format($enrollment->total_tuition, 2) }}</b>
							</td>
						</tr>

						@if($enrollment->total_discounts_discrepancies)
						<tr>
							<td><b>Total Less</b></td>
							<td class="text-right">
								<b style="color: red;">( PHP {{ number_format($enrollment->total_discounts_discrepancies, 2) }} )</b>
							</td>
						</tr>
						@endif
						<tr>
							<td><b>Total Payment History</b></td>
							<td class="text-right">
								<b style="color: red;"> ( PHP {{ number_format($payment_histories->sum('amount'), 2) }} ) </b>
							</td>
						</tr>
						<tr>
							<td colspan="2"><hr></td>
						</tr>
						<tr>
							<td><b>Remaining Balance</b></td>
							<td class="text-right">
								<b>PHP {{ number_format($enrollment->remaining_balance, 2) }}</b>
							</td>
						</tr>

					</tbody>
				</table>
			</div>
			<!-- TOTAL SUMMARY -->

	</main>
	
</body>
</html>
