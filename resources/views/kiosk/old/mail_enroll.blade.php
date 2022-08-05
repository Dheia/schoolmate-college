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
			<table class="table">
				<tbody>
					<tr>
						<td>
							<p class="m-0"><b>{{ config('settings.schoolabbr') . ' - ' . $enrollment->studentnumber }}</b></p> 
							<h3 class="m-0">{{ $enrollment->full_name }}</h3> 
						</td>
						<td class="text-right">
							<p>Enrollment Date</p>
							<p><b>{{ $enrollment->created_at->format('M d, Y') }}</b></p>
						</td>
					</tr>
				</tbody>
			</table>

			<div class="items">
				<table class="table">
					<tbody>
						<tr>
							<td>Kiosk ID</td>
							<td class="text-right"><b>{{ $kiosk->kiosk_id }}</b></td>
						</tr>
						<tr>
							<td>Email Recipient</td>
							<td class="text-right"><a href="#">{{ $kiosk->email }}</a></td>
						</tr>
						@if($show_tuition && $tuition)
						<tr>
							<td>Tuition Form</td>
							<td class="text-right"><b>{{ $tuition->form_name }}</b></td>
						</tr>
						@endif
						<tr>
							<td>Enrolling As</td>
							<td class="text-right">
								<b>
									{{ App\Models\YearManagement::where('id', $enrollment->level_id)->first()->year }} - 
									{{ $enrollment->term_type }} | 
									{{ App\Models\SchoolYear::where('id', $enrollment->school_year_id)->first()->schoolYear }}
								</b>
							</td>
						</tr>
						<tr>
							<td>Payment Basis</td>
							<td class="text-right">
								<b>
									{{ App\Models\CommitmentPayment::where('id', $enrollment->commitment_payment_id)->first()->name }}
								</b>
							</td>
						</tr>
						
						@if($show_tuition  && $tuition)
							<tr>
								<td>Tuition Fee</td>
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
	                                <small style="font-size: 70%;">- Upon Enrollment</small>
	                            </td>
	                            <td class="text-right" style="padding: 0;">
	                                <small style="font-size: 70%;">
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
	                                <small style="font-size: 70%;">- Balance</small>
	                            </td>
	                            <td class="text-right" style="padding: 0;">
	                                <small style="font-size: 70%;">
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
								<td>Activity Fees</td>
								<td class="text-right"><b>PHP {{ number_format($tuition->total_activities, 2) }}</b></td>
							</tr>
							
	                        <!-- ACTIVTY FEES ITEM -->
	                        @if($tuition->activities_fee !== null)
		                        @foreach ($tuition->activities_fee as $activity)
			                        <tr>
			                            <td style="text-indent: 10px; padding: 0;">
			                                <small style="font-size: 70%;">- {{ $activity->code . ' ' . $activity->description }}</small>
			                            </td>
			                            <td class="text-right" style="padding: 0;">
			                                <small style="font-size: 70%;">
			                                    PHP {{ number_format($activity->amount, 2) }}
			                                </small>
			                            </td>
			                        </tr>
		                        @endforeach
	                        @endif
	                        <!-- ACTIVTY FEES ITEM -->


							<tr>
								<td>Miscellaneous Fees</td>
								<td class="text-right"><b>PHP {{ number_format($tuition->total_miscellaneous, 2) }}</b></td>
							</tr>

	                        <!-- MISCELLANEOUS FEES ITEM -->
	                        @if($tuition->miscellaneous !== null)
		                        @foreach ($tuition->miscellaneous as $misc)
			                        <tr>
			                            <td style="text-indent: 10px; padding: 0;">
			                                <small style="font-size: 70%;">- {{ $misc->code . ' ' . $misc->description }}</small>
			                            </td>
			                            <td class="text-right" style="padding: 0;">
			                                <small style="font-size: 70%;">
			                                    PHP {{ number_format($misc->amount, 2) }}
			                                </small>
			                            </td>
			                        </tr>
		                        @endforeach
	                        @endif
	                        <!-- MISCELLANEOUS FEES ITEM -->


							<tr>
								<td>Other Fees</td>
								<td class="text-right"><b>PHP {{ number_format($tuition->total_other_fees, 2) }}</b></td>
							</tr>
							
							<!-- OTHER FEES ITEM -->
	                        @if($tuition->other_fees !== null)
		                        @foreach ($tuition->other_fees as $otherFee)
			                        <tr>
			                            <td style="text-indent: 10px; padding: 0;">
			                                <small style="font-size: 70%;">- {{ $otherFee->code . ' ' . $otherFee->description }}</small>
			                            </td>
			                            <td class="text-right" style="padding: 0;">
			                                <small style="font-size: 70%;">
			                                    PHP {{ number_format($otherFee->amount, 2) }}
			                                </small>
			                            </td>
			                        </tr>
		                        @endforeach
	                        @endif
	                        <!-- OTHER FEES ITEM -->

							<tr>
								<td colspan="2"><hr></td>
							</tr>
							<tr>
								<td>Amount Due</td>
								<td class="text-right">
									<b>PHP {{ number_format(collect($tuition->grand_total)->where('payment_type', $enrollment->commitment_payment_id)->first()['amount'], 2) }}</b>
								</td>
							</tr>
						@endif
					</tbody>
				</table>
			</div>
		</div>

		<div class="footer">
			<img src="{{ asset('images/smo_logo.png') }}" alt="" width="80" style="display: block; margin: auto;">
		</div>

	</div>

</div>