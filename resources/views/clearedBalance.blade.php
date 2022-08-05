@extends('backpack::layout')

@section('header')
	<section class="content-header">
	  <h1>
        <span class="text-capitalize">{{ \App\Models\CashAccount::find($pathId)->name }}</span>
        <small>All transactions.</small>
	  </h1>
	  <ol class="breadcrumb">
	    <li><a href="{{ url(config('backpack.base.route_prefix'), 'cash-account') }}">Cash Account</a></li>
	    <li class="active">Cleared Balance</li>
	  </ol>
	</section>
@endsection

@section('content')
	<div class="row">
		<div class="col-md-12">
			<!-- Default box -->
			
			<div class="box" >
			{{-- 	<div class="box-header with-border">
			      <h3 class="box-title"></h3>
			    </div> --}}
				
				<div class="box-body display-flex-wrap" style="display: flex; flex-wrap: wrap;">
						<table class="table table-striped  table-sm" id="table-basic-info">
							<thead style="background-color: #42286C; color: #FFF;">
								<th><small>Name</small></th>
								<th><small>Amount</small></th>
							</thead>
							<tbody>
								<tr>
									<td><small>Total Payment History</small></td>
									<td id="bTable-total-student"></td>
								</tr>
								<tr>
									<td><small>Total Received Money</small></td>
									<td id="bTable-total-received"></td>
								</tr>	
								<tr>
									<td><small>Total Spend Money</small></td>
									<td id="bTable-total-spend-money"></td>
								</tr>
								<tr>
									<td><small>Total Transfer Money</small></td>
									<td id="bTable-total-transfer-money"></td>
								</tr>	
								<tr style="border-top: 2px solid #42286C;">
									<td><small><b>Overall Total</b></small></td>
									<td id="bTable-overall"></td>
								</tr>
							</tbody>
						</table>
				</div>
			</div>

			<div class="box" id="tabs">

			    <div class="box-header with-border">
			      {{-- <h3 class="box-title">Student Payments</h3> --}}
			      <ul>
				    <li><a href="#payment-history">Payment History</a></li>
				    <li><a href="#received-money">Received Money</a></li>
				    <li><a href="#spend-money">Spend Money</a></li>
				    <li><a href="#transfer-money">Transfer Money</a></li>
				  </ul>
			    </div>

		    	<div id="payment-history">
				    <div class="box-body row display-flex-wrap" style="display: flex; flex-wrap: wrap;">
				    		
					    	<table class="table  table-striped text-center">
					    		<thead style="background-color: #42286C; color: #FFF;">
					    			<th>Date</th>
					    			<th>#</th>
					    			<th>Description</th>
					    			<th>Amount</th>
					    		</thead>
					    		<tbody>
					    			@foreach($paymentHistories as $ph)
										<tr>
											<td>
												{{ $ph->created_at->format('Y-m-d') }}
											</td>
											<td></td>
											<td></td>
											<td class="student-amount" student-amount="{{ $ph->amount }}">
												{{ number_format($ph->amount, 2) }}
											</td>
										</tr>
					    			@endforeach
					    				<tr>
					    					<td><b>Total</b></td>
					    					<td></td>
					    					<td></td>
					    					<td id="total-student-amount"></td>
					    				</tr>
					    		</tbody>
					    	</table>
			    	</div>
			    </div>

			    <div id="received-money">
				    <div class="box-body row display-flex-wrap" style="display: flex; flex-wrap: wrap;">
				    		
					    	<table class="table  table-striped text-center table-responsive">
					    		<thead style="background-color: #42286C; color: #FFF;">
					    			<th><i class="fa fa-pencil-square-o"></i></th>
					    			{{-- <th><i class="fa fa-eye"></i></th> --}}
					    			<th>Date</th>
					    			<th>#</th>
					    			<th>Account</th>
					    			<th>Description</th>
					    			<th>Amount</th>
					    			<th>Balance</th>
					    			<th>Status</th>
					    		</thead>
					    		<tbody>
					    			@foreach($receiveMoneys as $rm)
										<tr>
											<td>
												<a href="/admin/receive-money/{{ $rm->id }}/edit" class="btn btn-default btn-sm btn-block">Edit</a>
											</td>
											{{-- <td>
												<a href="" class="btn btn-default btn-sm btn-block">View</a>
											</td> --}}
											<td>
												{{ $rm->created_at->format('Y-m-d') }}
											</td>
											<td>{{ $rm->referrence_no }}</td>
											<td>
												@php 
													$accounts = json_decode($rm->accounts);
													$copy = $accounts;

													$total = 0;
													foreach ($accounts as $account) {
														$total += (int)$account->quantity * $account->unit_price; 
													}
												@endphp
												@foreach($accounts as $account)
													{{ \App\Models\ProfitsLossStatement::find($account->account)->name }} 
													@php
														if (next($copy )) { echo ','; }
													@endphp
												@endforeach
											</td>
											<td>
												@php 
													$copy = $accounts;
												@endphp
												@foreach($accounts as $account)
													{{ $account->description }} 
													@php
														if (next($copy )) { echo ','; }
													@endphp
												@endforeach
											</td>
											<td class="received-amount" received-amount="{{ $total }}">
												{{ number_format($total, 2) }}
											</td>
											<td>
												{{ number_format($total, 2) }}
											</td>
											<td>
												@php
													$print = "";
													if($rm->received_in_id == 1) {
														$print = "Cleared";
													} else {
														$print = $rm->status;
													}
												@endphp
												<span class="label label-success">{{ $print }}</span>
											</td>
										</tr>
					    			@endforeach
					    				<tr>
											<td class="text-right"><b>Total</b></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td id="total-amount-received"></td>
											<td></td>
											<td></td>
										</tr>
					    		</tbody>
					    	</table>
				    	
			    	</div>
			    </div>

			    <div id="spend-money">
				    <div class="box-body row display-flex-wrap" style="display: flex; flex-wrap: wrap;">
				    		
					    	<table class="table  table-striped text-center table-responsive">
					    		<thead style="background-color: #42286C; color: #FFF;">
					    			<th><i class="fa fa-pencil-square-o"></i></th>
					    			{{-- <th><i class="fa fa-eye"></i></th> --}}
					    			<th>Date</th>
					    			<th>#</th>
					    			<th>Account</th>
					    			<th>Description</th>
					    			<th>Amount</th>
					    			<th>Balance</th>
					    			<th>Status</th>
					    		</thead>
					    		<tbody>
					    			@foreach($spendMoneys as $sm)
										<tr>
											<td>
												<a href="/admin/spend-money/{{ $sm->id }}/edit" class="btn btn-default btn-sm btn-block">Edit</a>
											</td>
											{{-- <td>
												<a href="" class="btn btn-default btn-sm btn-block">View</a>
											</td> --}}
											<td>
												{{ $sm->created_at->format('Y-m-d') }}
											</td>
											<td>{{ $sm->referrence_no }}</td>
											<td>
												@php 
													$accounts = json_decode($sm->accounts);
													$copy = $accounts;

													$total = 0;
													foreach ($accounts as $account) {
														$total -= (int)$account->quantity * $account->unit_price; 
													}
												@endphp
												@foreach($accounts as $account)
													{{ \App\Models\ProfitsLossStatement::find($account->account)->name }} 
													@php
														if (next($copy )) { echo ','; }
													@endphp
												@endforeach
											</td>
											<td>
												@php 
													$copy = $accounts;
												@endphp
												@foreach($accounts as $account)
													{{ $account->description }} 
													@php
														if (next($copy )) { echo ','; }
													@endphp
												@endforeach
											</td>
											<td class="spend-money-amount" spend-money-amount="{{ $total }}">
												<b style="color: red;">{{ number_format($total, 2) }}</b>
											</td>
											<td>
												{{ number_format($total, 2) }}
											</td>
											<td>
												@php
													$print = "";
													if($sm->received_in_id == 1) {
														$print = "Cleared";
													} else {
														$print = $sm->status;
													}
												@endphp
												<span class="label label-success">{{ $print }}</span>
											</td>
										</tr>
					    			@endforeach
					    				<tr>
											<td class="text-right"><b>Total</b></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td id="total-amount-spend-money"></td>
											<td></td>
											<td></td>
										</tr>
					    		</tbody>
					    	</table>
				    	
			    	</div>
			    </div>

			    <div id="transfer-money">
				    <div class="box-body row display-flex-wrap" style="display: flex; flex-wrap: wrap;">
				    		
					    	<table class="table  table-striped text-center table-responsive">
					    		<thead style="background-color: #42286C; color: #FFF;">
					    			<th><i class="fa fa-pencil-square-o"></i></th>
					    			{{-- <th><i class="fa fa-eye"></i></th> --}}
					    			<th>Date</th>
					    			<th>#</th>
					    			<th>Account</th>
					    			<th>Description</th>
					    			<th>Amount</th>
					    			<th>Balance</th>
					    			<th>Status</th>
					    		</thead>
					    		<tbody>
					    			@foreach($transferMoneys as $tm)
										<tr>
											<td>
												<a href="/admin/transfer-money/{{ $tm->id }}/edit" class="btn btn-default btn-sm btn-block">Edit</a>
											</td>
										{{-- 	<td>
												<a href="" class="btn btn-default btn-sm btn-block">View</a>
											</td> --}}
											<td>
												{{ $tm->created_at->format('Y-m-d') }}
											</td>
											<td>{{ $tm->referrence_no }}</td>
											<td>
												{{ \App\Models\CashAccount::find($tm->received_in_id)->name }}
											</td>
											<td>
												{{ $tm->description }}
											</td>
											@if($tm->paid_from_id == $pathId)
												<td class="transfer-money-amount" transfer-money="-{{ $tm->paid_amount }}">
													<b style="color: red">- {{ number_format($tm->paid_amount, 2) }}
												</b>
											@else
												<td class="transfer-money-amount" transfer-money="+{{ $tm->receive_in_amount }}">
													<b>+ {{ number_format($tm->receive_in_amount, 2) }}</b>
												</td>
											@endif
											<td>
												{{-- {{ number_format($total, 2) }} --}}
											</td>
											<td>
												@php
													$print = "";
													if($tm->received_in_id == 1) {
														$print = "Cleared";
													} else {
														$print = $tm->status;
													}
												@endphp
												<span class="label label-success">{{ $print }}</span>
											</td>
										</tr>
					    			@endforeach
					    				<tr>
											<td class="text-right"><b>Total</b></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td id="total-transfer-money"></td>
											<td></td>
											<td></td>
										</tr>
					    		</tbody>
					    	</table>
				    	
			    	</div>
			    </div>
			</div><!-- /.box -->
		</div>
	</div>

@endsection

@push('after_styles')
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css">

	<style>
		.ui-state-active, .ui-widget-content .ui-state-active, .ui-widget-header .ui-state-active, a.ui-button:active, .ui-button:active, .ui-button.ui-state-active:hover {
			background-color: #DB9C35 !important;
			border-color: #DB9C35 !important;
		}
	</style>
@endpush

@push('after_scripts')
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
	<script src="{{ asset('js/accounting.min.js') }}"></script>
	<script>
		$( function() {
		    $( "#tabs" ).tabs();
		});

		$(document).ready(function () {


			//  PAYMENT HISTORY
			var totalStudentPayments = 0;
			$('.student-amount').each(function (i, obj) { 
				totalStudentPayments += parseFloat($(this).attr('student-amount')); 
			});
			$('#total-student-amount').html('<b>' + accounting.formatMoney(totalStudentPayments, '', 2, ',') + '</b>');
			$('#total-student-amount').attr('total-student-amount', totalStudentPayments);
			// $('.')
			
			//  RECEIVED MONEY
			var totalAmountReceivedPayments = 0;
			$('.received-amount').each(function (i, obj) { 
				totalAmountReceivedPayments += parseFloat($(this).attr('received-amount')); 
			});
			$('#total-amount-received').html('<b>' + accounting.formatMoney(totalAmountReceivedPayments, '', 2, ',') + '</b>');
			$('#total-amount-received').attr('total-amount-received', totalAmountReceivedPayments);

			//  SPEND MONEY
			var totalSpendMoney = 0;
			$('.spend-money-amount').each(function (i, obj) { 
				totalSpendMoney += parseFloat($(this).attr('spend-money-amount')); 
			});
			$('#total-amount-spend-money').html('<b>' + accounting.formatMoney(totalSpendMoney, '', 2, ',') + '</b>');
			$('#total-amount-spend-money').attr('total-amount-spend-money', totalSpendMoney);

			// TRANSFER MONEY
			var totalTransferMoney = 0;
			$('.transfer-money-amount').each(function (i, obj) {
				totalTransferMoney += parseFloat($(this).attr('transfer-money'));
			});
			$('#total-transfer-money').html('<b>' + accounting.formatMoney(totalTransferMoney, '', 2, ',') + '</b>');
			$('#total-transfer-money').attr('total-transfer-money', totalTransferMoney);


			// OVERALL TOTAL
			var overallTotal = 0;

			$('#bTable-total-student').html('<small>' + $('#total-student-amount').text() + '</small>');
			$('#bTable-total-received').html('<small>' + $('#total-amount-received').text() + '</small>');
			$('#bTable-total-spend-money').html('<small style="color: red;">' + $('#total-amount-spend-money').text() + '</small>');
			$('#bTable-total-transfer-money').html('<small>' + $('#total-transfer-money').text() + '</small>');

			overallTotal = parseFloat($('#total-student-amount').attr('total-student-amount')) + parseFloat($('#total-amount-received').attr('total-amount-received')) + parseFloat($('#total-transfer-money').attr('total-transfer-money')) + parseFloat($('#total-amount-spend-money').attr('total-amount-spend-money'));
			$('#bTable-overall').html('<b><small>' + accounting.formatMoney(overallTotal, '', 2, ',') + '</small></b>');

			// TRANSFER MONEY
		});
	</script>
@endpush