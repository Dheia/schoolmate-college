@php	
	$tuitions 		= $entry->tuition_fees;
	$miscellaneous  = $entry->miscellaneous;
	$activities 	= $entry->activities_fee;
	$other_fees 	= $entry->other_fees;
	$payment_scheme = $entry->payment_scheme;

	$initial_grand_total = 0;

	$commitmentPayments = App\Models\CommitmentPayment::active()->get();
	// dd($commitmentPayments);
@endphp

<style>
	#tuition-table th {
		padding: 2px 10px;
	}
	#tuition-table td:first-child, th:first-child {
		text-align: left;
	}

	#tuition-table td, th {
		text-align: right;
		padding: 2px 10px;
	}
</style>

<table id="tuition-table" class="table-striped" style="width: 100%">
	<thead style="background-color: #3C8DBC;" class="thead">
		<th style="color: #FFF; padding-top: 4px; padding-bottom: 4px;">Mandatory Fees Upon Enrollment</th>
		@foreach ($commitmentPayments as $commitmentPayment)
			<th style="color: #FFF; padding-top: 4px; padding-bottom: 4px;">
				{{ $commitmentPayment->name }}
			</th>
		@endforeach
	</thead>
	<tbody>
		<tr>
			<td>Tuition Fees</td>
			@foreach($tuitions as $key => $fee)
				@foreach ($commitmentPayments as $commitmentPayment)
					@if( $commitmentPayment->id == $fee->payment_type )
						<td>{{ number_format($fee->tuition_fees, 2, ".", ", ") }}</td>
					@endif
				@endforeach
			@endforeach
		</tr>

		<tr>
			<td>Less : Early Bird Discount</td>
			@foreach($tuitions as $key => $fee)
				@foreach ($commitmentPayments as $commitmentPayment)
					@if( $commitmentPayment->id == $fee->payment_type )
						<td>-{{ number_format($fee->discount, 2, ".", ", ") }}</td>
					@endif
				@endforeach
			@endforeach
		</tr>

		<tr style="border-top: 2px solid #3C8DBC; border-bottom: 2px solid #3C8DBC;">
			<td><b>Total Payable Upon Enrollment</b></td>
			@foreach($tuitions as $key => $fee)
				@foreach ($commitmentPayments as $commitmentPayment)
					@if( $commitmentPayment->id == $fee->payment_type )
						<td><b>{{ number_format($fee->tuition_fees - $fee->discount, 2, ".", ", ") }}</b></td>
					@endif
				@endforeach
			@endforeach
		</tr>

		
		{{-- MISCELLANEOUS FEE --}}
		<thead style="background-color: #3C8DBC;" class="thead">
			<th style="color: #FFF; padding-top: 4px; padding-bottom: 4px;" colspan="{{ count($commitmentPayments) + 1 }}">
				Miscellaneous Fees
			</th>
		</thead>
		@php
			$totalMisc = 0;
		@endphp

		@if($miscellaneous !== null && count($miscellaneous) > 0)
			@foreach($miscellaneous as $misc)
				<tr>
					<td>{{ $misc->code }} - {{ $misc->description }}</td>
					@foreach ($commitmentPayments as $commitmentPayment)
						<td>{{ number_format($misc->amount, 2, ".", ", ") }}</td>
					@endforeach
				</tr>
				@php  $totalMisc += $misc->amount @endphp
			@endforeach
		@else
			<tr><td colspan="5"><h4 class="text-center">No Miscellaneous</h4></td></tr>
		@endif

		<tr style="border-top: 2px solid #3C8DBC; border-bottom: 2px solid #3C8DBC;">
			<td><b>Total Miscellaneous Fee</b></td>
			@foreach($tuitions as $key => $fee)
				@foreach ($commitmentPayments as $commitmentPayment)
					@if($commitmentPayment->id == $fee->payment_type)
						<td><b>{{ number_format($totalMisc, 2, ".", ", ") }}</b></td>
					@endif
				@endforeach
			@endforeach
		</tr>
		{{-- END OF MISCELLANEOUS FEE --}}


		{{-- ACTIVITY FEES --}}
		<thead style="background-color: #3C8DBC;" class="thead">
			<th style="color: #FFF; padding-top: 4px; padding-bottom: 4px;" colspan="{{ count($commitmentPayments) + 1 }}">
				Activity Fees
			</th>
		</thead>
		@php
			$totalActivity = 0;
		@endphp

		@if($activities !== null && count($activities) > 0)
			@foreach($activities as $activity)
				<tr>
					<td>{{ $activity->code }} - {{ $activity->description }}</td>
					@foreach ($commitmentPayments as $commitmentPayment)
						<td>{{ number_format($activity->amount, 2, ".", ", ") }}</td>
					@endforeach
				</tr>
				@php  $totalActivity += $activity->amount @endphp
			@endforeach
		@else
			<tr>
				<td colspan="{{ count($commitmentPayments) + 1 }}">
					<h4 class="text-center">No Activity</h4>
				</td>
			</tr>
		@endif
		<tr style="border-top: 2px solid #3C8DBC; border-bottom: 2px solid #3C8DBC;">
			<td><b>Total Activity Fees</b></td>
			@foreach($tuitions as $key => $fee)
				@foreach ($commitmentPayments as $commitmentPayment)
					@if( $commitmentPayment->id == $fee->payment_type )
						<td><b>{{ number_format($totalActivity, 2, ".", ", ") }}</b></td>
					@endif
				@endforeach
			@endforeach
		</tr>
		{{-- END OF ACTIVITIES FEE --}}
		
		{{-- OTHER FEES --}}
		<thead style="background-color: #3C8DBC;">
			<th style="color: #FFF; padding-top: 4px; padding-bottom: 4px;" colspan="{{ count($commitmentPayments) + 1 }}">
				Other Fees
			</th>
		</thead>
		@php
			$totalOtherFee = 0;
		@endphp

		@if($other_fees !== null && count($other_fees) > 0)
			@foreach($other_fees as $other_fee)
				<tr>
					<td>{{ $other_fee->code }} - {{ $other_fee->description }}</td>
					@foreach ($commitmentPayments as $commitmentPayment)
						<td>{{ number_format($other_fee->amount, 2, ".", ", ") }}</td>
					@endforeach
				</tr>
				@php  $totalOtherFee += $other_fee->amount @endphp
			@endforeach
		@else
			<tr>
				<td colspan="{{ count($commitmentPayments) + 1 }}">
					<h4 class="text-center">No Other Fees</h4>
				</td>
			</tr>
		@endif
		<tr style="border-top: 2px solid #3C8DBC; border-bottom: 2px solid #3C8DBC;">
			<td><b>Total Other Fees</b></td>
			@foreach($tuitions as $key => $fee)
				@foreach ($commitmentPayments as $commitmentPayment)
					@if( $commitmentPayment->id == $fee->payment_type )
						<td><b>{{ number_format($totalOtherFee, 2, ".", ", ") }}</b></td>
					@endif
				@endforeach
			@endforeach
		</tr>
		{{-- END OF OTHER FEES --}}


		<tr style="border-top: 2px solid #3C8DBC; border-bottom: 2px solid #3C8DBC;">
			<td><b>Total Mandatory Fees Upon Enrollment</b></td>

			@php 
				$misc_inc = 0; 
				foreach ($miscellaneous as $misc) {
					$misc_inc += $misc->amount;
				}
			@endphp
			
			@foreach($tuitions as $key => $fee)
				@foreach ($commitmentPayments as $commitmentPayment)
					@if( $commitmentPayment->id == $fee->payment_type )
						<td><b>{{ number_format( ($fee->tuition_fees - $fee->discount) + $misc_inc + $totalActivity + $totalOtherFee, 2, ".", ", ") }}</b></td>
					@endif
				@endforeach
			@endforeach

		</tr>
		

		<thead style="background-color: #3C8DBC;">
			<th style="color: #FFF; padding-top: 4px; padding-bottom: 4px;" colspan="{{ count($commitmentPayments) + 1 }}">
				Tuition Fee (Payment Scheme)
			</th>
		</thead>
		
		{{-- PAYMENT SCHEME --}}
		@if($payment_scheme !== null && count($payment_scheme) > 0)
					{{-- <th>{{ json_encode($payment_scheme) }}</th> --}}
			@php 
				$total_payment_scheme = []; 
				foreach($commitmentPayments as $cPayment) {
					$total_payment_scheme[] = 0; 
				}
			@endphp

			@foreach($payment_scheme as $ps)
				<tr>
					<td>{{ $ps->scheme_date }}</td>
					@foreach ($commitmentPayments as $key => $cPayment)
						@if(isset($ps->{ $cPayment->snake . '_amount'}))
							<td>{{ number_format($ps->{ $cPayment->snake . '_amount'}, 2, ".", ", ") }}</td>
							@php 
								$total_payment_scheme[$key]	+= $ps->{ $cPayment->snake . '_amount' };
							@endphp
						@else
							<td>0.00</td>
							@php
								$total_payment_scheme[$key]	+= 0;
							@endphp
						@endif
					@endforeach
				</tr>
			@endforeach

			<tr style="border-top: 2px solid #3C8DBC; border-bottom: 2px solid #3C8DBC;">
				<td><b>Total Tuition Fees</td>
				@foreach($total_payment_scheme as $p_scheme)
					<td><b>{{ number_format($p_scheme, 2, ".", ", ") }}</b></td>
				@endforeach
			</tr>
			{{-- {{ $total_payment_scheme }} --}}
		@else
			<tr><td colspan="5"><h4 class="text-center">No Payment Scheme</h4></td></tr>
		@endif
		{{-- END OF PAYMENT SCHEME --}}

		{{-- GRAND TOTAL --}}
		<thead style="background-color: #3C8DBC;">
			<th style="color: #FFF; padding-top: 4px; padding-bottom: 4px;">Grand Total</th>
			@foreach($tuitions as $key => $fee)
				@foreach ($commitmentPayments as $key => $cPayment)
					@if( $cPayment->id == $fee->payment_type )
						<td style="color: #FFF">
							<b>
								{{ number_format( ($fee->tuition_fees - $fee->discount) + $misc_inc + $totalActivity + $totalOtherFee + $total_payment_scheme[$key], 2, ".", ", ") }}
							</b>
						</td>
					@endif
				@endforeach
			@endforeach
		</thead>
		{{-- END OF GRAND TOTAL --}}
		
	</tbody>
</table>