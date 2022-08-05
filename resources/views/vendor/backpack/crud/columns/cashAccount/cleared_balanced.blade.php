{{-- regular object attribute --}}
@php
	$value = $entry->{$column['name']};
	$totalPaymentHistory =  \App\PaymentHistory::where('payment_type_id', $entry->id)->sum('amount');
	$receivedMoneys =  \App\Models\ReceiveMoney::where('received_in_id', $entry->id)->get();
	$spendMoneys =  \App\Models\SpendMoney::where('paid_from_id', $entry->id)->get();
	$transferMoneys =  \App\Models\TransferMoney::where('paid_from_id', $entry->id)->orWhere('received_in_id', $entry->id)->orderBy('updated_at', 'desc')
                                                        ->get();

	$rmTotal = 0;
	foreach ($receivedMoneys as $receivedMoney) {
		$accounts = json_decode($receivedMoney->accounts);
		foreach ($accounts as $account) {
			$rmTotal += (float)$account->quantity * (float)$account->unit_price;
		}
	}
	$smTotal = 0;
	foreach ($spendMoneys as $spendMoney) {
		$accounts = json_decode($spendMoney->accounts);
		foreach ($accounts as $account) {
			$smTotal += (float)$account->quantity * (float)$account->unit_price;
		}
	}

	$tmTotalPaid = 0;
	$tmTotalReceive = 0;
	foreach ($transferMoneys as $tm) {
		if($tm->paid_from_id == $entry->id) {
			$tmTotalPaid -= $tm->paid_amount;
		}
		else {	
			$tmTotalReceive += $tm->receive_in_amount;
		}
		
	}
	$tmTotal = $tmTotalReceive + ($tmTotalPaid); 

	$overall = $totalPaymentHistory + $rmTotal + $tmTotal - $smTotal ;

@endphp

<span>
	<a href="/admin/cash-account/cleared-balance/{{ $entry->id }}"> {{ $overall }} </a>

	{{-- {{ (array_key_exists('prefix', $column) ? $column['prefix'] : '').str_limit(strip_tags($value), array_key_exists('limit', $column) ? $column['limit'] : 50, "[...]").(array_key_exists('suffix', $column) ? $column['suffix'] : '') }} --}}
</span>
