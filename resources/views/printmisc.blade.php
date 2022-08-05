@php
	$miscsRows = json_decode($miscs[0]->miscellaneous,true);
	$total = 0.0;
@endphp
<!DOCTYPE html>
<html>
<head>
	<style type="text/css">
		body{
			font-family: helvetica;
			font-size: 11px;
		}

		table{
			border: 1px solid black;
			border-bottom: none;
		}

		th{
			background-color: black;
			color: white;
			padding: 4px;
		}
		td{
			padding:0px 4px 0px 4px;
		}
	</style>
	<title></title>
</head>
<body>
	<b>Name:</b> {{$miscs[0]->name}}
	<br>
	<b>School Year:</b> {{$miscs[1]}}
	<br><br>
	<table width="100%" cellspacing="0px">
		<tr>
			<th>Code</th>
			<th>Description</th>
			<th>Amount</th>
		</tr>
	
		@php
			foreach (json_decode($miscs[0]->miscellaneous,true) as $row => $value) {
					echo '<tr>';
					echo "<td>".$value['code']."</td>";
					echo "<td>".$value['description']."</td>";
					echo "<td>".$value['amount']."</td>";
					echo '</tr>';
					$total += $value['amount'];
				}	
		@endphp
		<tr>
			<td style="border-bottom: 1px solid black;" align="left" colspan="2"><b>TOTAL</b></td>
			<td style="border-bottom: 1px solid black;"><b>{{$total}}</b></td>
		</tr>
	</table>
</body>
</html>